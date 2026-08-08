<?php

namespace App\Services;

use App\Models\Kendaraan;

class KendaraanService
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {}

    public function createKendaraan(array $data): Kendaraan
    {
        $kendaraan = Kendaraan::create(array_merge($data, ['status' => 'tersedia']));

        $this->activityLogService->log(
            'kendaraan_buat',
            "Kendaraan dengan plat '{$kendaraan->plat_nomor}' ({$kendaraan->merk} {$kendaraan->model}) ditambahkan"
        );

        return $kendaraan;
    }

    public function updateKendaraan(Kendaraan $kendaraan, array $data): Kendaraan
    {
        $kendaraan->update($data);

        $this->activityLogService->log(
            'kendaraan_update',
            "Detail kendaraan '{$kendaraan->plat_nomor}' diperbarui"
        );

        return $kendaraan->fresh();
    }

    public function deleteKendaraan(Kendaraan $kendaraan): void
    {
        $plat = $kendaraan->plat_nomor;
        $kendaraan->delete();

        $this->activityLogService->log(
            'kendaraan_hapus',
            "Kendaraan dengan plat '{$plat}' dihapus (soft delete)"
        );
    }

    public function setStatus(Kendaraan $kendaraan, string $status): void
    {
        $kendaraan->update(['status' => $status]);

        $this->activityLogService->log(
            'kendaraan_status',
            "Status kendaraan '{$kendaraan->plat_nomor}' diubah menjadi '{$status}'"
        );
    }
}
