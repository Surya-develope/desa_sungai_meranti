<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminPengajuanController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\SuratTerbitController;

// ✅ Semua endpoint lewat /api/...
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Public
Route::post('pengajuan', [PengajuanController::class, 'store']);
Route::get('jenis-surat', [PengajuanController::class, 'jenisSuratList']);
Route::post('tambah-jenis', [JenisSuratController::class, 'store']);
Route::get('pengajuan/{id}', [PengajuanController::class, 'show']);

// Protected routes (admin)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::get('admin/pengajuan', [AdminPengajuanController::class, 'index']);
    Route::get('admin/pengajuan/{id}', [AdminPengajuanController::class, 'show']);
    Route::post('admin/pengajuan/{id}/approve', [AdminPengajuanController::class, 'approve']);
    Route::post('admin/pengajuan/{id}/reject', [AdminPengajuanController::class, 'reject']);
    Route::post('admin/pengajuan/{id}/generate', [AdminPengajuanController::class, 'generate']);
    Route::post('logout', [AuthController::class, 'logout']);
});
