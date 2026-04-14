<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Produk;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $data = BarangKeluar::with(['produk', 'user', 'pelanggan'])->get();
        return view('barang_keluar.index', compact('data'));
    }

    public function create()
    {
        $produk = Produk::all();
        $pelanggan = Pelanggan::all();
        return view('barang_keluar.create', compact('produk', 'pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
            'jumlah' => 'required|numeric',
            'harga_jual' => 'required|numeric'
        ]);

        $produk = Produk::find($request->produk_id);

        // ❗ CEK STOK
        if ($produk->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak cukup!');
        }

        $total = $request->jumlah * $request->harga_jual;

        BarangKeluar::create([
            'produk_id' => $request->produk_id,
            'user_id' => 1,
            'pelanggan_id' => $request->pelanggan_id,
            'jumlah' => $request->jumlah,
            'harga_jual' => $request->harga_jual,
            'total' => $total
        ]);

        // 🔥 KURANGI STOK
        $produk->stok -= $request->jumlah;
        $produk->save();

        return redirect()->route('barang-keluar.index');
    }
}