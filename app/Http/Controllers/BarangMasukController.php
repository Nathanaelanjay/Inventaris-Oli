<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pemasok;
use App\Models\Pengeluaran;
use App\Models\HutangPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangMasukController extends Controller
{

    public function index(Request $request)
    {
        $query = BarangMasuk::with(['produk.kategori', 'produk.pemasok', 'user', 'hutang']);

        $now = Carbon::now();

        // FILTER
        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->kategori) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }

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

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'status_pembayaran' => 'required',
            'lama_hutang' => 'nullable|numeric|min:1'
        ]);

        DB::transaction(function () use ($request) {

            $total = $request->jumlah * $request->harga_beli;

            // SIMPAN BARANG
            $barang = BarangMasuk::create([
                'produk_id' => $request->produk_id,
                'user_id' => 1,
                'jumlah' => $request->jumlah,
                'harga_beli' => $request->harga_beli,
                'total' => $total,
            ]);

            // UPDATE STOK
            $produk = Produk::findOrFail($request->produk_id);
            $produk->stok += $request->jumlah;
            $produk->save();

            // =========================
            // LOGIC PEMBAYARAN
            // =========================
            if ($request->status_pembayaran == 'lunas') {

                Pengeluaran::create([
                    'barang_masuk_id' => $barang->barang_masuk_id,
                    'jumlah' => $total,
                    'tanggal' => now(),
                    'keterangan' => 'Pembelian ' . $produk->nama_barang,
                ]);

            } else {

                if (!$produk->pemasok_id) {
                    throw new \Exception('Produk belum punya pemasok!');
                }

                $jatuhTempo = now()->addMonths((int) $request->lama_hutang);

                HutangPembelian::create([
                    'barang_masuk_id' => $barang->barang_masuk_id,
                    'pemasok_id' => $produk->pemasok_id,
                    'total_hutang' => $total,
                    'sisa_hutang' => $total,
                    'total_terbayar' => 0,
                    'status' => 'Belum Lunas',
                    'tanggal_jatuh_tempo' => $jatuhTempo
                ]);
            }
        });

        return redirect()->route('barangmasuk.index')
            ->with('success', 'Barang masuk berhasil ditambahkan');
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'status_pembayaran' => 'required',
            'lama_hutang' => 'nullable|numeric|min:1'
        ]);

        DB::transaction(function () use ($request, $id) {

            $barang = BarangMasuk::findOrFail($id);

            // KURANGI STOK LAMA
            $produkLama = Produk::findOrFail($barang->produk_id);
            $produkLama->stok -= $barang->jumlah;
            $produkLama->save();

            $total = $request->jumlah * $request->harga_beli;

            // UPDATE BARANG
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

            // =========================
            // HAPUS RELASI LAMA
            // =========================
            Pengeluaran::where('barang_masuk_id', $barang->barang_masuk_id)->delete();
            HutangPembelian::where('barang_masuk_id', $barang->barang_masuk_id)->delete();

            // =========================
            // LOGIC PEMBAYARAN
            // =========================
            if ($request->status_pembayaran == 'lunas') {

                Pengeluaran::create([
                    'barang_masuk_id' => $barang->barang_masuk_id,
                    'jumlah' => $total,
                    'tanggal' => now(),
                    'keterangan' => 'Update pembelian ' . $produkBaru->nama_barang,
                ]);

            } else {

                $jatuhTempo = now()->addMonths((int) $request->lama_hutang);

                HutangPembelian::create([
                    'barang_masuk_id' => $barang->barang_masuk_id,
                    'pemasok_id' => $produkBaru->pemasok_id,
                    'total_hutang' => $total,
                    'sisa_hutang' => $total,
                    'total_terbayar' => 0,
                    'status' => 'Belum Lunas',
                    'tanggal_jatuh_tempo' => $jatuhTempo
                ]);
            }
        });

        return redirect()->route('barangmasuk.index')
            ->with('success', 'Barang masuk berhasil diupdate');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $barang = BarangMasuk::findOrFail($id);

            // HAPUS RELASI DULU
            Pengeluaran::where('barang_masuk_id', $barang->barang_masuk_id)->delete();
            HutangPembelian::where('barang_masuk_id', $barang->barang_masuk_id)->delete();

            // KURANGI STOK
            $produk = Produk::findOrFail($barang->produk_id);
            $produk->stok -= $barang->jumlah;
            $produk->save();

            // HAPUS DATA
            $barang->delete();
        });

        return redirect()->route('barangmasuk.index')
            ->with('success', 'Barang masuk berhasil dihapus');
    }
}