<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\RiwayatPengembalian;
use Illuminate\Support\Facades\DB;

class PengembalianService
{
    public function __construct(
        protected ActivityLogService $activityLogService,
        protected NotificationService $notificationService
    ) {}

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

        $riwayat = RiwayatPengembalian::create([
            'peminjaman_id'        => $peminjaman->id,
            'catatan_pengembalian' => $catatan,
            'status'               => 'pending',
            'tanggal_pengajuan'    => now(),
            'tanggal_konfirmasi'   => null,
        ]);

        // Log Aktivitas
        $this->activityLogService->log(
            'pengembalian_ajukan',
            "Karyawan '{$peminjaman->user->username}' mengajukan pengembalian kendaraan '{$peminjaman->kendaraan->plat_nomor}'"
        );

        // Notifikasi ke seluruh administrator
        $admins = \App\Models\User::where('role', 'administrator')->get();
        foreach ($admins as $admin) {
            $this->notificationService->sendNotification(
                $admin->id,
                'Pengajuan Pengembalian Baru',
                "Karyawan '{$peminjaman->user->username}' telah mengajukan pengembalian kendaraan '{$peminjaman->kendaraan->plat_nomor}'."
            );
        }

        return $riwayat;
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

            // Log Aktivitas
            $this->activityLogService->log(
                'pengembalian_konfirmasi',
                "Admin menyetujui pengembalian kendaraan '{$riwayat->peminjaman->kendaraan->plat_nomor}'"
            );

            // Notifikasi ke karyawan peminjam
            $this->notificationService->sendNotification(
                $riwayat->peminjaman->user_id,
                'Pengembalian Disetujui',
                "Pengembalian kendaraan '{$riwayat->peminjaman->kendaraan->plat_nomor}' telah dikonfirmasi oleh Admin."
            );
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

        // Log Aktivitas
        $this->activityLogService->log(
            'pengembalian_tolak',
            "Admin menolak pengembalian kendaraan '{$riwayat->peminjaman->kendaraan->plat_nomor}'"
        );

        // Notifikasi ke karyawan peminjam
        $this->notificationService->sendNotification(
            $riwayat->peminjaman->user_id,
            'Pengembalian Ditolak',
            "Pengajuan pengembalian kendaraan '{$riwayat->peminjaman->kendaraan->plat_nomor}' ditolak oleh Admin."
        );
    }
}
