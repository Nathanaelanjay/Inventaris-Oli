<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // ========================
    // DASHBOARD SUPER ADMIN
    // ========================
    public function index()
    {
        // Hitung total admin
        $totalAdmin = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'Admin');
        })->count();

        // Hitung aktivitas hari ini
        $totalLog = LogAktivitas
    ::whereDate('created_at', today())->count();

        // Ambil 10 log terbaru
        $logs = LogAktivitas
    ::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboardadmin', compact(
            'totalAdmin',
            'totalLog',
            'logs'
        ));
    }

    // ========================
    // TAMBAH ADMIN
    // ========================
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        // Ambil role Admin
        $roleAdmin = Role::where('nama_role', 'Admin')->first();

        // Simpan user admin baru
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleAdmin->role_id
        ]);

        // Simpan log aktivitas
        LogActivity::create([
            'user_id' => auth()->user()->user_id,
            'aktivitas' => 'Menambahkan admin baru: ' . $user->nama
        ]);

        return back()->with('success', 'Admin berhasil ditambahkan');
    }
}