<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;

// ─── Halaman publik ───────────────────────────────────────────────────────────
Route::get('/', fn() => view('home'))->name('home');

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
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->middleware('auth')->group(function () {

    Route::controller(UserManagementController::class)->prefix('usermanagement')->group(function () {
        Route::get('/',                    'index')->name('usermanagement.index');
        Route::get('/create',              'create')->name('usermanagement.create');
        Route::post('/store',              'store')->name('usermanagement.store');
        Route::get('/{userid}/edit',       'edit')->name('usermanagement.edit');
        Route::put('/{userid}/update',     'update')->name('usermanagement.update');
        Route::delete('/{userid}',         'destroy')->name('usermanagement.destroy');
        Route::get('/bulkcreate',          'showregisterbulk')->name('usermanagement.bulkcreate');
        Route::get('/download-csv',        'downloadcsvuser')->name('usermanagement.downloadcsvuser');
        Route::post('/bulkstore',          'bulkstoreuser')->name('usermanagement.bulkstoreuser');
    });

    Route::controller(PengembalianController::class)->prefix('pengembalian')->group(function () {
        Route::get('/',                        'index')->name('pengembalian.index');
        Route::put('/{riwayat}/konfirmasi',    'konfirmasi')->name('pengembalian.konfirmasi');
        Route::put('/{riwayat}/tolak',         'tolak')->name('pengembalian.tolak');
    });
});
