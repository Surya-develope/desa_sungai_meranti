<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/surat', function(){
    return view('surat');
})->name('surat');

Route::get('/penduduk', function(){
    return view('penduduk');
})->name('penduduk');

Route::get('/profil', function(){
    return view('profil');
})->name('profil');