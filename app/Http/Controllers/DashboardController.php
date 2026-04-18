<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Produk;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Produk::count();
        $totalStok = Produk::sum('stok');
        $produkMenipis = Produk::whereColumn('stok', '<=', 'stok_minimum')->count();
        $now = Carbon::now();

        $pengeluaran = BarangMasuk::whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('total');

        $pemasukan = BarangKeluar::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total');

        $keuntungan_bersih = $pemasukan - $pengeluaran;

        return view('dashboard', compact(
            'totalProduk',
            'totalStok',
            'produkMenipis',
            'pengeluaran',
            'pemasukan',
            'keuntungan_bersih'
        ));
    }
}