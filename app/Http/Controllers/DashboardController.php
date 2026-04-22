<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\PiutangPelanggan;
use App\Models\HutangPembelian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $totalProduk = Produk::count();
        $totalStok = Produk::sum('stok');
        $produkMenipis = Produk::whereColumn('stok', '<=', 'stok_minimum')->count();

        $pengeluaran = BarangMasuk::whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('total');

        $pemasukan = BarangKeluar::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total');

        $keuntungan_bersih = $pemasukan - $pengeluaran;

        $totalPiutang = PiutangPelanggan::sum('sisa_piutang');

        $totalHutang = HutangPembelian::sum('sisa_hutang');

        return view('dashboard', compact(
            'totalProduk',
            'totalStok',
            'produkMenipis',
            'pengeluaran',
            'pemasukan',
            'keuntungan_bersih',
            'totalPiutang',
            'totalHutang'
        ));
    }
}