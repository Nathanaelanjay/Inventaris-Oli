<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HutangPembelian;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;

class HutangPembelianController extends Controller
{
    public function index()
    {
        $hutang = HutangPembelian::with('barangMasuk.produk.pemasok')->get();

        return view('hutangpembelian.index', compact('hutang'));
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1'
        ]);

        $hutang = HutangPembelian::findOrFail($id);

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
            });

            return back()->with('success', 'Pembayaran hutang berhasil!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        HutangPembelian::findOrFail($id)->delete();

        return back()->with('success', 'Data hutang dihapus');
    }
}