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
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('pengajuan', [AdminPengajuanController::class, 'index']);
        Route::get('pengajuan/{id}', [AdminPengajuanController::class, 'show']);
        Route::post('pengajuan/{id}/approve', [AdminPengajuanController::class, 'approve']);
        Route::post('pengajuan/{id}/reject', [AdminPengajuanController::class, 'reject']);
        Route::post('pengajuan/{id}/generate', [AdminPengajuanController::class, 'generate']);
    });

    // Auth routes
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('/routes', function () {
    $routes = [];
    foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
        $routes[] = [
            'uri' => $route->uri(),
            'methods' => $route->methods(),
        ];
    }
    return response()->json($routes);
})->name('api.routes');
