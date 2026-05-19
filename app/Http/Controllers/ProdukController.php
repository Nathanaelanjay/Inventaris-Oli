<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\HutangPembelian;
use App\Models\PiutangPelanggan;
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

        // SEARCH
        if ($request->search) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

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

        $produk = $query->paginate(10)->withQueryString();

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
        // STOK MINIMUM
        $produkMinimum = Produk::whereColumn('stok', '<=', 'stok_minimum')->get();

        // HUTANG BELUM LUNAS
        $hutang = HutangPembelian::with([
            'barangMasuk.produk.pemasok'
        ])
            ->where('status', '!=', 'Lunas')
            ->get();

        // PIUTANG BELUM LUNAS
        $piutang = PiutangPelanggan::where('status', '!=', 'Lunas')->get();

        // CEK SEMUA KOSONG
        if (
            $produkMinimum->isEmpty() &&
            $hutang->isEmpty() &&
            $piutang->isEmpty()
        ) {
            return back()->with(
                'success',
                'Tidak ada notifikasi stok, hutang, atau piutang'
            );
        }

        Mail::raw(
            $this->formatEmailLaporan(
                $produkMinimum,
                $hutang,
                $piutang
            ),
            function ($message) {
                $message->to('tritunggalinventarisoli@gmail.com')
                    ->subject('Laporan Inventory, Hutang, dan Piutang');
            }
        );

        return redirect()->back()
            ->with(
                'success',
                'Email laporan berhasil dikirim'
            );
    }

    private function formatEmailLaporan($produk, $hutang, $piutang)
    {
        $text = "LAPORAN INVENTORY TOKO OLI\n";
        $text .= "=============================\n\n";

        /*
        |--------------------------------------------------------------------------
        | STOK MINIMUM
        |--------------------------------------------------------------------------
        */
        $text .= "STOK MINIMUM PRODUK\n";
        $text .= "-----------------------------\n";

        if ($produk->isEmpty()) {
            $text .= "Tidak ada stok minimum\n";
        } else {
            foreach ($produk as $p) {
                $text .=
                    "- {$p->nama_barang} | Stok: {$p->stok} | Minimum: {$p->stok_minimum}\n";
            }
        }

        $text .= "\n\n";

        /*
        |--------------------------------------------------------------------------
        | HUTANG PEMBELIAN
        |--------------------------------------------------------------------------
        */
        $text .= "HUTANG PEMBELIAN\n";
        $text .= "-----------------------------\n";

        if ($hutang->isEmpty()) {
            $text .= "Tidak ada hutang pembelian\n";
        } else {
            foreach ($hutang as $h) {

                $namaPemasok =
                    $h->barangMasuk->produk->pemasok->nama_pemasok ?? '-';

                $jatuhTempo =
                    $h->tanggal_jatuh_tempo
                    ? \Carbon\Carbon::parse($h->tanggal_jatuh_tempo)
                        ->format('d-m-Y')
                    : '-';

                $text .=
                    "- Hutang ke {$namaPemasok} | " .
                    "Jatuh Tempo: {$jatuhTempo} | " .
                    "Sisa: Rp " .
                    number_format($h->sisa_hutang, 0, ',', '.') .
                    "\n";
            }
        }

        $text .= "\n\n";

        /*
        |--------------------------------------------------------------------------
        | PIUTANG PELANGGAN
        |--------------------------------------------------------------------------
        */
        $text .= "PIUTANG PELANGGAN\n";
        $text .= "-----------------------------\n";

        if ($piutang->isEmpty()) {
            $text .= "Tidak ada piutang pelanggan\n";
        } else {
            foreach ($piutang as $p) {
                $text .=
                    "- Piutang ID: {$p->piutang_id} | Sisa: Rp " .
                    number_format($p->sisa_piutang, 0, ',', '.') .
                    " | Status: {$p->status}\n";
            }
        }

        return $text;
    }
}