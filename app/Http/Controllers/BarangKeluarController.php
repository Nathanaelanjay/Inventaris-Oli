<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Pemasok;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangKeluar::with([
            'produk.kategori',
            'produk.pemasok',
            'pelanggan',
            'user'
        ]);

        $now = Carbon::now();

        $pemasukan = BarangKeluar::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
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

        //  FILTER PELANGGAN
        if ($request->pelanggan) {
            $query->where('pelanggan_id', $request->pelanggan);
        }

        // FILTER PEMASOK
        if ($request->pemasok) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('pemasok_id', $request->pemasok);
            });
        }

        $barangKeluar = $query->latest()->get();

        $produk = Produk::all();
        $pelanggan = Pelanggan::all();
        $kategori = Kategori::all();
        $pemasok = Pemasok::all();

        return view('barangkeluar.index', compact(
            'barangKeluar',
            'produk',
            'pelanggan',
            'kategori',
            'pemasok',
            'pemasukan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'harga_jual' => 'required|numeric|min:0',
            'pelanggan_id' => 'nullable'
        ]);

        $produk = Produk::findOrFail($request->produk_id);

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

        $produk->stok -= $request->jumlah;
        $produk->save();

        return redirect()->route('barangkeluar.index')
            ->with('success', 'Barang keluar berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'harga_jual' => 'required|numeric|min:0',
            'pelanggan_id' => 'nullable'
        ]);

        $barang = BarangKeluar::findOrFail($id);
        $produkLama = Produk::findOrFail($barang->produk_id);
        $produkLama->stok += $barang->jumlah;
        $produkLama->save();
        $produkBaru = Produk::findOrFail($request->produk_id);

        if ($produkBaru->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak cukup!');
        }

        $total = $request->jumlah * $request->harga_jual;

        $barang->update([
            'produk_id' => $request->produk_id,
            'pelanggan_id' => $request->pelanggan_id,
            'jumlah' => $request->jumlah,
            'harga_jual' => $request->harga_jual,
            'total' => $total
        ]);

        $produkBaru->stok -= $request->jumlah;
        $produkBaru->save();

        return redirect()->route('barangkeluar.index')
            ->with('success', 'Barang keluar berhasil diupdate');
    }

    public function destroy($id)
    {
        $barang = BarangKeluar::findOrFail($id);
        $produk = Produk::findOrFail($barang->produk_id);
        $produk->stok += $barang->jumlah;
        $produk->save();

        $barang->delete();

        return redirect()->route('barangkeluar.index')
            ->with('success', 'Barang keluar berhasil dihapus');
    }
}