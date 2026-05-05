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

    <main class="ml-64 p-8 space-y-6">

        <!-- HEADER -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100">
            <h1 class="text-lg font-bold text-slate-800">Dashboard Super Admin</h1>
            <p class="text-xs text-slate-400 mt-1">
                {{ auth()->user()->nama }}
                • {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-2 gap-5">

            <!-- TOTAL ADMIN -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Total Admin</p>
                    <i class="fas fa-user-shield text-purple-500"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-800 mt-2">
                    {{ $totalAdmin ?? 0 }}
                </h2>
            </div>

            <!-- TOTAL LOG -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">Aktivitas Hari Ini</p>
                    <i class="fas fa-clock text-blue-500"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-800 mt-2">
                    {{ $totalLog ?? 0 }}
                </h2>
            </div>

        </div>

        <!-- FORM TAMBAH ADMIN -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100">

            <h2 class="font-semibold text-slate-800 mb-4">Tambah Admin</h2>

            <form action="{{ route('admin.store') }}" method="POST" class="grid grid-cols-3 gap-4">
                @csrf

                <input name="nama" placeholder="Nama"
                    class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">

                <input name="email" type="email" placeholder="Email"
                    class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">

                <input name="password" type="password" placeholder="Password"
                    class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">

                <button class="col-span-3 bg-purple-600 text-white py-2 rounded-xl font-semibold hover:opacity-90">
                    Tambah Admin
                </button>
            </form>

        </div>

        <!-- LOG AKTIVITAS -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">

            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold text-sm text-slate-800">Log Aktivitas Admin</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-slate-50 text-slate-500 text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Admin</th>
                            <th class="px-6 py-3 text-left">Aktivitas</th>
                            <th class="px-6 py-3 text-left">Waktu</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($logs ?? [] as $log)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3 font-medium text-slate-800">
                                    {{ $log->user->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-3 text-slate-600">
                                    {{ $log->aktivitas }}
                                </td>
                                <td class="px-6 py-3 text-slate-400">
                                    {{ $log->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-slate-400">
                                    Belum ada aktivitas
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

    </main>

</body>

</html>