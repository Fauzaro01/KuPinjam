<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'totalKendaraan'  => Kendaraan::where('status', 'tersedia')->count(),
            'totalAktif'      => Peminjaman::where('status_peminjaman', 'dipinjam')->count(),
            'totalPengguna'   => User::count(),
            'kepuasan'        => 98,
        ];

        return view('home', $stats);
    }
}
