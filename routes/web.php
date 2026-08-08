<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;

// ─── Halaman publik ───────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── Auth (guest only untuk halaman form, throttle pada POST) ────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
});

Route::post('/store',        [AuthController::class, 'store'])
     ->middleware(['guest', 'throttle:10,1'])
     ->name('store');

Route::post('/authenticate', [AuthController::class, 'authenticate'])
     ->middleware(['guest', 'throttle:10,1'])
     ->name('authenticate');

// ─── Auth (perlu login) ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',       [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout',         [AuthController::class, 'logout'])->name('logout');
    Route::get('/account-security',[AuthController::class, 'accountSecurity'])->name('accountSecurity');
    Route::post('/changepassword', [AuthController::class, 'changePassword'])->name('changePassword');
});

// ─── Kendaraan & Peminjaman (auth) ────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::controller(KendaraanController::class)->prefix('kendaraan')->group(function () {
        Route::get('/',                   'index')->name('kendaraan.index');
        Route::get('/create',             'create')->name('kendaraan.create');
        Route::post('/store',             'store')->name('kendaraan.store');
        Route::get('/{kendaraan}/edit',   'edit')->name('kendaraan.edit');
        Route::put('/{kendaraan}/update', 'update')->name('kendaraan.update');
        Route::delete('/{kendaraan}',     'destroy')->name('kendaraan.destroy');
    });

    // Restore soft-deleted kendaraan (admin only)
    Route::patch('/kendaraan/{id}/restore', [KendaraanController::class, 'restore'])
         ->middleware('admin')
         ->name('kendaraan.restore');

    Route::controller(PeminjamanController::class)->prefix('peminjaman')->group(function () {
        Route::get('/',                      'index')->name('peminjaman.index');
        Route::get('/create',                'create')->name('peminjaman.create');
        Route::get('/export-csv',            'exportCsv')->name('peminjaman.export-csv');
        Route::post('/store',                'store')->name('peminjaman.store');
        Route::post('/pinjam',               'pinjam')->name('peminjaman.pinjam');
        Route::get('/{peminjaman}/edit',     'edit')->name('peminjaman.edit');
        Route::put('/{peminjaman}/update',   'update')->name('peminjaman.update');
        Route::delete('/{peminjaman}',       'destroy')->name('peminjaman.destroy');
    });

    // Profil
    Route::get('/profile',         [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile',         [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // Pengembalian (karyawan ajukan)
    Route::post('/pengembalian/{peminjaman}', [PengembalianController::class, 'ajukan'])
         ->name('pengembalian.ajukan');

    // Notifikasi
    Route::patch('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])
         ->name('notifications.read');
    Route::patch('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
         ->name('notifications.read-all');

    // Pencarian Global
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search'])
         ->name('search');

    // Surat Jalan Peminjaman
    Route::get('/peminjaman/{peminjaman}/surat-jalan', [\App\Http\Controllers\PeminjamanController::class, 'suratJalan'])
         ->name('peminjaman.surat-jalan');

    // Kalender Peminjaman
    Route::get('/kalender', [\App\Http\Controllers\PeminjamanController::class, 'kalender'])
         ->name('peminjaman.kalender');
    Route::get('/api/peminjaman-kalender', [\App\Http\Controllers\PeminjamanController::class, 'apiKalender'])
         ->name('peminjaman.api-kalender');
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::controller(UserManagementController::class)->prefix('usermanagement')->group(function () {
        Route::get('/',                    'index')->name('usermanagement.index');
        Route::get('/create',              'create')->name('usermanagement.create');
        Route::post('/store',              'store')->name('usermanagement.store');
        Route::get('/{userid}/edit',       'edit')->name('usermanagement.edit');
        Route::put('/{userid}/update',     'update')->name('usermanagement.update');
        Route::delete('/{userid}',         'destroy')->name('usermanagement.destroy');
        Route::patch('/{id}/restore',      'restore')->name('usermanagement.restore');
        Route::get('/bulkcreate',          'showregisterbulk')->name('usermanagement.bulkcreate');
        Route::get('/download-csv',        'downloadcsvuser')->name('usermanagement.downloadcsvuser');
        Route::post('/bulkstore',          'bulkstoreuser')->name('usermanagement.bulkstoreuser');
    });

    Route::controller(PengembalianController::class)->prefix('pengembalian')->group(function () {
        Route::get('/',                        'index')->name('pengembalian.index');
        Route::put('/{riwayat}/konfirmasi',    'konfirmasi')->name('pengembalian.konfirmasi');
        Route::put('/{riwayat}/tolak',         'tolak')->name('pengembalian.tolak');
    });

    // Activity Log & Laporan Cetak
    Route::get('/activity-log', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])
         ->name('admin.activity-log');
    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])
         ->name('admin.laporan');
    Route::get('/analytics/data', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getChartData'])
         ->name('admin.analytics.data');

    // Perawatan Kendaraan
    Route::patch('/perawatan/{perawatan}/selesai', [\App\Http\Controllers\Admin\PerawatanController::class, 'selesai'])
         ->name('perawatan.selesai');
    Route::resource('perawatan', \App\Http\Controllers\Admin\PerawatanController::class);

    // Pengumuman Admin
    Route::patch('/announcement/{announcement}/toggle', [\App\Http\Controllers\Admin\AnnouncementController::class, 'toggle'])
         ->name('announcement.toggle');
    Route::resource('announcement', \App\Http\Controllers\Admin\AnnouncementController::class);
});
