<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendPeminjamanReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'peminjaman:send-reminders
                            {--dry-run : Jalankan tanpa menyimpan notifikasi, hanya tampilkan hasilnya}';

    /**
     * The console command description.
     */
    protected $description = 'Kirim notifikasi pengingat otomatis untuk peminjaman yang akan segera jatuh tempo atau sudah terlambat';

    public function handle(): int
    {
        $isDry = $this->option('dry-run');
        $now   = Carbon::now();

        // ---- 1. Pengingat H-1 (jatuh tempo besok) ----
        $tomorrow = $now->copy()->addDay();

        $nearDue = Peminjaman::with('user')
            ->where('status_peminjaman', 'dipinjam')
            ->whereBetween('tanggal_kembali', [
                $tomorrow->copy()->startOfDay(),
                $tomorrow->copy()->endOfDay(),
            ])
            ->get();

        $sentH1 = 0;
        foreach ($nearDue as $p) {
            if (! $p->user) continue;

            // Cegah duplikat: cek apakah sudah ada notif H-1 hari ini untuk peminjaman ini
            $alreadySent = Notification::where('user_id', $p->user_id)
                ->where('title', 'like', '%Jatuh Tempo Besok%')
                ->where('message', 'like', "%{$p->id}%")
                ->whereDate('created_at', $now->toDateString())
                ->exists();

            if ($alreadySent) continue;

            if (! $isDry) {
                Notification::create([
                    'user_id' => $p->user_id,
                    'title'   => '⏰ Pengingat: Kendaraan Jatuh Tempo Besok',
                    'message' => "Peminjaman #{$p->id} kendaraan {$p->kendaraan?->plat_nomor} ({$p->kendaraan?->merk} {$p->kendaraan?->model}) harus dikembalikan besok, {$tomorrow->format('d M Y')}. Segera ajukan pengembalian.",
                    'is_read' => false,
                ]);
            }

            $this->line("  [H-1] #{$p->id} → {$p->user->username} ({$p->kendaraan?->plat_nomor})");
            $sentH1++;
        }

        // ---- 2. Terlambat (sudah melewati tanggal kembali) ----
        $overdue = Peminjaman::with('user')
            ->where('status_peminjaman', 'dipinjam')
            ->where('tanggal_kembali', '<', $now)
            ->get();

        $sentOverdue = 0;
        foreach ($overdue as $p) {
            if (! $p->user) continue;

            // Cegah duplikat: notif terlambat maks 1x per hari
            $alreadySent = Notification::where('user_id', $p->user_id)
                ->where('title', 'like', '%Terlambat%')
                ->where('message', 'like', "%{$p->id}%")
                ->whereDate('created_at', $now->toDateString())
                ->exists();

            if ($alreadySent) continue;

            $daysLate = (int) Carbon::parse($p->tanggal_kembali)->diffInDays($now);

            if (! $isDry) {
                Notification::create([
                    'user_id' => $p->user_id,
                    'title'   => '🚨 Peringatan: Kendaraan Terlambat Dikembalikan',
                    'message' => "Peminjaman #{$p->id} kendaraan {$p->kendaraan?->plat_nomor} sudah terlambat {$daysLate} hari dari tanggal kembali. Segera kembalikan dan hubungi admin.",
                    'is_read' => false,
                ]);
            }

            $this->line("  [LATE] #{$p->id} → {$p->user->username} ({$p->kendaraan?->plat_nomor}) — {$daysLate} hari terlambat");
            $sentOverdue++;
        }

        // ---- 3. Notifikasi Admin: ada peminjaman terlambat ----
        if ($overdue->isNotEmpty() && ! $isDry) {
            // Kirim ke semua admin (role admin)
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $alreadySent = Notification::where('user_id', $admin->id)
                    ->where('title', 'like', '%Ringkasan Keterlambatan%')
                    ->whereDate('created_at', $now->toDateString())
                    ->exists();

                if (! $alreadySent) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title'   => "📋 Ringkasan Keterlambatan — {$now->format('d M Y')}",
                        'message' => "Ada {$overdue->count()} peminjaman kendaraan yang terlambat dikembalikan. Harap ditindaklanjuti segera.",
                        'is_read' => false,
                    ]);
                }
            }
        }

        // ---- Summary ----
        $mode = $isDry ? ' [DRY RUN]' : '';
        $this->info("✅ Selesai{$mode} — H-1: {$sentH1} notif, Terlambat: {$sentOverdue} notif.");

        return self::SUCCESS;
    }
}
