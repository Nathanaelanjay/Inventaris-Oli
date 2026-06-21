<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin</title>

    @vite('resources/css/app.css')

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-slate-50" style="font-family: 'Inter', sans-serif;">

    @include('layouts.sidebaradmin')

    <main class="ml-64 min-h-screen">

        <!-- HEADER -->
        <div class="bg-white border-b border-slate-100 px-8 py-4 flex items-center justify-between sticky top-0 z-20"
            style="box-shadow: 0 1px 12px rgba(0,0,0,0.04);">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Dashboard Super Admin</h1>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ auth()->user()->nama }}
                    &bull; {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>


        <div class="p-8 space-y-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div
                    class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                    <i class="fas fa-circle-check text-emerald-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 rounded-lg bg-red-100 border border-red-300 text-red-700">
                    {{ session('error') }}
                </div>
            @endif
            <!-- HIGHLIGHT CARDS -->
            <div class="grid grid-cols-2 gap-5">

                <!-- ADMIN CARD -->
                <div
                    class="relative bg-gradient-to-br from-blue-600 to-blue-700 p-6 rounded-2xl text-white overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full"></div>
                    <div class="absolute -right-2 bottom-4 w-16 h-16 bg-white/5 rounded-full"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                                <i class="fas fa-users-gear text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-semibold text-blue-100">Manajemen Admin</span>
                        </div>
                        <p class="text-blue-100 text-sm">Total Admin Terdaftar</p>
                        <h2 class="text-4xl font-extrabold mt-1">{{ $totalAdmin ?? 0 }}</h2>
                        <p class="text-blue-200 text-xs mt-2">Kelola semua akun admin sistem</p>
                    </div>
                </div>

                <!-- ACTIVITY CARD -->
                <div
                    class="relative bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-2xl text-white overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full"></div>
                    <div class="absolute -right-2 bottom-4 w-16 h-16 bg-white/5 rounded-full"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                                <i class="fas fa-chart-line text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-semibold text-emerald-100">Aktivitas Sistem</span>
                        </div>
                        <p class="text-emerald-100 text-sm">Log Aktivitas Hari Ini</p>
                        <h2 class="text-4xl font-extrabold mt-1">{{ $totalLog ?? 0 }}</h2>
                        <p class="text-emerald-200 text-xs mt-2">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>

            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

                <!-- HEADER -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="fas fa-users-gear text-blue-500 text-xs"></i>
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-800 text-sm">
                                Manajemen Admin
                            </h2>

                            <p class="text-xs text-slate-400">
                                Tambah, edit, dan hapus akun admin
                            </p>
                        </div>
                    </div>

                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold">
                        {{ $admins->count() }} Admin
                    </span>

                </div>

                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">

                    <div>
                        <h2 class="font-semibold text-slate-800 text-sm">
                            Manajemen Admin
                        </h2>

                        <p class="text-xs text-slate-400">
                            Kelola akun admin sistem
                        </p>
                    </div>

                    <button onclick="openModal('createAdminModal')"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium">

                        <i class="fas fa-plus"></i>
                        Tambah Admin

                    </button>

                </div>

                <!-- LIST ADMIN -->
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50">

                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Nama</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($admins as $admin)

                                <tr class="border-t border-slate-100">

                                    <td class="px-6 py-4">
                                        {{ $admins->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $admin->nama }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $admin->email }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">

                                            <!-- EDIT -->
                                            <button
                                                onclick="openEditAdmin(
                                                                                                                                    '{{ $admin->user_id }}',
                                                                                                                                    '{{ $admin->nama }}',
                                                                                                                                    '{{ $admin->email }}'
                                                                                                                                )"
                                                class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg border border-amber-100">

                                                <i class="fas fa-pen text-[10px]"></i>
                                                Edit

                                            </button>

                                            <!-- DELETE -->
                                            <form action="{{ route('admin.destroy', $admin->user_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" onclick="return confirm('Yakin hapus admin ini?')"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg border border-red-100">

                                                    <i class="fas fa-trash text-[10px]"></i>
                                                    Hapus

                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $admins->withQueryString()->links() }}
                    </div>
                </div>

            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

                <!-- HEADER -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                            <i class="fas fa-clock text-orange-500 text-xs"></i>
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-800 text-sm">
                                Log Aktivitas Admin
                            </h2>

                            <p class="text-xs text-slate-400">
                                Riwayat aktivitas sistem
                            </p>
                        </div>
                    </div>

                    <span
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 bg-orange-50 px-3 py-1 rounded-full border border-orange-100">

                        <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>

                        {{ $logs->total() }} aktivitas
                    </span>
                </div>

                <form method="GET" class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                        <!-- FILTER TANGGAL -->
                        <div>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm
                       focus:outline-none focus:ring-2 focus:ring-orange-500
                       focus:border-transparent transition">
                        </div>

                        <!-- FILTER AKTIVITAS -->
                        <div>
                            <select name="aktivitas" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm
                       focus:outline-none focus:ring-2 focus:ring-orange-500
                       focus:border-transparent transition">

                                <option value="">Semua Aktivitas</option>

                                <option value="Menambahkan" {{ request('aktivitas') == 'Menambahkan' ? 'selected' : '' }}>
                                    Menambahkan
                                </option>

                                <option value="Mengupdate" {{ request('aktivitas') == 'Mengupdate' ? 'selected' : '' }}>
                                    Mengupdate
                                </option>

                                <option value="Menghapus" {{ request('aktivitas') == 'Menghapus' ? 'selected' : '' }}>
                                    Menghapus
                                </option>

                                <option value="Membayar" {{ request('aktivitas') == 'Membayar' ? 'selected' : '' }}>
                                    Membayar
                                </option>

                            </select>
                        </div>

                        <div>
                            <select name="admin"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm
                                focus:outline-none focus:ring-2 focus:ring-orange-500
                                focus:border-transparent transition">

                                <option value="">Semua Admin</option>

                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->user_id }}"
                                        {{ request('admin') == $admin->user_id ? 'selected' : '' }}>
                                        {{ $admin->nama }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- BUTTON --}}
                        <div class="flex gap-3 w-full md:w-auto md:ml-auto">
                            <!-- Filter -->
                            <button type="submit"
                                class="w-full md:w-40 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl px-4 py-2 text-sm transition">
                                Filter
                            </button>

                            <!-- Reset -->
                            <a href="{{ url()->current() }}"
                                class="w-full md:w-40 text-center bg-slate-200 hover:bg-slate-300 rounded-xl px-4 py-2 text-sm transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- AUTO DELETE LOG -->
                <div class="px-6 py-4 border-b border-slate-100 bg-red-50/40">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                        <div>
                            <h3 class="text-sm font-semibold text-slate-700">
                                Hapus Otomatis Log Lama
                            </h3>

                            <p class="text-xs text-slate-400 mt-0.5">
                                Bersihkan log aktivitas yang sudah terlalu lama
                            </p>
                        </div>

                        <form action="{{ route('log.hapus.lama') }}" method="POST"
                            class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">

                            @csrf
                            @method('DELETE')

                            <select name="hari" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm
                focus:outline-none focus:ring-2 focus:ring-red-500">

                                <option value="10">Hapus log lebih dari 10 hari</option>
                                <option value="30">Hapus log lebih dari 30 hari</option>
                                <option value="60">Hapus log lebih dari 60 hari</option>
                                <option value="90">Hapus log lebih dari 90 hari</option>

                            </select>

                            <button type="submit" onclick="return confirm('Yakin ingin menghapus log lama?')"
                                class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">

                                <i class="fas fa-trash mr-1"></i>
                                Hapus Log

                            </button>

                        </form>

                    </div>

                </div>

                <!-- TABLE -->
                <div class="overflow-x-auto max-h-[500px] overflow-y-auto"></div>
                <!-- TABLE -->
                <div class="overflow-x-auto max-h-[500px] overflow-y-auto">

                    <table class="w-full text-sm">

                        <!-- HEAD -->
                        <thead class="bg-slate-50 sticky top-0 z-10">

                            <tr>

                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">
                                    #
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">
                                    Admin
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">
                                    Aktivitas
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">
                                    Waktu
                                </th>

                            </tr>

                        </thead>

                        <!-- BODY -->
                        <tbody class="divide-y divide-slate-50">

                            @forelse ($logs as $index => $log)

                                @php

                                    $color = 'bg-slate-100 text-slate-600';

                                    if (Str::contains($log->aktivitas, 'Menambahkan')) {
                                        $color = 'bg-emerald-100 text-emerald-600';
                                    } elseif (Str::contains($log->aktivitas, 'Mengupdate')) {
                                        $color = 'bg-blue-100 text-blue-600';
                                    } elseif (Str::contains($log->aktivitas, 'Menghapus')) {
                                        $color = 'bg-red-100 text-red-600';
                                    } elseif (Str::contains($log->aktivitas, 'Membayar')) {
                                        $color = 'bg-orange-100 text-orange-600';
                                    }

                                @endphp

                                <tr class="hover:bg-slate-50/70 transition-colors">

                                    <!-- NOMOR -->
                                    <td class="px-6 py-4 text-slate-400 text-xs">
                                        {{ $logs->firstItem() + $index }}
                                    </td>

                                    <!-- ADMIN -->
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">

                                                <span class="text-xs font-bold text-blue-600">
                                                    {{ strtoupper(substr($log->user->nama ?? 'A', 0, 1)) }}
                                                </span>

                                            </div>

                                            <div>
                                                <p class="font-semibold text-slate-700 text-sm">
                                                    {{ $log->user->nama ?? '-' }}
                                                </p>

                                                <p class="text-xs text-slate-400">
                                                    {{ $log->user->email ?? '' }}
                                                </p>
                                            </div>

                                        </div>

                                    </td>

                                    <!-- AKTIVITAS -->
                                    <td class="px-6 py-4">

                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-1 rounded-xl text-xs font-medium {{ $color }}">

                                            <i class="fas fa-circle text-[6px]"></i>

                                            {{ $log->aktivitas }}

                                        </span>

                                    </td>

                                    <!-- WAKTU -->
                                    <td class="px-6 py-4">

                                        <div class="flex flex-col">

                                            <span class="text-xs text-slate-700 font-medium">
                                                {{ $log->created_at->format('d M Y') }}
                                            </span>

                                            <span class="text-xs text-slate-400">
                                                {{ $log->created_at->format('H:i') }}
                                                •
                                                {{ $log->created_at->diffForHumans() }}
                                            </span>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4" class="px-6 py-16 text-center">

                                        <div class="flex flex-col items-center gap-3">

                                            <div
                                                class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">

                                                <i class="fas fa-inbox text-slate-300 text-xl"></i>

                                            </div>

                                            <div>

                                                <p class="text-sm font-medium text-slate-500">
                                                    Belum ada aktivitas
                                                </p>

                                                <p class="text-xs text-slate-400 mt-0.5">
                                                    Log aktivitas akan muncul di sini
                                                </p>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <!-- PAGINATION -->
                <div class="px-6 py-4 border-t border-slate-100">

                    {{ $logs->links() }}

                </div>

            </div>

        </div>

        </div>
        <div id="createAdminModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">

            <div class="absolute inset-0 bg-black/50" onclick="closeModal('createAdminModal')"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">

                <!-- HEADER -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">

                    <div class="flex items-center gap-3">

                        <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                        </div>

                        <div>
                            <h2 class="text-base font-bold text-slate-800">
                                Tambah Admin
                            </h2>

                            <p class="text-xs text-slate-400">
                                Tambahkan akun admin baru
                            </p>
                        </div>

                    </div>

                    <button onclick="closeModal('createAdminModal')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100">

                        <i class="fas fa-xmark"></i>

                    </button>

                </div>
                <!-- FORM -->
                @if ($errors->any() && !$errors->edit->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin.store') }}" method="POST" class="px-6 py-5 space-y-4">

                    @csrf

                    <input type="text" name="nama" placeholder="Nama Lengkap"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>

                    <input type="email" name="email" placeholder="Alamat Email"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>

                    <input type="password" name="password" placeholder="Password"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>

                    <div class="flex justify-end gap-2 pt-2">

                        <button type="button" onclick="closeModal('createAdminModal')"
                            class="px-4 py-2 rounded-xl border border-slate-200">

                            Batal

                        </button>

                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-xl">

                            Simpan

                        </button>

                    </div>

                </form>
                @if ($errors->any() && !$errors->edit->any())
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            openModal('createAdminModal');
                        });
                    </script>
                @endif
            </div>

        </div>
        <div id="editAdminModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">

            <div class="absolute inset-0 bg-black/50" onclick="closeModal('editAdminModal')"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">

                <!-- HEADER -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">

                    <div class="flex items-center gap-3">

                        <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-pen text-amber-600 text-sm"></i>
                        </div>

                        <div>
                            <h2 class="text-base font-bold text-slate-800">
                                Edit Admin
                            </h2>

                            <p class="text-xs text-slate-400">
                                Perbarui data admin
                            </p>
                        </div>

                    </div>

                    <button onclick="closeModal('editAdminModal')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100">

                        <i class="fas fa-xmark"></i>

                    </button>

                </div>

                <!-- FORM -->
                @if ($errors->edit->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700">
                        <ul>
                            @foreach ($errors->edit->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" id="editAdminForm" class="px-6 py-5 space-y-4">

                    @csrf
                    @method('PUT')

                    <!-- NAMA -->
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">
                            Nama Lengkap
                        </label>

                        <input type="text" id="edit_nama" name="nama"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                            required>
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">
                            Alamat Email
                        </label>

                        <input type="email" id="edit_email" name="email"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                            required>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">
                            Password
                        </label>

                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">

                        <button type="button" onclick="closeModal('editAdminModal')"
                            class="px-4 py-2 rounded-xl border border-slate-200">

                            Batal

                        </button>

                        <button type="submit"
                            class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm rounded-xl">

                            Update

                        </button>

                    </div>

                </form>

                @if ($errors->edit->any())
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            openModal('editAdminModal');
                        });
                    </script>
                @endif

            </div>

        </div>
    </main>
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function openEditAdmin(id, nama, email) {
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_email').value = email;

            document.getElementById('editAdminForm').action =
                `/admin/${id}`;

            openModal('editAdminModal');
        }
    </script>
</body>

</html>