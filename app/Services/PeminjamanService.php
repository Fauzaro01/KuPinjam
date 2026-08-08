<?php

namespace App\Services;

use App\Exceptions\KendaraanTidakTersediaException;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PeminjamanService
{
    public function __construct(
        protected ActivityLogService $activityLogService,
        protected NotificationService $notificationService
    ) {}

    /**
     * Satu metode terpusat untuk membuat peminjaman (admin & karyawan).
     * Memvalidasi status kendaraan, membuat record, mengubah status kendaraan.
     *
     * @throws KendaraanTidakTersediaException
     */
    public function createPeminjaman(array $data, User $peminjam): Peminjaman
    {
        return DB::transaction(function () use ($data, $peminjam) {
            $kendaraan = Kendaraan::where('id', $data['kendaraan_id'])->lockForUpdate()->firstOrFail();

            if ($kendaraan->status !== 'tersedia') {
                throw new KendaraanTidakTersediaException(
                    "Kendaraan dengan plat {$kendaraan->plat_nomor} tidak tersedia untuk dipinjam."
                );
            }

            $peminjaman = Peminjaman::create([
                'user_id'           => $peminjam->id,
                'kendaraan_id'      => $kendaraan->id,
                'tanggal_pinjam'    => Carbon::parse($data['tanggal_pinjam']),
                'tanggal_kembali'   => Carbon::parse($data['tanggal_kembali']),
                'status_peminjaman' => 'dipinjam',
                'tujuan'            => $data['tujuan'],
                'keterangan'        => $data['keterangan'] ?? null,
            ]);

            $kendaraan->update(['status' => 'dipinjam']);

            // Upload dokumen lampiran jika ada
            if (isset($data['dokumens']) && is_array($data['dokumens'])) {
                foreach ($data['dokumens'] as $file) {
                    $path = $file->store('peminjaman-docs', 'public');
                    \App\Models\PeminjamanDokumen::create([
                        'peminjaman_id' => $peminjaman->id,
                        'uploader_id'   => $peminjam->id,
                        'file_path'     => $path,
                        'file_name'     => $file->getClientOriginalName(),
                        'jenis'         => 'surat_tugas',
                    ]);
                }
            }

            // Catat Aktivitas
            $this->activityLogService->log(
                'peminjaman_buat',
                "Peminjaman kendaraan '{$kendaraan->plat_nomor}' dibuat untuk karyawan '{$peminjam->username}'"
            );

            // Kirim notifikasi ke seluruh administrator
            $admins = User::where('role', 'administrator')->get();
            foreach ($admins as $admin) {
                $this->notificationService->sendNotification(
                    $admin->id,
                    'Pengajuan Peminjaman Baru',
                    "Karyawan '{$peminjam->username}' telah meminjam kendaraan '{$kendaraan->plat_nomor}'."
                );
            }

            return $peminjaman;
        });
    }

    /**
     * Memperbarui record peminjaman tanpa mengosongkan status_peminjaman.
     */
    public function updatePeminjaman(Peminjaman $peminjaman, array $data): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $data) {
            $oldKendaraanId = $peminjaman->kendaraan_id;
            $newKendaraanId = $data['kendaraan_id'];

            if ($oldKendaraanId != $newKendaraanId) {
                $oldKendaraan = Kendaraan::find($oldKendaraanId);
                if ($oldKendaraan) $oldKendaraan->update(['status' => 'tersedia']);

                $newKendaraan = Kendaraan::where('id', $newKendaraanId)->lockForUpdate()->firstOrFail();
                if ($newKendaraan->status !== 'tersedia') {
                    throw new KendaraanTidakTersediaException("Kendaraan tidak tersedia.");
                }
                $newKendaraan->update(['status' => 'dipinjam']);
            }

            $updateData = [
                'user_id'        => $data['user_id'],
                'kendaraan_id'   => $newKendaraanId,
                'tanggal_pinjam' => Carbon::parse($data['tanggal_pinjam']),
                'tanggal_kembali'=> Carbon::parse($data['tanggal_kembali']),
                'tujuan'         => $data['tujuan'],
                'keterangan'     => $data['keterangan'] ?? null,
            ];

            if (isset($data['status_peminjaman'])) {
                $updateData['status_peminjaman'] = $data['status_peminjaman'];
            }

            if (array_key_exists('admin_notes', $data)) {
                $updateData['admin_notes'] = $data['admin_notes'];
            }

            $peminjaman->update($updateData);

            // Log update aktivitas
            $this->activityLogService->log(
                'peminjaman_update',
                "Peminjaman ID '{$peminjaman->id}' diperbarui oleh administrator"
            );

            return $peminjaman->fresh();
        });
    }

    /**
     * Menghapus peminjaman dan mengembalikan status kendaraan ke tersedia.
     */
    public function deletePeminjaman(Peminjaman $peminjaman): void
    {
        DB::transaction(function () use ($peminjaman) {
            $kendaraan = $peminjaman->kendaraan;
            $peminjamanId = $peminjaman->id;
            $peminjaman->delete();

            if ($kendaraan) {
                $hasPending = Peminjaman::where('kendaraan_id', $kendaraan->id)
                    ->where('status_peminjaman', 'dipinjam')
                    ->exists();

                if (!$hasPending) {
                    $kendaraan->update(['status' => 'tersedia']);
                }
            }

            // Log hapus aktivitas
            $this->activityLogService->log(
                'peminjaman_hapus',
                "Peminjaman ID '{$peminjamanId}' dihapus oleh administrator"
            );
        });
    }

    /**
     * Mengambil daftar kendaraan untuk dropdown edit:
     * kendaraan tersedia + kendaraan yang saat ini ada di peminjaman.
     */
    public function getKendaraanForEdit(Peminjaman $peminjaman): Collection
    {
        $tersedia = Kendaraan::where('status', 'tersedia')->get();
        $kendaraanAktif = $peminjaman->kendaraan;

        if ($kendaraanAktif && !$tersedia->contains('id', $kendaraanAktif->id)) {
            return $tersedia->push($kendaraanAktif);
        }

        return $tersedia;
    }
}
