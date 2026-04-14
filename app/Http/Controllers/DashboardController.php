<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Produk::count();
        $totalStok = Produk::sum('stok');
        $produkMenipis = Produk::whereColumn('stok', '<=', 'stok_minimum')->count();

        return view('dashboard', compact(
            'totalProduk',
            'totalStok',
            'produkMenipis'
        ));
    }
}