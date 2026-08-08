<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Halaman laporan peminjaman bulanan dengan filter tanggal (Admin only).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Peminjaman::class);

        $startDate = $request->query('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->query('end_date', now()->endOfMonth()->format('Y-m-d'));

        $query = \App\Models\Peminjaman::with(['user', 'kendaraan', 'riwayatPengembalian'])
            ->whereDate('tanggal_pinjam', '>=', $startDate)
            ->whereDate('tanggal_pinjam', '<=', $endDate);

        $peminjamans = $query->latest('tanggal_pinjam')->get();

        // Statistik laporan ringkas
        $totalPeminjaman = $peminjamans->count();
        $totalSelesai    = $peminjamans->where('status_peminjaman', 'selesai')->count();
        $totalDipinjam   = $peminjamans->where('status_peminjaman', 'dipinjam')->count();

        return view('admin.laporan.index', compact(
            'peminjamans',
            'startDate',
            'endDate',
            'totalPeminjaman',
            'totalSelesai',
            'totalDipinjam'
        ));
    }
}
