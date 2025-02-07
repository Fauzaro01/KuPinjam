<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kendaraan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*
            UserSeeding for InsertData Default USER
        */

        User::insert([
            [
            "id" => Str::random(13),
            "username" => "adminkupinjam",
            "email" => "adminkupinjam@gmail.com",
            "no_telp" => "0000000000",
            "password" => Hash::make("supersecretpassword"),
            "role" => "administrator" 
        ],
        [
            "id" => Str::random(13),
            "username" => "memberkupinjam",
            "email" => "memberkupinjam@gmail.com",
            "no_telp" => "0000000001",
            "password" => Hash::make("supersecretpassword"),
            "role" => "karyawan" 
        ]
    ]);

        /* 

        */

        Kendaraan::factory()->count(15)->create();
    }
}
