<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Warga\PengajuanController;
use App\Http\Controllers\Admin\ValidasiController;
use App\Http\Controllers\Sekdes\ProsesSuratController;
use App\Http\Controllers\Kades\TandaTanganController;
use App\Http\Controllers\TrackingController;
// Hapus atau abaikan: use App\Http\resources\views\homeblade;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminPengajuanController;
use App\Http\Controllers\SuratTerbitController;

Route::get('/', function () {
    return view('home');
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
=======
Route::get('pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');


Route::get('/penduduk', function () {
    return 'Halaman data penduduk sedang dalam pengembangan.';
})->name('penduduk');

Route::get('/profil', function () {
    return 'Halaman profil desa sedang dalam pengembangan.';
})->name('profil');

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

// ✅ Tracking publik (tanpa login)
Route::get('tracking/{kode}', [TrackingController::class, 'check']);

Route::view('/', 'home')->name('home');
