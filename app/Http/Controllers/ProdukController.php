<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with(['kategori', 'pemasok'])->get();
        $kategori = Kategori::all();
        $pemasok = Pemasok::all();

        return view('produk.index', compact(
            'produk',
            'kategori',
            'pemasok'
        ));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $pemasok = Pemasok::all();

        return view('produk.create', compact('kategori', 'pemasok'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'stok' => 'required|numeric',
            'stok_minimum' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kategori_id' => 'required',
            'pemasok_id' => 'required',
        ]);

        Produk::create($request->all());

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::all();
        $pemasok = Pemasok::all();

        return view('produk.edit', compact(
            'produk',
            'kategori',
            'pemasok'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'stok' => 'required|numeric',
            'stok_minimum' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kategori_id' => 'required',
            'pemasok_id' => 'required',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->all());

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id)
    {
        Produk::destroy($id);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}