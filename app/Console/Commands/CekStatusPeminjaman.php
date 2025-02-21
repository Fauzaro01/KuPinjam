<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\Kendaraan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CekStatusPeminjaman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peminjaman:cek-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memeriksa status kendaraan yang dipinjam dan memperbarui status jika sudah waktunya dikembalikan';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $peminjamans = Peminjaman::where('status_peminjaman', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->get();

        if ($peminjamans->isEmpty()) {
            $this->info('Tidak ada peminjaman yang melewati batas waktu.');
            return;
        }

        foreach ($peminjamans as $peminjaman) {
            $kendaraan = Kendaraan::find($peminjaman->kendaraan_id);

            if (!$kendaraan) {
                Log::error("Kendaraan dengan ID {$peminjaman->kendaraan_id} tidak ditemukan.");
                continue;
            }

            $kendaraan->update(['status' => 'tersedia']);

            $peminjaman->update(['status_peminjaman' => 'selesai']);

            $this->info("Status kendaraan dengan plat nomor {$kendaraan->plat_nomor} telah diperbarui menjadi tersedia.");
            Log::info("Peminjaman ID {$peminjaman->id} telah diperbarui menjadi selesai.");
        }
    }
}
