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
use App\Http\Controllers\PiutangPelangganController;
use App\Http\Controllers\HutangPembelianController;
use App\Http\Controllers\SuperAdminController;

Route::get('/', [LoginController::class, 'showLogin']);

Route::resource('produk', ProdukController::class);
Route::resource('barang-masuk', BarangMasukController::class);
Route::resource('barang-keluar', BarangKeluarController::class);
Route::resource('kategori', KategoriController::class);
Route::resource('pemasok', PemasokController::class);
Route::resource('pelanggan', PelangganController::class);
Route::resource('piutang-pelanggan', PiutangPelangganController::class);
Route::resource('hutang-pembelian', HutangPembelianController::class);

// LOGIN
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// REGISTER
Route::get('/register', [RegisterController::class, 'showRegister']);
Route::post('/register', [RegisterController::class, 'register']);

// PROTECTED
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboardadmin', [SuperAdminController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard.admin');

});

// KATEGORI
Route::resource('kategori', KategoriController::class);

// PEMASOK
Route::resource('pemasok', PemasokController::class);

// PELANGGAN
Route::resource('pelanggan', PelangganController::class);

// BARANG MASUK
Route::resource('barangmasuk', BarangMasukController::class);

// BARANG KELUAR
Route::resource('barangkeluar', BarangKeluarController::class);

// PIUTANG PELANGGAN
Route::resource('piutang-pelanggan', PiutangPelangganController::class);
Route::get('/piutang-pelanggan', [PiutangPelangganController::class, 'index']);

Route::post('/piutang-pelanggan/bayar/{id}', [PiutangPelangganController::class, 'bayar'])
    ->name('piutang.bayar');

Route::delete('/piutang-pelanggan/{id}', [PiutangPelangganController::class, 'destroy']);

// HUTANG PEMBELIAN
Route::resource('hutang-pembelian', HutangPembelianController::class);
Route::get('/hutang-pembelian', [HutangPembelianController::class, 'index'])
    ->name('hutang.index');

Route::post('/hutang-pembelian/bayar/{id}', [HutangPembelianController::class, 'bayar'])
    ->name('hutang.bayar');

Route::delete('/hutang-pembelian/{id}', [HutangPembelianController::class, 'destroy'])
    ->name('hutang.destroy');


// SUPER ADMIN
Route::get('/superadmin', [SuperAdminController::class, 'index'])
    ->middleware('auth');
Route::post('/admin/store', [SuperAdminController::class, 'store'])->name('admin.store');