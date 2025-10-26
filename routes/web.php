<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengajuanController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');

Route::get('/penduduk', function () {
    return 'Halaman data penduduk sedang dalam pengembangan.';
})->name('penduduk');

Route::get('/profil', function () {
    return 'Halaman profil desa sedang dalam pengembangan.';
})->name('profil');
