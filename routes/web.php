<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Warga\PengajuanController;
use App\Http\Controllers\Admin\ValidasiController;
use App\Http\Controllers\Sekdes\ProsesSuratController;
use App\Http\Controllers\Kades\TandaTanganController;
use App\Http\Controllers\TrackingController;
// Hapus atau abaikan: use App\Http\resources\views\homeblade;

// ✅ Tes route di luar auth, biar bisa diakses tanpa login
Route::get('/tes', function () {
    return 'Laravel jalan!';
});

// --- Rute Otentikasi (LOGIN & LOGOUT) ---
// Rute ini harus ada di luar middleware 'auth' agar bisa diakses pengguna yang belum login.
// Menggunakan PengajuanController untuk menangani form login warga
Route::get('/login', [PengajuanController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PengajuanController::class, 'login']);
Route::post('/logout', [PengajuanController::class, 'logout'])->name('logout');
// -----------------------------------------


// ✅ Semua route di bawah ini butuh login
Route::middleware(['auth'])->group(function () {

    Route::prefix('warga')->middleware('role:warga')->group(function () {
        Route::get('pengajuan', [PengajuanController::class, 'index']);
        Route::post('pengajuan', [PengajuanController::class, 'store']);
    });

    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('validasi', [ValidasiController::class, 'index']);
        Route::post('validasi/{id}/verify', [ValidasiController::class, 'verify']);
        Route::post('validasi/{id}/reject', [ValidasiController::class, 'reject']);
    });

    Route::prefix('sekdes')->middleware('role:sekdes')->group(function () {
        Route::post('proses/{id}/generate', [ProsesSuratController::class, 'generate']);
    });

    Route::prefix('kades')->middleware('role:kades')->group(function () {
        Route::post('tandatangan/{id}/approve', [TandaTanganController::class, 'approve']);
    });
});

// ✅ Tracking publik (tanpa login)
Route::get('tracking/{kode}', [TrackingController::class, 'check']);

Route::view('/', 'home')->name('home');
