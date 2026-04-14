<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Produk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $data = BarangMasuk::with(['produk', 'user'])->get();
        return view('barang_masuk.index', compact('data'));
    }

    public function create()
    {
        $produk = Produk::all();
        return view('barang_masuk.create', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
            'jumlah' => 'required|numeric',
            'harga_beli' => 'required|numeric'
        ]);

        $total = $request->jumlah * $request->harga_beli;

        BarangMasuk::create([
            'produk_id' => $request->produk_id,
            'user_id' => 1, // sementara (nanti pakai auth)
            'jumlah' => $request->jumlah,
            'harga_beli' => $request->harga_beli,
            'total' => $total
        ]);

        // 🔥 UPDATE STOK
        $produk = Produk::find($request->produk_id);
        $produk->stok += $request->jumlah;
        $produk->save();

        return redirect()->route('barang-masuk.index');
    }
}