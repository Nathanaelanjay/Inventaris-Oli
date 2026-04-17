<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    // =========================
    // INDEX (READ)
    // =========================
    public function index()
    {
        $pemasok = Pemasok::all();
        return view('pemasok.index', compact('pemasok'));
    }

    // =========================
    // STORE (CREATE)
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'kontak' => 'required|string|max:20', // 🔥 FIX
            'alamat' => 'nullable|string',
        ]);

        Pemasok::create([
            'nama_pemasok' => $request->nama_pemasok,
            'kontak' => $request->kontak, // 🔥 FIX
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Pemasok berhasil ditambahkan');
    }

    // =========================
    // UPDATE (EDIT)
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'kontak' => 'required|string|max:20', // 🔥 FIX
            'alamat' => 'nullable|string',
        ]);

        $pemasok = Pemasok::findOrFail($id);

        $pemasok->update([
            'nama_pemasok' => $request->nama_pemasok,
            'kontak' => $request->kontak, // 🔥 FIX
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Pemasok berhasil diupdate');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $pemasok = Pemasok::findOrFail($id);
        $pemasok->delete();

        return redirect()->back()->with('success', 'Pemasok berhasil dihapus');
    }
}