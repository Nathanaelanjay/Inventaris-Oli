<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Pemasok;
use App\Models\Kategori;
use App\Models\Pemasukan;
use App\Models\PiutangPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'pelanggan_id' => 'nullable',
            'status_pembayaran' => 'required',
            'lama_hutang' => 'nullable|numeric|min:1'
        ]);

        DB::transaction(function () use ($request) {

            $produk = Produk::findOrFail($request->produk_id);

            // CEK STOK
            if ($produk->stok < $request->jumlah) {
                throw new \Exception('Stok tidak cukup!');
            }

            $total = $request->jumlah * $request->harga_jual;

            // SIMPAN BARANG KELUAR
            $barang = BarangKeluar::create([
                'produk_id' => $request->produk_id,
                'user_id' => auth()->id(),
                'pelanggan_id' => $request->pelanggan_id,
                'jumlah' => $request->jumlah,
                'harga_jual' => $request->harga_jual,
                'total' => $total
            ]);

            // KURANGI STOK
            $produk->stok -= $request->jumlah;
            $produk->save();

            if ($request->status_pembayaran == 'lunas') {

                Pemasukan::create([
                    'barang_keluar_id' => $barang->barang_keluar_id,
                    'jumlah' => $total,
                    'tanggal' => now(),
                    'keterangan' => 'Penjualan ' . $produk->nama_barang
                ]);

            } else {

                if (!$request->pelanggan_id) {
                    throw new \Exception('Pelanggan wajib dipilih untuk transaksi hutang!');
                }

                $bulan = (int) ($request->lama_hutang ?? 0);
                $jatuhTempo = now()->addMonths($bulan);

                PiutangPelanggan::create([
                    'barang_keluar_id' => $barang->barang_keluar_id,
                    'pelanggan_id' => $request->pelanggan_id,
                    'total_piutang' => $total,
                    'sisa_piutang' => $total,
                    'total_terbayar' => 0,
                    'status' => 'hutang',
                    'tanggal_jatuh_tempo' => $jatuhTempo
                ]);
            }

        });

        return redirect()->route('barangkeluar.index')
            ->with('success', 'Barang keluar berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'harga_jual' => 'required|numeric|min:0',
            'pelanggan_id' => 'nullable',
            'status_pembayaran' => 'required',
            'lama_hutang' => 'nullable|numeric|min:1'
        ]);

        DB::transaction(function () use ($request, $id) {

            $barang = BarangKeluar::findOrFail($id);

            $produkLama = Produk::findOrFail($barang->produk_id);
            $produkLama->stok += $barang->jumlah;
            $produkLama->save();
            $produkBaru = Produk::findOrFail($request->produk_id);

            if ($produkBaru->stok < $request->jumlah) {
                throw new \Exception('Stok tidak cukup!');
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

            Pemasukan::where('barang_keluar_id', $barang->barang_keluar_id)->delete();
            PiutangPelanggan::where('barang_keluar_id', $barang->barang_keluar_id)->delete();


            if ($request->status_pembayaran == 'lunas') {

                // MASUK KE PEMASUKAN
                Pemasukan::create([
                    'barang_keluar_id' => $barang->barang_keluar_id,
                    'jumlah' => $total,
                    'tanggal' => now(),
                    'keterangan' => 'Update penjualan ' . $produkBaru->nama_barang
                ]);

            } else {

                // VALIDASI WAJIB ADA PELANGGAN
                if (!$request->pelanggan_id) {
                    throw new \Exception('Pelanggan wajib dipilih untuk hutang!');
                }

                // HITUNG JATUH TEMPO (DALAM BULAN)
                $jatuhTempo = now()->addMonths((int) $request->lama_hutang);

                // MASUK KE PIUTANG
                PiutangPelanggan::create([
                    'barang_keluar_id' => $barang->barang_keluar_id,
                    'pelanggan_id' => $request->pelanggan_id,
                    'total_piutang' => $total,
                    'sisa_piutang' => $total,
                    'total_terbayar' => 0,
                    'status' => 'hutang',
                    'tanggal_jatuh_tempo' => $jatuhTempo
                ]);
            }
        });

        return redirect()->route('barangkeluar.index')
            ->with('success', 'Barang keluar berhasil diupdate');
    }
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $barang = BarangKeluar::findOrFail($id);

            // BALIKIN STOK
            $produk = Produk::findOrFail($barang->produk_id);
            $produk->stok += $barang->jumlah;
            $produk->save();

            // HAPUS PEMASUKAN
            Pemasukan::where('barang_keluar_id', $barang->barang_keluar_id)->delete();

            // HAPUS PIUTANG
            PiutangPelanggan::where('barang_keluar_id', $barang->barang_keluar_id)->delete();

            // HAPUS BARANG
            $barang->delete();
        });

        return redirect()->route('barangkeluar.index')
            ->with('success', 'Barang keluar berhasil dihapus');
    }
}