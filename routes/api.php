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

Route::get('jenis-surat', [JenisSuratController::class, 'jenisSuratList']);
Route::get('pengajuan/form-structure/{jenisSuratId}', [PengajuanController::class, 'getFormStructure']);
Route::post('tambah-jenis', [JenisSuratController::class, 'AddLetter']);
Route::get('pengajuan/{id}', [PengajuanController::class, 'show']);

// Protected routes (auth required)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('pengajuan', [PengajuanController::class, 'addPengajuan']);
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
    
    // Admin routes (with role middleware)
    Route::middleware('role:admin')->group(function () {
        // CRUD Jenis Surat
        Route::get('admin/jenis-surat', [JenisSuratController::class, 'index']);
        Route::post('admin/jenis-surat', [JenisSuratController::class, 'store']);
        Route::get('admin/jenis-surat/{jenisSurat}', [JenisSuratController::class, 'show']);
        Route::put('admin/jenis-surat/{jenisSurat}', [JenisSuratController::class, 'update']);
        Route::delete('admin/jenis-surat/{jenisSurat}', [JenisSuratController::class, 'destroy']);
        Route::patch('admin/jenis-surat/{jenisSurat}/toggle-status', [JenisSuratController::class, 'toggleStatus']);
        
        // Admin Pengajuan
        Route::get('admin/pengajuan', [AdminPengajuanController::class, 'index']);
        Route::get('admin/pengajuan/{id}', [AdminPengajuanController::class, 'show']);
        Route::post('admin/pengajuan/{id}/approve', [AdminPengajuanController::class, 'approve']);
        Route::post('admin/pengajuan/{id}/reject', [AdminPengajuanController::class, 'reject']);
        Route::post('admin/pengajuan/{id}/generate', [AdminPengajuanController::class, 'generate']);
    });
    
    // Public routes that need auth (citizens)
    Route::get('jenis-surat/active', [JenisSuratController::class, 'activeOnly']);
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
