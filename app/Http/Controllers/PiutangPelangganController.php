<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PiutangPelanggan;
use App\Models\Pemasukan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PiutangPelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = PiutangPelanggan::with('barangKeluar.pelanggan');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $piutang = $query->latest()->get();

        return view('piutangpelanggan.index', compact('piutang'));
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1'
        ]);

        $piutang = PiutangPelanggan::findOrFail($id);

        try {

            DB::transaction(function () use ($request, $piutang) {

                $bayar = $request->jumlah_bayar;

                if ($bayar > $piutang->sisa_piutang) {
                    throw new \Exception('Jumlah bayar melebihi sisa piutang!');
                }

                // UPDATE PIUTANG
                $piutang->sisa_piutang -= $bayar;
                $piutang->total_terbayar += $bayar;
                $piutang->tanggal_bayar_terakhir = now();

                if ($piutang->sisa_piutang == 0) {
                    $piutang->status = 'lunas';
                } else {
                    $piutang->status = 'sebagian';
                }

                $piutang->save();

                // INSERT PEMASUKAN
                Pemasukan::create([
                    'jumlah' => $bayar,
                    'tanggal' => now(),
                    'keterangan' => 'Pembayaran Piutang Pelanggan',
                    'barang_keluar_id' => $piutang->barang_keluar_id
                ]);
            });

            return back()->with('success', 'Pembayaran berhasil!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $piutang = PiutangPelanggan::findOrFail($id);
        $piutang->delete();

        return redirect()->back()->with('success', 'Data piutang dihapus');
    }

}