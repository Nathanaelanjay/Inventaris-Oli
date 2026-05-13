<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Mail;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['kategori', 'pemasok']);

        // FILTER KATEGORI
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        // FILTER PEMASOK
        if ($request->pemasok) {
            $query->where('pemasok_id', $request->pemasok);
        }

        // SORTING STOK
        if ($request->stok) {
            $query->orderBy('stok', $request->stok);
        }

        $produk = $query->paginate(10);

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
            'nama_barang' => 'required',
            'stok' => 'required|numeric',
            'stok_minimum' => 'required|numeric',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'kategori_id' => 'required',
            'pemasok_id' => 'required',
        ]);

        $harga_beli = str_replace(['Rp', '.', ' '], '', $request->harga_beli);
        $harga_jual = str_replace(['Rp', '.', ' '], '', $request->harga_jual);

        $produk = Produk::create([
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'stok_minimum' => $request->stok_minimum,
            'harga_beli' => $harga_beli,
            'harga_jual' => $harga_jual,
            'kategori_id' => $request->kategori_id,
            'pemasok_id' => $request->pemasok_id,
        ]);

        LogHelper::simpan(
            'Menambahkan produk: ' . $produk->nama_barang,
            'produk',
            $produk->produk_id
        );

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
            'nama_barang' => 'required',
            'stok' => 'required|numeric',
            'stok_minimum' => 'required|numeric',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'kategori_id' => 'required',
            'pemasok_id' => 'required',
        ]);

        $produk = Produk::findOrFail($id);

        $harga_beli = str_replace(['Rp', '.', ' '], '', $request->harga_beli);
        $harga_jual = str_replace(['Rp', '.', ' '], '', $request->harga_jual);

        $produk->update([
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'stok_minimum' => $request->stok_minimum,
            'harga_beli' => $harga_beli,
            'harga_jual' => $harga_jual,
            'kategori_id' => $request->kategori_id,
            'pemasok_id' => $request->pemasok_id,
        ]);

        LogHelper::simpan(
            'Mengupdate produk: ' . $request->nama_barang,
            'produk',
            $produk->produk_id
        );

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        LogHelper::simpan(
            'Menghapus produk: ' . $produk->nama_barang,
            'produk',
            $produk->produk_id
        );
        $produk->delete();


        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    public function kirimEmailStok()
    {
        $produkMinimum = Produk::whereColumn('stok', '<=', 'stok_minimum')->get();

        if ($produkMinimum->isEmpty()) {
            return back()->with('success', 'Tidak ada stok minimum');
        }

        Mail::raw(
            $this->formatEmailStok($produkMinimum),
            function ($message) {
                $message->to('tritunggalinventarisoli@gmail.com')
                    ->subject('Peringatan Stok Minimum Produk');
            }
        );

        return redirect()->back()
        ->with('success', 'Email stok minimum berhasil dikirim');
    }
    private function formatEmailStok($produk)
    {
        $text = "Daftar Produk Stok Minimum\n\n";

        foreach ($produk as $p) {
            $text .=
                "- {$p->nama_barang} | Stok: {$p->stok} | Minimum: {$p->stok_minimum}\n";
        }

        return $text;
    }
}