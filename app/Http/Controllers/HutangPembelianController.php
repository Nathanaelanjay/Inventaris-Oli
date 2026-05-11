<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HutangPembelian;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;
use App\Helpers\LogHelper;

class HutangPembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = HutangPembelian::with('barangMasuk.produk.pemasok');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $hutang = $query->latest()->paginate(10);

        return view('hutangpembelian.index', compact('hutang'));
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1'
        ]);

        $hutang = HutangPembelian::with('barangMasuk.produk')->findOrFail($id);

        try {

            DB::transaction(function () use ($request, $hutang) {

                $bayar = $request->jumlah_bayar;

                if ($bayar > $hutang->sisa_hutang) {
                    throw new \Exception('Jumlah bayar melebihi sisa hutang!');
                }

                $hutang->sisa_hutang -= $bayar;
                $hutang->total_terbayar += $bayar;
                $hutang->tanggal_bayar_terakhir = now();

                if ($hutang->sisa_hutang == 0) {
                    $hutang->status = 'lunas';
                } else {
                    $hutang->status = 'sebagian';
                }

                $hutang->save();

                Pengeluaran::create([
                    'jumlah' => $bayar,
                    'tanggal' => now(),
                    'keterangan' => 'Pembayaran Hutang Pembelian',
                    'barang_masuk_id' => $hutang->barang_masuk_id
                ]);

                LogHelper::simpan(
                    'Membayar hutang pembelian produk '
                    . $hutang->barangMasuk->produk->nama_barang .
                    ' sebesar Rp ' .
                    number_format($bayar, 0, ',', '.'),
                    'hutang_pembelian',
                    $hutang->hutang_id
                );
            });

            return back()->with('success', 'Pembayaran hutang berhasil!');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $hutang = HutangPembelian::findOrFail($id);
        LogHelper::simpan(
            'Menghapus hutang pembelian produk: '
            . $hutang->barangMasuk->produk->nama_barang,
            'hutang_pembelian',
            $hutang->hutang_id
        );
        $hutang->delete();

        return back()->with('success', 'Data hutang dihapus');
    }
}