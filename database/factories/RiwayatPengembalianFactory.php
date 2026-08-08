<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\RiwayatPengembalian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RiwayatPengembalian>
 */
class RiwayatPengembalianFactory extends Factory
{
    protected $model = RiwayatPengembalian::class;

    public function definition(): array
    {
        return [
            'peminjaman_id'        => PeminjamanFactory::new()->aktif(),
            'catatan_pengembalian' => $this->faker->optional()->sentence(),
            'status'               => 'pending',
            'tanggal_pengajuan'    => now(),
            'tanggal_konfirmasi'   => null,
        ];
    }

    /**
     * State: pengajuan sudah dikonfirmasi.
     */
    public function dikonfirmasi(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'             => 'dikonfirmasi',
            'tanggal_konfirmasi' => now(),
        ]);
    }

    /**
     * State: pengajuan ditolak.
     */
    public function ditolak(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'             => 'ditolak',
            'tanggal_konfirmasi' => null,
        ]);
    }
}
