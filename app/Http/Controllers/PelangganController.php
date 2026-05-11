<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();

        return view('pelanggan.index', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bengkel' => 'required',
            'no_telp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        $pelanggan = Pelanggan::create([
            'nama_bengkel' => $request->nama_bengkel,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        LogHelper::simpan(
            'Menambahkan pelanggan: ' . $pelanggan->nama_bengkel,
            'pelanggan',
            $pelanggan->pelanggan_id
        );

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bengkel' => 'required',
            'no_telp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->update([
            'nama_bengkel' => $request->nama_bengkel,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        LogHelper::simpan(
            'Mengupdate pelanggan: ' . $request->nama_bengkel,
            'pelanggan',
            $pelanggan->pelanggan_id
        );

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diupdate');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        LogHelper::simpan(
            'Menghapus pelanggan: ' . $pelanggan->nama_bengkel,
            'pelanggan',
            $pelanggan->pelanggan_id
        );
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil dihapus');
    }
}