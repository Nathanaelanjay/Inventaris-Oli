<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PelangganController;

Route::get('/', [LoginController::class, 'showLogin']);

Route::resource('produk', ProdukController::class);
Route::resource('barang-masuk', BarangMasukController::class);
Route::resource('barang-keluar', BarangKeluarController::class);

Route::resource('kategori', KategoriController::class);
Route::resource('pemasok', PemasokController::class);
Route::resource('pelanggan', PelangganController::class);

// LOGIN
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// REGISTER
Route::get('/register', [RegisterController::class, 'showRegister']);
Route::post('/register', [RegisterController::class, 'register']);

// PROTECTED
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// KATEGORI
Route::resource('kategori', KategoriController::class);

// PEMASOK
Route::resource('pemasok', PemasokController::class);

// BARANG MASUK
Route::resource('barangmasuk', BarangMasukController::class);

