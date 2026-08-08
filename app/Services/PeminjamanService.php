<?php

namespace App\Services;

use App\Exceptions\KendaraanTidakTersediaException;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PeminjamanService
{
    /**
     * Satu metode terpusat untuk membuat peminjaman (admin & karyawan).
     * Memvalidasi status kendaraan, membuat record, mengubah status kendaraan.
     *
     * @throws KendaraanTidakTersediaException
     */
    public function createPeminjaman(array $data, User $peminjam): Peminjaman
    {
        $kendaraan = Kendaraan::findOrFail($data['kendaraan_id']);

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

        return $peminjaman;
    }

    /**
     * Memperbarui record peminjaman tanpa mengosongkan status_peminjaman.
     */
    public function updatePeminjaman(Peminjaman $peminjaman, array $data): Peminjaman
    {
        $updateData = [
            'user_id'        => $data['user_id'],
            'kendaraan_id'   => $data['kendaraan_id'],
            'tanggal_pinjam' => Carbon::parse($data['tanggal_pinjam']),
            'tanggal_kembali'=> Carbon::parse($data['tanggal_kembali']),
            'tujuan'         => $data['tujuan'],
            'keterangan'     => $data['keterangan'] ?? null,
        ];

        // Hanya update status_peminjaman jika secara eksplisit dikirim
        if (isset($data['status_peminjaman']) && $data['status_peminjaman'] !== null) {
            $updateData['status_peminjaman'] = $data['status_peminjaman'];
        }

        $peminjaman->update($updateData);

        return $peminjaman->fresh();
    }

    /**
     * Menghapus peminjaman dan mengembalikan status kendaraan ke tersedia.
     */
    public function deletePeminjaman(Peminjaman $peminjaman): void
    {
        $kendaraan = $peminjaman->kendaraan;
        $peminjaman->delete();

        if ($kendaraan) {
            $kendaraan->update(['status' => 'tersedia']);
        }
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
