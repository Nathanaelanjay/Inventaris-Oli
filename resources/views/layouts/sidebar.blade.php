<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-100 flex flex-col z-30"
    style="box-shadow: 4px 0 24px rgba(0,0,0,0.04);">

    @php
        $menu = request()->segment(1);

        $isDashboard = $menu == 'dashboard';
        $isProduk = $menu == 'produk';
        $isBarangMasuk = str_contains($menu, 'masuk');
        $isBarangKeluar = str_contains($menu, 'keluar');
        $isKategori = $menu == 'kategori';
        $isPelanggan = $menu == 'pelanggan';
        $isPemasok = $menu == 'pemasok';
        $isPiutang = $menu == 'piutang-pelanggan';
        $isHutang = $menu == 'hutang-pembelian';
    @endphp

    <!-- Logo -->
    <div class="px-6 py-5 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                <i class="fas fa-box text-white text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800">OliStock</p>
                <p class="text-xs text-slate-400">Inventory Management</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 overflow-y-auto">
        <p class="text-xs font-semibold text-slate-400 uppercase px-3 mb-3">Menu Utama</p>

        <div class="space-y-1">

            <!-- Dashboard -->
            <a href="/dashboard"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isDashboard ? 'text-white font-semibold bg-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-th-large"></i>
                Dashboard
            </a>

            <!-- Produk -->
            <a href="/produk"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isProduk ? 'text-white font-semibold bg-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-box"></i>
                Produk
            </a>

            <!-- Barang Masuk -->
            <a href="/barang-masuk"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isBarangMasuk ? 'text-white font-semibold bg-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-arrows-up-down"></i>
                Barang Masuk
            </a>

            <!-- Barang Keluar -->
            <a href="/barang-keluar"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isBarangKeluar ? 'text-white font-semibold bg-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-exchange-alt"></i>
                Barang Keluar
            </a>

            <!-- Kategori -->
            <a href="/kategori"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isKategori ? 'text-white font-semibold bg-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-tag"></i>
                Kategori
            </a>

            <!-- Pelanggan -->
            <a href="/pelanggan"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isPelanggan ? 'text-white font-semibold bg-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-user"></i>
                Pelanggan
            </a>

            <!-- Pemasok -->
            <a href="/pemasok"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isPemasok ? 'text-white font-semibold bg-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-users"></i>
                Pemasok
            </a>

            <!-- Piutang -->
            <a href="/piutang-pelanggan"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isPiutang ? 'text-white font-semibold bg-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-hand-holding-usd"></i>
                Piutang Pelanggan
            </a>

            <!-- Hutang -->
            <a href="/hutang-pembelian"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isHutang ? 'text-white font-semibold bg-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-file-invoice-dollar"></i>
                Hutang Pembelian
            </a>

        </div>
    </nav>

    <!-- User -->
    <div class="px-3 py-4 border-t border-slate-100">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-slate-50 mb-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-700 truncate">{{ auth()->user()->nama }}</p>
                <p class="text-xs text-slate-400">Administrator</p>
            </div>
        </div>

        <form action="/logout" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50">
                <i class="fas fa-right-from-bracket"></i>
                Keluar
            </button>
        </form>
    </div>
</div>