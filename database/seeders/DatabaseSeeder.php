<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Default: memanggil DevelopmentSeeder yang berisi data lengkap dan realistis.
     * Untuk production, ganti dengan seeder yang hanya berisi akun admin awal.
     */
    public function run(): void
    {
        $this->call(DevelopmentSeeder::class);
    }
}
