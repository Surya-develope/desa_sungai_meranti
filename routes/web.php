<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminPengajuanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WargaDashboardController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');

    Route::middleware('role:warga')->group(function () {
        Route::get('/warga/dashboard', [WargaDashboardController::class, 'index'])->name('warga.dashboard');
        Route::get('/warga/pengajuan/{pengajuan}', [WargaDashboardController::class, 'show'])->name('warga.pengajuan.show');
        Route::post('/warga/pengajuan/{pengajuan}/batal', [WargaDashboardController::class, 'cancel'])->name('warga.pengajuan.cancel');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard'); // Staff/admin dashboard view
        })->name('admin.dashboard');
    });

    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('admin.pengajuan.index');
        Route::get('/pengajuan/{id}', [AdminPengajuanController::class, 'show'])->name('admin.pengajuan.show');
        Route::post('/pengajuan/{id}/approve', [AdminPengajuanController::class, 'approve'])->name('admin.pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [AdminPengajuanController::class, 'reject'])->name('admin.pengajuan.reject');
        Route::post('/pengajuan/{id}/generate', [AdminPengajuanController::class, 'generate'])->name('admin.pengajuan.generate');
    });
});

Route::view('/penduduk', 'home')->name('penduduk');
Route::view('/profil', 'home')->name('profil');
    
