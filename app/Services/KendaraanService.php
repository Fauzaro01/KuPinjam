<?php

namespace App\Services;

use App\Models\Kendaraan;

class KendaraanService
{
    public function createKendaraan(array $data): Kendaraan
    {
        return Kendaraan::create(array_merge($data, ['status' => 'tersedia']));
    }

    public function updateKendaraan(Kendaraan $kendaraan, array $data): Kendaraan
    {
        $kendaraan->update($data);
        return $kendaraan->fresh();
    }

    public function deleteKendaraan(Kendaraan $kendaraan): void
    {
        $kendaraan->delete();
    }

    public function setStatus(Kendaraan $kendaraan, string $status): void
    {
        $kendaraan->update(['status' => $status]);
    }
}
