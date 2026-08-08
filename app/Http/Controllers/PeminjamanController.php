<?php

namespace App\Http\Controllers;

use App\Exceptions\KendaraanTidakTersediaException;
use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Requests\UpdatePeminjamanRequest;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\User;
use App\Services\PeminjamanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function __construct(
        protected PeminjamanService $peminjamanService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Peminjaman::class);

        if (Auth::user()->hasRole('administrator')) {
            $peminjamans = Peminjaman::with(['user', 'kendaraan', 'riwayatPengembalian'])
                ->latest()
                ->paginate(15);
        } else {
            $peminjamans = Peminjaman::with(['user', 'kendaraan', 'riwayatPengembalian'])
                ->where('user_id', Auth::user()->id)
                ->latest()
                ->paginate(10);
        }

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $this->authorize('adminCreate', Peminjaman::class);

        $kendaraans = Kendaraan::where('status', 'tersedia')->get();
        $users = User::where('role', 'karyawan')->get();

        return view('peminjaman.create', compact('kendaraans', 'users'));
    }

    public function store(StorePeminjamanRequest $request)
    {
        $this->authorize('adminCreate', Peminjaman::class);

        $peminjam = User::findOrFail($request->user_id);

        try {
            $this->peminjamanService->createPeminjaman($request->validated(), $peminjam);
        } catch (KendaraanTidakTersediaException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dibuat!');
    }

    public function edit(Peminjaman $peminjaman)
    {
        $this->authorize('update', $peminjaman);

        $kendaraans = $this->peminjamanService->getKendaraanForEdit($peminjaman);
        $users = User::where('role', 'karyawan')->get();

        return view('peminjaman.edit', compact('peminjaman', 'kendaraans', 'users'));
    }

    public function update(UpdatePeminjamanRequest $request, Peminjaman $peminjaman)
    {
        $this->authorize('update', $peminjaman);

        try {
            $this->peminjamanService->updatePeminjaman($peminjaman, $request->validated());
        } catch (KendaraanTidakTersediaException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil diperbarui!');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $this->authorize('delete', $peminjaman);

        try {
            $this->peminjamanService->deletePeminjaman($peminjaman);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus!');
    }

    /**
     * Export semua peminjaman ke CSV (administrator only), dengan opsional filter range tanggal.
     */
    public function exportCsv(Request $request)
    {
        $this->authorize('viewAny', Peminjaman::class);

        $query = Peminjaman::with(['user', 'kendaraan']);

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->end_date);
        }

        $peminjamans = $query->get();

        $filename = 'laporan-peminjaman-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($peminjamans) {
            $handle = fopen('php://output', 'w');

            // BOM untuk Excel agar UTF-8 terbaca dengan benar
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($handle, [
                'ID', 'Nama Karyawan', 'Plat Kendaraan',
                'Jenis', 'Tanggal Pinjam', 'Tanggal Kembali',
                'Tujuan', 'Status',
            ]);

            foreach ($peminjamans as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->user?->username  ?? '-',
                    $p->kendaraan?->plat_nomor ?? '-',
                    $p->kendaraan?->jenis_kendaraan ?? '-',
                    \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y'),
                    \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y'),
                    $p->tujuan,
                    $p->status_peminjaman,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Alur peminjaman oleh Karyawan (form karyawan via modal).
     */
    public function pinjam(Request $request)
    {
        $this->authorize('create', Peminjaman::class);

        $validated = $request->validate([
            'kendaraan_id'   => 'required|exists:kendaraans,id',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali'=> 'required|date|after:tanggal_pinjam',
            'tujuan'         => 'required|string|max:255',
            'keterangan'     => 'nullable|string',
            'dokumens'       => 'nullable|array|max:3',
            'dokumens.*'     => 'file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        try {
            $this->peminjamanService->createPeminjaman($validated, Auth::user());
        } catch (KendaraanTidakTersediaException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Kendaraan berhasil dipinjam!');
    }

    /**
     * Tampilan Surat Jalan resmi peminjaman kendaraan (untuk cetak/PDF).
     */
    public function suratJalan(Peminjaman $peminjaman)
    {
        // Karyawan hanya bisa melihat miliknya sendiri
        if (Auth::user()->hasRole('karyawan') && $peminjaman->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke Surat Jalan ini.');
        }

        $peminjaman->load(['user', 'kendaraan']);
        return view('peminjaman.surat-jalan', compact('peminjaman'));
    }

    /**
     * Tampilan utama dashboard kalender peminjaman.
     */
    public function kalender()
    {
        $this->authorize('viewAny', Peminjaman::class);
        $kendaraans = Kendaraan::all();
        return view('peminjaman.kalender', compact('kendaraans'));
    }

    /**
     * API event peminjaman dalam format JSON untuk kalender.
     */
    public function apiKalender(Request $request)
    {
        $this->authorize('viewAny', Peminjaman::class);

        $query = Peminjaman::with(['user', 'kendaraan'])
            ->whereIn('status_peminjaman', ['dipinjam', 'selesai']);

        if ($request->filled('kendaraan_id')) {
            $query->where('kendaraan_id', $request->kendaraan_id);
        }

        $peminjamans = $query->get();

        $events = $peminjamans->map(function ($p) {
            // Rentang tanggal dari tanggal_pinjam sampai tanggal_kembali
            return [
                'id' => $p->id,
                'user' => $p->user?->username ?? 'User',
                'plat_nomor' => $p->kendaraan?->plat_nomor ?? '-',
                'kendaraan' => ($p->kendaraan?->merk ?? '') . ' ' . ($p->kendaraan?->model ?? ''),
                'start' => \Carbon\Carbon::parse($p->tanggal_pinjam)->toIso8601String(),
                'end' => \Carbon\Carbon::parse($p->tanggal_kembali)->toIso8601String(),
                'tujuan' => $p->tujuan,
                'status' => $p->status_peminjaman,
            ];
        });

        return response()->json($events);
    }
}
