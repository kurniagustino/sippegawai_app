<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\BerkasController;

// 1. Arahkan halaman utama ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Rute untuk pengguna yang belum login (tamu)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
});

// 3. Rute untuk pengguna yang sudah login (terotentikasi)
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Halaman Dashboard Admin
    Route::get('/dashboard-admin', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');

    // Halaman Dashboard Pegawai
    Route::get('/dashboard', function () {
        return view('dashboard.pegawai');
    })->name('home');

    // Rute untuk semua proses CRUD Pegawai
    Route::resource('pegawai', PegawaiController::class);

    // Rute untuk semua proses CRUD Berkas
    Route::resource('berkas', BerkasController::class);
});