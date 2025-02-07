<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\KendaraanController;

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
});

Route::controller(KendaraanController::class)->prefix('kendaraan')->group(function () {
    Route::get('/', 'index')->name('kendaraan.index');
    Route::post('/store' , 'store')->name('kendaraan.store');
    Route::get('/create', 'create')->name('kendaraan.create');
    Route::get('/{kendaraan}/edit', 'edit')->name('kendaraan.edit');
    Route::put('/{kendaraan}/update', 'update')->name('kendaraan.update');
    Route::delete('/{kendaraan}', 'destroy')->name('kendaraan.destroy');
});