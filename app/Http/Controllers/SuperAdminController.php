<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
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
        // FILTER ADMIN
        if ($request->filled('admin')) {
            $query->where('user_id', $request->admin);
        }

        // PAGINATION LOG
        $logs = $query->latest()->paginate(5);

        // LIST ADMIN
        $admins = User::with('role')
            ->whereHas('role', function ($q) {
                $q->where('nama_role', 'Admin');
            })
            ->latest()
            ->paginate(5, ['*'], 'admins_page');

        return view('dashboardadmin', compact(
            'totalAdmin',
            'totalLog',
            'totalLogAll',
            'logs',
            'admins'
        ));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ], [
            'email.unique' => 'Email sudah terdaftar sebagai admin.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        $roleAdmin = Role::where('nama_role', 'Admin')->first();

        if (!$roleAdmin) {
            return back()->with(
                'error',
                'Role Admin tidak ditemukan'
            );
        }

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

        return back()
            ->withErrors([
                'store_error' => 'Password minimal 6 karakter.'
            ], 'store')
            ->withInput();
    }
    // ========================
    // UPDATE ADMIN
    // ========================
    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validateWithBag('edit', [
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'password' => 'nullable|min:6'
        ], [
            'email.unique' => 'Email sudah terdaftar sebagai admin.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);
        $admin->nama = $request->nama;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        LogAktivitas::create([
            'user_id' => auth()->user()->user_id,
            'aktivitas' => 'Mengupdate admin: ' . $admin->nama
        ]);

        return back()->with(
            'success',
            'Admin berhasil diperbarui'
        );
    }

    // ========================
    // HAPUS ADMIN
    // ========================
    public function destroy($id)
    {
        $admin = User::findOrFail($id);

        $namaAdmin = $admin->nama;

        $admin->delete();

        LogAktivitas::create([
            'user_id' => auth()->user()->user_id,
            'aktivitas' => 'Menghapus admin: ' . $namaAdmin
        ]);

        return back()->with(
            'success',
            'Admin berhasil dihapus'
        );
    }

    // ========================
    // HAPUS LOG LAMA
    // ========================
    public function hapusLogLama(Request $request)
    {
        $hari = $request->hari;

        LogAktivitas::where(
            'created_at',
            '<',
            Carbon::now()->subDays($hari)
        )->delete();

        return back()->with(
            'success',
            "Log lebih dari {$hari} hari berhasil dihapus"
        );
    }
}