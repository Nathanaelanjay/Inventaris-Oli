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
    public function index(Request $request)
    {
        // TOTAL ADMIN
        $totalAdmin = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'Admin');
        })->count();

        // TOTAL LOG HARI INI
        $totalLog = LogAktivitas::whereDate('created_at', today())
            ->count();

        // TOTAL SEMUA LOG
        $totalLogAll = LogAktivitas::count();

        // QUERY LOG
        $query = LogAktivitas::with('user');

        // FILTER TANGGAL
        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // FILTER AKTIVITAS
        if ($request->aktivitas) {
            $query->where(
                'aktivitas',
                'like',
                '%' . $request->aktivitas . '%'
            );
        }

        // PAGINATION
        $logs = $query->latest()->paginate(5);

        return view('dashboardadmin', compact(
            'totalAdmin',
            'totalLog',
            'totalLogAll',
            'logs'
        ));
    }

    // ========================
    // TAMBAH ADMIN
    // ========================
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $roleAdmin = Role::where('nama_role', 'Admin')->first();

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleAdmin->role_id
        ]);

        LogAktivitas::create([
            'user_id' => auth()->user()->user_id,
            'aktivitas' => 'Menambahkan admin baru: ' . $user->nama
        ]);

        return redirect()->route('dashboard.admin')
            ->with('success', 'Admin berhasil ditambahkan');
    }
}