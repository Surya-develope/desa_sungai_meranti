<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminPengajuanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WargaDashboardController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\TrackingController;

Route::get('/', function () {
    // Check if user is authenticated
    if (Auth::check()) {
        $user = Auth::user();
        // Redirect based on user role
        $userRole = $user->role ? $user->role->nama_role : 'warga';
        if ($userRole === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('warga.dashboard');
        }
    }
    // Show home page for unauthenticated users
    return view('home');
})->name('home');

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('forgot-password.post');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset-password');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password.post');

// Public Routes
Route::get('/administrasi', [PengajuanController::class, 'jenis'])->name('administrasi');
Route::view('/penduduk', 'home')->name('penduduk');
Route::view('/profil', 'home')->name('profil');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Pengajuan Routes
    Route::get('pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('pengajuan/create', [PengajuanController::class, 'store'])->name('pengajuan.create.post');

    // Warga Routes
    Route::middleware('role:warga')->group(function () {
        Route::get('/warga/dashboard', [WargaDashboardController::class, 'index'])->name('warga.dashboard');
        Route::get('/warga/pengajuan/{pengajuan}', [WargaDashboardController::class, 'show'])->name('warga.pengajuan.show');
        Route::post('/warga/pengajuan/{pengajuan}/batal', [WargaDashboardController::class, 'cancel'])->name('warga.pengajuan.cancel');
    });

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });

    Route::prefix('admin')->middleware('role:admin')->group(function () {
        // Admin Pengajuan Routes
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('admin.pengajuan.index');
        Route::get('/pengajuan/{id}', [AdminPengajuanController::class, 'show'])->name('admin.pengajuan.show');
        Route::post('/pengajuan/{id}/approve', [AdminPengajuanController::class, 'approve'])->name('admin.pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [AdminPengajuanController::class, 'reject'])->name('admin.pengajuan.reject');
        Route::post('/pengajuan/{id}/generate', [AdminPengajuanController::class, 'generate'])->name('admin.pengajuan.generate');
        
        // Admin Jenis Surat Routes
        Route::get('/jenis-surat', [JenisSuratController::class, 'adminIndex'])->name('admin.jenis-surat.index');
        Route::post('/jenis-surat', [JenisSuratController::class, 'adminStore'])->name('admin.jenis-surat.store');
        Route::get('/jenis-surat/{jenisSurat}', [JenisSuratController::class, 'adminShow'])->name('admin.jenis-surat.show');
        Route::put('/jenis-surat/{jenisSurat}', [JenisSuratController::class, 'adminUpdate'])->name('admin.jenis-surat.update');
        Route::delete('/jenis-surat/{jenisSurat}', [JenisSuratController::class, 'adminDestroy'])->name('admin.jenis-surat.destroy');
        Route::patch('/jenis-surat/{jenisSurat}/toggle-status', [JenisSuratController::class, 'adminToggleStatus'])->name('admin.jenis-surat.toggle-status');
    });
});

// Testing Route (remove in production)
Route::view('/testing', 'testing.frontend-test')->name('testing');
