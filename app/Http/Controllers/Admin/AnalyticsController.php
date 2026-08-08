<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Mengembalikan data analitik dalam format JSON untuk render Chart.js
     */
    public function getChartData(): JsonResponse
    {
        // 1. Tren peminjaman 6 bulan terakhir
        $months = [];
        $peminjamanCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->translatedFormat('F Y');
            
            $count = Peminjaman::whereYear('tanggal_pinjam', $date->year)
                ->whereMonth('tanggal_pinjam', $date->month)
                ->count();
            $peminjamanCounts[] = $count;
        }

        // 2. Distribusi status kendaraan
        $tersedia = Kendaraan::where('status', 'tersedia')->count();
        $dipinjam = Kendaraan::where('status', 'dipinjam')->count();
        $perawatan = Kendaraan::where('status', 'perawatan')->count();

        // 3. Top 5 kendaraan paling sering dipinjam
        // Ambil data peminjaman group by kendaraan_id
        $topKendaraansRaw = Peminjaman::select('kendaraan_id', DB::raw('count(*) as total'))
            ->groupBy('kendaraan_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->with('kendaraan')
            ->get();

        $topLabels = [];
        $topData = [];
        foreach ($topKendaraansRaw as $tk) {
            if ($tk->kendaraan) {
                $topLabels[] = $tk->kendaraan->plat_nomor . ' (' . $tk->kendaraan->merk . ')';
                $topData[] = $tk->total;
            }
        }

        return response()->json([
            'trend' => [
                'labels' => $months,
                'data'   => $peminjamanCounts,
            ],
            'status' => [
                'labels' => ['Tersedia', 'Dipinjam', 'Perawatan'],
                'data'   => [$tersedia, $dipinjam, $perawatan],
            ],
            'top' => [
                'labels' => $topLabels,
                'data'   => $topData,
            ]
        ]);
    }
}
