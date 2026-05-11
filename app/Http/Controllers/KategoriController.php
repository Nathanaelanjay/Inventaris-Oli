<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()->get();
        return view('kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|max:255'
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        LogHelper::simpan(
            'Menambahkan kategori: ' . $kategori->nama_kategori,
            'kategori',
            $kategori->kategori_id
        );

        return back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|max:255'
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        LogHelper::simpan(
            'Mengupdate kategori: ' . $request->nama_kategori,
            'kategori',
            $kategori->kategori_id
        );

        return back()->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        LogHelper::simpan(
            'Menghapus kategori: ' . $kategori->nama_kategori,
            'kategori',
            $kategori->kategori_id
        );
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus');
    }
}