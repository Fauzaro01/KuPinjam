<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('peminjaman:cek-status')->everyMinute();

// Auto-reminder: kirim notifikasi H-1 dan pengingat keterlambatan setiap hari pukul 08:00
Schedule::command('peminjaman:send-reminders')->dailyAt('08:00')->withoutOverlapping();