<?php

namespace App\Services;

use App\Models\Kendaraan;
use App\Models\PerawatanKendaraan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerawatanService
{
    public function getAll()
    {
        return PerawatanKendaraan::with(['kendaraan', 'admin'])
            ->latest()
            ->paginate(15);
    }

    public function getKendaraanList()
    {
        return Kendaraan::orderBy('plat_nomor')->get(['id', 'plat_nomor', 'merk', 'model', 'status']);
    }

    public function jadwalkan(array $data): PerawatanKendaraan
    {
        return DB::transaction(function () use ($data) {
            // Set status kendaraan menjadi perawatan
            $kendaraan = Kendaraan::findOrFail($data['kendaraan_id']);
            $kendaraan->update(['status' => 'perawatan']);

            return PerawatanKendaraan::create([
                'kendaraan_id'      => $data['kendaraan_id'],
                'admin_id'          => Auth::id(),
                'jenis_perawatan'   => $data['jenis_perawatan'],
                'tanggal_mulai'     => $data['tanggal_mulai'],
                'estimasi_selesai'  => $data['estimasi_selesai'] ?? null,
                'catatan'           => $data['catatan'] ?? null,
                'status'            => 'dijadwalkan',
            ]);
        });
    }

    public function update(PerawatanKendaraan $perawatan, array $data): PerawatanKendaraan
    {
        $perawatan->update([
            'jenis_perawatan'   => $data['jenis_perawatan'],
            'tanggal_mulai'     => $data['tanggal_mulai'],
            'estimasi_selesai'  => $data['estimasi_selesai'] ?? null,
            'catatan'           => $data['catatan'] ?? null,
        ]);

        return $perawatan->fresh();
    }

    public function selesaikan(PerawatanKendaraan $perawatan): PerawatanKendaraan
    {
        return DB::transaction(function () use ($perawatan) {
            $perawatan->update([
                'status'           => 'selesai',
                'tanggal_selesai'  => now()->toDateString(),
            ]);

            // Kembalikan kendaraan ke tersedia
            $perawatan->kendaraan()->update(['status' => 'tersedia']);

            return $perawatan->fresh();
        });
    }

    public function hapus(PerawatanKendaraan $perawatan): void
    {
        DB::transaction(function () use ($perawatan) {
            // Kembalikan kendaraan ke tersedia jika perawatan dibatalkan
            if ($perawatan->status !== 'selesai') {
                $perawatan->kendaraan()->update(['status' => 'tersedia']);
            }
            $perawatan->delete();
        });
    }
}
