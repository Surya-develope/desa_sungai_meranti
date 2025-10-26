<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminPengajuanController;
use App\Http\Controllers\SuratTerbitController;

Route::post('register', [AuthController::class, 'register']); // optional
Route::post('login', [AuthController::class, 'login']);

// Public endpoints
Route::post('pengajuan', [PengajuanController::class, 'store']); // warga submit
Route::get('jenis-surat', [PengajuanController::class, 'jenisSuratList']);
Route::get('pengajuan/{id}', [PengajuanController::class, 'show']); // lihat status

// Auth protected (admin) - menggunakan sanctum
Route::middleware('auth:sanctum')->group(function(){
    Route::get('admin/pengajuan', [AdminPengajuanController::class, 'index']);
    Route::get('admin/pengajuan/{id}', [AdminPengajuanController::class, 'show']);
    Route::post('admin/pengajuan/{id}/approve', [AdminPengajuanController::class, 'approve']);
    Route::post('admin/pengajuan/{id}/reject', [AdminPengajuanController::class, 'reject']);
    Route::post('admin/pengajuan/{id}/generate', [AdminPengajuanController::class, 'generate']); // generate doc/pdf
    Route::post('logout', [AuthController::class, 'logout']);
});
