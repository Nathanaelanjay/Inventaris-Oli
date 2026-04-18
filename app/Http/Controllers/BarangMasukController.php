<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BarangMasukController extends Controller
{

    public function index(Request $request)
    {
        $query = BarangMasuk::with(['produk.kategori', 'produk.pemasok', 'user']);
        $now = Carbon::now();

        $pengeluaran = BarangMasuk::whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('total');

        // FILTER TANGGAL
        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        // FILTER KATEGORI
        if ($request->kategori) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }

        // FILTER PEMASOK
        if ($request->pemasok) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('pemasok_id', $request->pemasok);
            });
        }

        $barangMasuk = $query->latest()->get();

        $produk = Produk::all();
        $kategori = Kategori::all();
        $pemasok = Pemasok::all();

        return view('barangmasuk.index', compact(
            'barangMasuk',
            'produk',
            'kategori',
            'pemasok'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'harga_beli' => 'required|numeric|min:0',
        ]);

        $total = $request->jumlah * $request->harga_beli;

        BarangMasuk::create([
            'produk_id' => $request->produk_id,
            'user_id' => 1,
            'jumlah' => $request->jumlah,
            'harga_beli' => $request->harga_beli,
            'total' => $total,
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
            'produk_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'harga_beli' => 'required|numeric|min:0',
        ]);

        $barang = BarangMasuk::findOrFail($id);

        // KURANGI STOK LAMA
        $produkLama = Produk::findOrFail($barang->produk_id);
        $produkLama->stok -= $barang->jumlah;
        $produkLama->save();

        $total = $request->jumlah * $request->harga_beli;

        $barang->update([
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'harga_beli' => $request->harga_beli,
            'total' => $total,
        ]);

        // TAMBAH STOK BARU
        $produkBaru = Produk::findOrFail($request->produk_id);
        $produkBaru->stok += $request->jumlah;
        $produkBaru->save();

        return redirect()->route('barangmasuk.index')
            ->with('success', 'Barang masuk berhasil diupdate');
    }

    public function destroy($id)
    {
        $barang = BarangMasuk::findOrFail($id);

        // KURANGI STOK
        $produk = Produk::findOrFail($barang->produk_id);
        $produk->stok -= $barang->jumlah;
        $produk->save();

        // HAPUS DATA
        $barang->delete();

        return redirect()->route('barangmasuk.index')
            ->with('success', 'Barang masuk berhasil dihapus');
    }
}