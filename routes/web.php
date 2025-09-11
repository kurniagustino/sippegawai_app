<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiBerkasController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PegawaiDashboardController;
use App\Http\Controllers\ProfilePegawaiController;
use Illuminate\Support\Facades\Route;


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

    // ==========================================================
    //          BAGIAN INI YANG KITA PERBAIKI
    // ==========================================================
    // Dashboard "Pintar" yang akan mengecek role pengguna
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            // Jika admin, panggil DashboardController (milik admin)
            return app(DashboardController::class)->index();
        } else {
            // Jika bukan admin (pegawai), panggil PegawaiDashboardController
            return app(PegawaiDashboardController::class)->dashboard();
        }
    })->name('dashboard');
    // ==========================================================
    //                  AKHIR DARI PERBAIKAN
    // ==========================================================

    // Profil & Pengaturan Akun Pegawai
    Route::get('/profil', [ProfilePegawaiController::class, 'show'])->name('pegawai.profile.show');
    Route::get('/profil/edit', [ProfilePegawaiController::class, 'edit'])->name('pegawai.profile.edit');
    Route::put('/profil', [ProfilePegawaiController::class, 'update'])->name('pegawai.profile.update');
    Route::get('/ganti-password', [PegawaiDashboardController::class, 'showChangePasswordForm'])->name('pegawai.password.show');
    Route::put('/ganti-password', [PegawaiDashboardController::class, 'updatePassword'])->name('pegawai.password.update');

    // Rute untuk Berkas Pegawai (Hanya untuk pengguna yang login)
    Route::prefix('berkas-saya')->name('pegawai.berkas.')->group(function () {
        Route::get('/', [PegawaiBerkasController::class, 'index'])->name('index');
        Route::get('/upload', [PegawaiBerkasController::class, 'create'])->name('create');
        Route::post('/', [PegawaiBerkasController::class, 'store'])->name('store');
        Route::delete('/{berka}', [PegawaiBerkasController::class, 'destroy'])->name('destroy');
    });

    // Rute untuk CRUD Pegawai (Hanya untuk Admin)
    Route::middleware('role:admin')->group(function () {
        Route::resource('pegawai', PegawaiController::class);
        Route::resource('berkas', BerkasController::class);
        Route::resource('jabatan', JabatanController::class);
    });

});