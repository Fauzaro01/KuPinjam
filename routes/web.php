<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\Admin\UserManagementController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view("home");
})->name('home');

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/account-security', 'accountSecurity')->name('accountSecurity');
    Route::post('/changepassword', 'changePassword')->name('changePassword');   
});

Route::controller(KendaraanController::class)->prefix('kendaraan')->group(function () {
    Route::get('/', 'index')->name('kendaraan.index');
    Route::post('/store' , 'store')->name('kendaraan.store');
    Route::get('/create', 'create')->name('kendaraan.create');
    Route::get('/{kendaraan}/edit', 'edit')->name('kendaraan.edit');
    Route::put('/{kendaraan}/update', 'update')->name('kendaraan.update');
    Route::delete('/{kendaraan}', 'destroy')->name('kendaraan.destroy');
});

Route::prefix('/admin')->middleware('auth')->group(function () {
    Route::controller(UserManagementController::class)->prefix('/usermanagement')->group(function () {
        Route::get('/', 'index')->name('usermanagement.index');
        Route::post('/store' , 'store')->name('usermanagement.store');
        Route::get('/create', 'create')->name('usermanagement.create');
        Route::get('/{userid}/edit', 'edit')->name('usermanagement.edit');
        Route::put('/{userid}/update', 'update')->name('usermanagement.update');
        Route::delete('/{userid}', 'destroy')->name('usermanagement.destroy');
        Route::get('/bulkcreate', 'showregisterbulk')->name('usermanagement.bulkcreate');
        Route::get('/download-csv', 'downloadcsvuser')->name('usermanagement.downloadcsvuser');
        Route::post('/bulkstore', 'bulkstoreuser')->name('usermanagement.bulkstoreuser');
    });
});