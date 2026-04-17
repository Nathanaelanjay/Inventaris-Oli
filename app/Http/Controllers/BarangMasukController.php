<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Produk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{

    public function index()
    {
        $barangMasuk = BarangMasuk::with(['produk', 'user'])->latest()->get();
        $produk = Produk::all();

        return view('barangmasuk.index', compact(
            'barangMasuk',
            'produk'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id'   => 'required',
            'jumlah'      => 'required|numeric|min:1',
            'harga_beli'  => 'required|numeric|min:0',
        ]);

        $total = $request->jumlah * $request->harga_beli;

        BarangMasuk::create([
            'produk_id'   => $request->produk_id,
            'user_id'     => 1, // sementara
            'jumlah'      => $request->jumlah,
            'harga_beli'  => $request->harga_beli,
            'total'       => $total,
        ]);

        // UPDATE STOK PRODUK
        $produk = Produk::findOrFail($request->produk_id);
        $produk->stok += $request->jumlah;
        $produk->save();

        return redirect()->route('barangmasuk.index')
            ->with('success', 'Barang masuk berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id'   => 'required',
            'jumlah'      => 'required|numeric|min:1',
            'harga_beli'  => 'required|numeric|min:0',
        ]);

        $barang = BarangMasuk::findOrFail($id);
        $produkLama = Produk::findOrFail($barang->produk_id);
        $produkLama->stok -= $barang->jumlah;
        $produkLama->save();
        $total = $request->jumlah * $request->harga_beli;
        $barang->update([
            'produk_id'   => $request->produk_id,
            'jumlah'      => $request->jumlah,
            'harga_beli'  => $request->harga_beli,
            'total'       => $total,
        ]);

        $produkBaru = Produk::findOrFail($request->produk_id);
        $produkBaru->stok += $request->jumlah;
        $produkBaru->save();

        return redirect()->route('barangmasuk.index')
            ->with('success', 'Barang masuk berhasil diupdate');
    }

    public function destroy($id)
    {
        $barang = BarangMasuk::findOrFail($id);

        // 🔥 KURANGI STOK
        $produk = Produk::findOrFail($barang->produk_id);
        $produk->stok -= $barang->jumlah;
        $produk->save();

        // 🔥 HAPUS DATA
        $barang->delete();

        return redirect()->route('barangmasuk.index')
            ->with('success', 'Barang masuk berhasil dihapus');
    }
}