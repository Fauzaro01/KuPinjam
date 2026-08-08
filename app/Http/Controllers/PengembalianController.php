<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRiwayatPengembalianRequest;
use App\Models\Peminjaman;
use App\Models\RiwayatPengembalian;
use App\Services\PengembalianService;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function __construct(
        protected PengembalianService $pengembalianService
    ) {}

    /**
     * Karyawan mengajukan pengembalian kendaraan.
     */
    public function ajukan(StoreRiwayatPengembalianRequest $request, Peminjaman $peminjaman)
    {
        $this->authorize('ajukanPengembalian', $peminjaman);

        try {
            $this->pengembalianService->ajukanPengembalian(
                $peminjaman,
                $request->catatan_pengembalian
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan pengembalian berhasil dikirim, menunggu konfirmasi admin.');
    }

    /**
     * Admin melihat daftar pengajuan pengembalian — dengan filter status opsional.
     * ?status=pending|dikonfirmasi|ditolak  (kosong = semua)
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $this->authorize('viewAny', RiwayatPengembalian::class);

        $validStatuses = ['pending', 'dikonfirmasi', 'ditolak'];
        $currentStatus = in_array($request->query('status'), $validStatuses)
            ? $request->query('status')
            : null;

        $query = RiwayatPengembalian::with(['peminjaman.user', 'peminjaman.kendaraan'])
            ->latest('tanggal_pengajuan');

        if ($currentStatus) {
            $query->where('status', $currentStatus);
        }

        $riwayats = $query->paginate(15);

        return view('pengembalian.index', compact('riwayats', 'currentStatus'));
    }

    /**
     * Admin mengkonfirmasi pengajuan pengembalian.
     */
    public function konfirmasi(Request $request, RiwayatPengembalian $riwayat)
    {
        $this->authorize('konfirmasi', $riwayat);

        $validated = $request->validate([
            'kondisi_rating'   => 'nullable|integer|min:1|max:5',
            'kondisi_feedback' => 'nullable|string|max:500',
        ]);

        try {
            $this->pengembalianService->konfirmasiPengembalian($riwayat);
            // Simpan rating & feedback setelah konfirmasi berhasil
            $riwayat->update([
                'kondisi_rating'   => $validated['kondisi_rating'] ?? null,
                'kondisi_feedback' => $validated['kondisi_feedback'] ?? null,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengkonfirmasi pengembalian. Silakan coba lagi.');
        }

        return redirect()->route('pengembalian.index')
            ->with('success', 'Pengembalian berhasil dikonfirmasi. Kendaraan kembali tersedia.');
    }

    /**
     * Admin menolak pengajuan pengembalian.
     */
    public function tolak(RiwayatPengembalian $riwayat)
    {
        $this->authorize('tolak', $riwayat);

        $this->pengembalianService->tolakPengembalian($riwayat);

        return redirect()->route('pengembalian.index')
            ->with('success', 'Pengajuan pengembalian telah ditolak.');
    }
}
