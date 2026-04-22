<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Inventory Oli</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-50" style="font-family: 'Inter', sans-serif;">

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto ml-64">

        <!-- Top Bar -->
        <div class="bg-white border-b border-slate-100 px-8 py-4 flex items-center justify-between sticky top-0 z-20"
            style="box-shadow: 0 1px 12px rgba(0,0,0,0.04);">
            <div>
                <h1 class="text-lg font-bold text-slate-800">Dashboard</h1>
                <p class="text-xs text-slate-400">Selamat datang, {{ auth()->user()->nama }} &mdash;
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-amber-50 border border-amber-100">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    <span class="text-xs font-semibold text-amber-700">{{ $produkMenipis }} produk stok menipis</span>
                </div>
                <button
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-white font-semibold text-sm transition-all hover:opacity-90 active:scale-95"
                    style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                    <i class="fas fa-envelope w-4 h-4"></i>
                    Kirim Email Stok
                </button>
            </div>
        </div>

        <!-- Page Content -->
        <div class="px-8 py-7 space-y-7">

            <!-- Stat Cards -->
            <div class="grid grid-cols-4 gap-5">

                <!-- Total Produk -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50">
                            <i class="fas fa-box text-blue-600 text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Aktif</span>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Total Produk</p>
                    <h2 class="text-3xl font-extrabold text-slate-800 mt-1 tracking-tight">{{ $totalProduk }}</h2>
                    <p class="text-xs text-slate-400 mt-1">Total produk terdaftar</p>
                </div>

                <!-- Produk Menipis -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-red-50">
                            <i class="fas fa-triangle-exclamation text-red-500 text-lg"></i>
                        </div>
                        <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-lg">Perhatian</span>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Produk Menipis</p>
                    <h2 class="text-3xl font-extrabold text-red-500 mt-1 tracking-tight">{{ $produkMenipis }}</h2>
                    <p class="text-xs text-slate-400 mt-1">Perlu segera diisi ulang</p>
                </div>

                <!-- Total Piutang -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50">
                            <i class="fas fa-wallet text-blue-600 text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Rupiah</span>
                    </div>

                    <p class="text-sm text-slate-500 font-medium">Total Piutang</p>

                    <h2 class="text-3xl font-extrabold text-slate-800 mt-1 tracking-tight">
                        Rp {{ number_format($totalPiutang, 0, ',', '.') }}
                    </h2>

                    <p class="text-xs text-slate-400 mt-1">Total piutang pelanggan</p>
                </div>

                <!-- Total Hutang Pembelian -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-red-50">
                            <i class="fas fa-file-invoice-dollar text-red-600 text-lg"></i>
                        </div>
                        <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-lg">Rupiah</span>
                    </div>

                    <p class="text-sm text-slate-500 font-medium">Total Hutang</p>

                    <h2 class="text-3xl font-extrabold text-red-600 mt-1 tracking-tight">
                        Rp {{ number_format($totalHutang, 0, ',', '.') }}
                    </h2>

                    <p class="text-xs text-slate-400 mt-1">Total hutang pembelian</p>
                </div>
            </div>

            <!-- Financial Cards -->
            <div class="grid grid-cols-3 gap-5">

                <!-- Pemasukan -->
                <div class="rounded-2xl p-6 relative overflow-hidden"
                    style="background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full -mr-10 -mt-10 bg-white opacity-5"></div>
                    <div class="absolute bottom-0 left-0 w-20 h-20 rounded-full -ml-6 -mb-6 bg-white opacity-5"></div>
                    <div class="relative">
                        <i class="fa-solid fa-arrow-trend-up text-white text-lg"></i>
                        <p class="text-white text-sm font-semibold">Pemasukan</p>
                        <h2 class="text-4xl font-extrabold text-white mt-1 tracking-tight">
                            Rp {{ number_format($pemasukan, 0, ',', '.') }}
                        </h2>
                        <p class="text-white text-xs opacity-80 mt-2">Bulan ini &bull;
                            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                        </p>
                    </div>
                </div>

                <!-- Pengeluaran -->
                <div class="rounded-2xl p-6 relative overflow-hidden"
                    style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full -mr-10 -mt-10 bg-white opacity-5"></div>
                    <div class="absolute bottom-0 left-0 w-20 h-20 rounded-full -ml-6 -mb-6 bg-white opacity-5"></div>
                    <div class="relative">
                        <i class="fas fa-arrow-trend-down text-white text-lg"></i>
                        <p class="text-white text-sm font-semibold">Pengeluaran</p>
                        <h2 class="text-4xl font-extrabold text-white mt-1 tracking-tight">
                            Rp {{ number_format($pengeluaran, 0, ',', '.') }}
                        </h2>
                        <p class="text-white text-xs opacity-80 mt-2">Bulan ini &bull;
                            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                        </p>
                    </div>
                </div>

                <!-- Keuntungan Bersih -->
                <div class="rounded-2xl p-6 relative overflow-hidden"
                    style="background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%);">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full -mr-10 -mt-10 bg-white opacity-5"></div>
                    <div class="absolute bottom-0 left-0 w-20 h-20 rounded-full -ml-6 -mb-6 bg-white opacity-5"></div>
                    <div class="relative">
                        <i class="fas fa-sack-dollar text-white text-lg"></i>
                        <p class="text-white text-sm font-semibold">Keuntungan Bersih</p>
                        <h2 class="text-4xl font-extrabold text-white mt-1 tracking-tight">
                            Rp {{ number_format($keuntungan_bersih, 0, ',', '.') }}
                        </h2>
                        <p class="text-white text-xs opacity-80 mt-2">Bulan ini &bull;
                            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                        </p>
                    </div>
                </div>

            </div>

            <!-- Low Stock Table -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                style="box-shadow: 0 2px 16px rgba(0,0,0,0.05);">

                <!-- Table Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                            style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                            <i class="fas fa-triangle-exclamation w-4 h-4 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Notifikasi Stok Menipis</h3>
                            <p class="text-xs text-slate-400">Produk yang perlu segera di-restock</p>
                        </div>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-orange-50 border border-orange-100 text-xs font-semibold text-orange-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                        {{ \App\Models\Produk::whereColumn('stok', '<=', 'stok_minimum')->count() }} produk
                    </span>
                </div>

                <!-- Table -->
                @php $lowStockItems = \App\Models\Produk::whereColumn('stok', '<=', 'stok_minimum')->get(); @endphp

                @if($lowStockItems->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4">
                            <i class="fas fa-circle-check text-emerald-500 text-2xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Semua stok aman</p>
                        <p class="text-xs text-slate-400 mt-1">Tidak ada produk dengan stok menipis</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50">
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider w-12">
                                        #</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                        Nama Produk</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                        Stok Saat Ini</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                        Stok Minimum</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                        Selisih</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($lowStockItems as $index => $item)
                                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-semibold text-slate-400">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center bg-orange-50 flex-shrink-0">
                                                    <i class="fas fa-box text-orange-500 text-sm"></i>
                                                </div>
                                                <span
                                                    class="text-sm font-semibold text-slate-800">{{ $item->nama_barang }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-red-50 text-red-600 font-bold text-sm border border-red-100">{{ $item->stok }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm">{{ $item->stok_minimum }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg bg-red-50 text-red-600 font-semibold text-xs border border-red-100">
                                                -{{ $item->stok_minimum - $item->stok }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($item->stok == 0)
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold border border-red-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                    Habis
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-orange-50 text-orange-700 text-xs font-semibold border border-orange-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                                                    Stok Menipis
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>

        </div>
        </div>

</body>

</html>