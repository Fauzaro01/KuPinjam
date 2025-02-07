<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kendaraan>
 */
class KendaraanFactory extends Factory
{
    public function definition(): array
    {
        $jenisKendaraan = $this->faker->randomElement(['motor', 'mobil']);
        $statusOptions = ['tersedia', 'dipinjam', 'perawatan'];

        // Data berdasarkan jenis kendaraan
        $merk = $jenisKendaraan === 'mobil'
            ? $this->faker->randomElement(['Toyota', 'Honda', 'Suzuki', 'Mitsubishi', 'Daihatsu'])
            : $this->faker->randomElement(['Yamaha', 'Honda', 'Kawasaki', 'Suzuki', 'Vespa']);

        $model = $jenisKendaraan === 'mobil'
            ? $this->faker->randomElement(['Avanza', 'Civic', 'Pajero', 'Xenia', 'Jazz'])
            : $this->faker->randomElement(['Vario', 'Nmax', 'Beat', 'Z1000', 'Scoopy']);

        $platNomor = $jenisKendaraan === 'mobil'
            ? strtoupper($this->faker->bothify('B #### ??'))  // Plat mobil: B 1234 AB
            : strtoupper($this->faker->bothify('B ### ?'))    // Plat motor: B 123 A

        ;

        return [
            'plat_nomor'     => $platNomor,
            'merk'           => $merk,
            'model'          => $model,
            'tahun'          => $this->faker->numberBetween(2000, 2024),
            'jenis_kendaraan'=> $jenisKendaraan,
            'status'         => $this->faker->randomElement($statusOptions),
        ];
    }
}
