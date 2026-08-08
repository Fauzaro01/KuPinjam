<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\RiwayatPengembalian;
use Illuminate\Support\Facades\DB;

class PengembalianService
{
    /**
     * Ajukan pengembalian kendaraan.
     * Cek tidak ada pengajuan pending, lalu buat RiwayatPengembalian baru.
     *
     * @throws \RuntimeException jika sudah ada pending
     */
    public function ajukanPengembalian(Peminjaman $peminjaman, ?string $catatan): RiwayatPengembalian
    {
        $existingPending = RiwayatPengembalian::where('peminjaman_id', $peminjaman->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingPending) {
            throw new \RuntimeException(
                'Peminjaman ini sudah memiliki pengajuan pengembalian yang sedang menunggu konfirmasi.'
            );
        }

        return RiwayatPengembalian::create([
            'peminjaman_id'        => $peminjaman->id,
            'catatan_pengembalian' => $catatan,
            'status'               => 'pending',
            'tanggal_pengajuan'    => now(),
            'tanggal_konfirmasi'   => null,
        ]);
    }

    /**
     * Konfirmasi pengembalian — atomik via DB::transaction.
     * Update riwayat, peminjaman, dan kendaraan sekaligus.
     */
    public function konfirmasiPengembalian(RiwayatPengembalian $riwayat): void
    {
        DB::transaction(function () use ($riwayat) {
            $riwayat->update([
                'status'             => 'dikonfirmasi',
                'tanggal_konfirmasi' => now(),
            ]);

            $riwayat->peminjaman->update([
                'status_peminjaman' => 'selesai',
            ]);

            $riwayat->peminjaman->kendaraan->update([
                'status' => 'tersedia',
            ]);
        });
    }

    /**
     * Tolak pengembalian — hanya update status riwayat.
     * Status peminjaman dan kendaraan tidak berubah.
     */
    public function tolakPengembalian(RiwayatPengembalian $riwayat): void
    {
        $riwayat->update([
            'status' => 'ditolak',
        ]);
    }
}
