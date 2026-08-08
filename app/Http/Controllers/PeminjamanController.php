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
            $peminjamans = Peminjaman::with(['user', 'kendaraan', 'riwayatPengembalian'])->get();
        } else {
            $peminjamans = Peminjaman::with(['user', 'kendaraan', 'riwayatPengembalian'])
                ->where('user_id', Auth::user()->id)
                ->get();
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

        $this->peminjamanService->updatePeminjaman($peminjaman, $request->validated());

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil diperbarui!');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $this->authorize('delete', $peminjaman);

        $this->peminjamanService->deletePeminjaman($peminjaman);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus!');
    }

    /**
     * Export semua peminjaman ke CSV (administrator only).
     */
    public function exportCsv()
    {
        $this->authorize('viewAny', Peminjaman::class);

        $peminjamans = Peminjaman::with(['user', 'kendaraan'])->get();

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
        ]);

        try {
            $this->peminjamanService->createPeminjaman([
                'kendaraan_id'   => $validated['kendaraan_id'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali'=> $validated['tanggal_kembali'],
                'tujuan'         => $validated['tujuan'],
                'keterangan'     => $validated['keterangan'] ?? null,
            ], Auth::user());
        } catch (KendaraanTidakTersediaException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Kendaraan berhasil dipinjam!');
    }
}
