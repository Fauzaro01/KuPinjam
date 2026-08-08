<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    protected $model = Peminjaman::class;

    public function definition(): array
    {
        $tanggalPinjam  = $this->faker->dateTimeBetween('-30 days', 'now');
        $tanggalKembali = $this->faker->dateTimeBetween($tanggalPinjam, '+14 days');

        return [
            'user_id'           => User::factory()->state(['role' => 'karyawan']),
            'kendaraan_id'      => Kendaraan::factory()->state(['status' => 'dipinjam']),
            'tanggal_pinjam'    => $tanggalPinjam,
            'tanggal_kembali'   => $tanggalKembali,
            'status_peminjaman' => 'dipinjam',
            'tujuan'            => $this->faker->sentence(4),
            'keterangan'        => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * State: peminjaman berstatus dipinjam (aktif).
     */
    public function aktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_peminjaman' => 'dipinjam',
        ]);
    }

    /**
     * State: peminjaman berstatus selesai.
     * Tanggal kembali di masa lalu.
     */
    public function selesai(): static
    {
        return $this->state(function (array $attributes) {
            $pinjam  = $this->faker->dateTimeBetween('-60 days', '-15 days');
            $kembali = $this->faker->dateTimeBetween($pinjam, '-1 day');

            return [
                'status_peminjaman' => 'selesai',
                'tanggal_pinjam'    => $pinjam,
                'tanggal_kembali'   => $kembali,
                'kendaraan_id'      => Kendaraan::factory()->state(['status' => 'tersedia']),
            ];
        });
    }
}
