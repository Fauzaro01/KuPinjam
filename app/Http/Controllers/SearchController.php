<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Pencarian global yang mengembalikan JSON.
     * Mencari di tabel Kendaraan, User (admin only), dan Peminjaman.
     */
    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];
        $like = "%{$q}%";

        // Kendaraan
        $kendaraans = Kendaraan::where('plat_nomor', 'like', $like)
            ->orWhere('merk', 'like', $like)
            ->orWhere('model', 'like', $like)
            ->limit(4)
            ->get();

        foreach ($kendaraans as $k) {
            $results[] = [
                'type'     => 'Kendaraan',
                'label'    => "{$k->plat_nomor} — {$k->merk} {$k->model}",
                'sublabel' => ucfirst($k->status),
                'url'      => route('kendaraan.index'),
                'icon'     => 'car',
            ];
        }

        // User (hanya admin yang bisa cari user)
        if (auth()->user()->hasRole('administrator')) {
            $users = User::where('username', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->limit(4)
                ->get();

            foreach ($users as $u) {
                $results[] = [
                    'type'     => 'Pengguna',
                    'label'    => $u->username,
                    'sublabel' => $u->email . ' · ' . ucfirst($u->role),
                    'url'      => route('usermanagement.index'),
                    'icon'     => 'user',
                ];
            }

            // Peminjaman berdasarkan nama karyawan atau plat
            $peminjamans = Peminjaman::with(['user', 'kendaraan'])
                ->whereHas('user', fn($q2) => $q2->where('username', 'like', $like))
                ->orWhereHas('kendaraan', fn($q2) => $q2->where('plat_nomor', 'like', $like))
                ->limit(4)
                ->get();

            foreach ($peminjamans as $p) {
                $results[] = [
                    'type'     => 'Peminjaman',
                    'label'    => ($p->user?->username ?? '-') . ' — ' . ($p->kendaraan?->plat_nomor ?? '-'),
                    'sublabel' => ucfirst($p->status_peminjaman) . ' · ' . \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y'),
                    'url'      => route('peminjaman.index'),
                    'icon'     => 'document',
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}
