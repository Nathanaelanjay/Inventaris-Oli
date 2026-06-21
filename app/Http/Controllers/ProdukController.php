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

        Mail::html(
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
        $html = '
    <div style="font-family: Arial, sans-serif; padding:20px; color:#333;">
        
        <h2 style="color:#2563eb;">
            Laporan Inventory Oli Tritunggal
        </h2>

        <p style="margin-bottom:30px;">
            Berikut adalah laporan stok minimum, hutang pembelian,
            dan piutang pelanggan terbaru.
        </p>
    ';
        $tableStyle = '
    border-collapse: collapse;
    width: auto;
    min-width: 500px;
    font-size: 13px;
    margin-bottom: 25px;
';

        $thStyle = '
    padding: 6px 10px;
    background: #f8fafc;
    font-weight: 600;
';

        $tdStyle = '
    padding: 6px 10px;
';

        /*
        |--------------------------------------------------------------------------
        | STOK MINIMUM
        |--------------------------------------------------------------------------
        */
        $html .= '
        <table
            border="1"
            cellpadding="0"
            cellspacing="0"
            style="' . $tableStyle . '"
        >
            <thead>
                <tr>
                    <th style="' . $thStyle . '">Nama Produk</th>
                    <th style="' . $thStyle . '">Stok</th>
                    <th style="' . $thStyle . '">Min</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($produk as $p) {

            $html .= '
            <tr>
                <td style="' . $tdStyle . '">' . $p->nama_barang . '</td>

                <td align="center" style="' . $tdStyle . '">
                    ' . $p->stok . '
                </td>

                <td align="center" style="' . $tdStyle . '">
                    ' . $p->stok_minimum . '
                </td>
            </tr>
            ';
        }

        $html .= '
            </tbody>
        </table>
        ';

        /*
        |--------------------------------------------------------------------------
        | HUTANG PEMBELIAN
        |--------------------------------------------------------------------------
        */
        $html .= '
        <h3 style="color:#ea580c;">
            Hutang Pembelian
        </h3>
    ';

        if ($hutang->isEmpty()) {

            $html .= '
            <p>Tidak ada hutang pembelian.</p>
        ';

        } else {

            $html .= '
        <table 
            border="1" 
            cellpadding="0" 
            cellspacing="0" 
            style="' . $tableStyle . '"
        >
            <thead style="background:#f3f4f6;">
                <tr>
                    <th>Pemasok</th>
                    <th>Jatuh Tempo</th>
                    <th>Sisa Hutang</th>
                </tr>
            </thead>
            <tbody>
        ';

            foreach ($hutang as $h) {

                $namaPemasok =
                    $h->barangMasuk->produk->pemasok->nama_pemasok ?? '-';

                $jatuhTempo =
                    $h->tanggal_jatuh_tempo
                    ? \Carbon\Carbon::parse($h->tanggal_jatuh_tempo)
                        ->format('d-m-Y')
                    : '-';

                $html .= '
                <tr>
                    <td>' . $namaPemasok . '</td>
                    <td align="center">' . $jatuhTempo . '</td>
                    <td align="right">
                        Rp ' . number_format($h->sisa_hutang, 0, ',', '.') . '
                    </td>
                </tr>
            ';
            }

            $html .= '
            </tbody>
        </table>
        ';
        }

        /*
 |--------------------------------------------------------------------------
 | PIUTANG PELANGGAN
 |--------------------------------------------------------------------------
 */
        $html .= '
    <h3 style="color:#16a34a;">
        Piutang Pelanggan
    </h3>
';

        if ($piutang->isEmpty()) {

            $html .= '
        <p>Tidak ada piutang pelanggan.</p>
    ';

        } else {

            $html .= '
    <table 
        border="1" 
        cellpadding="0" 
        cellspacing="0" 
        style="' . $tableStyle . '"
    >
        <thead style="background:#f3f4f6;">
            <tr>
                <th>Nama Bengkel</th>
                <th>Jatuh Tempo</th>
                <th>Sisa Piutang</th>
            </tr>
        </thead>
        <tbody>
    ';

            foreach ($piutang as $p) {

                $namaBengkel =
                    $p->barangKeluar->pelanggan->nama_bengkel ?? '-';

                $jatuhTempo =
                    $p->tanggal_jatuh_tempo
                    ? \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)
                        ->format('d-m-Y')
                    : '-';

                $html .= '
            <tr>
                <td>' . $namaBengkel . '</td>

                <td align="center">
                    ' . $jatuhTempo . '
                </td>

                <td align="right">
                    Rp ' . number_format($p->sisa_piutang, 0, ',', '.') . '
                </td>
            </tr>
        ';
            }

            $html .= '
        </tbody>
    </table>
    ';
        }

        $html .= '
    <p style="margin-top:40px; font-size:12px; color:#888;">
        Email otomatis dari sistem Inventory Oli
    </p>
</div>
';
        return $html;
    }
}