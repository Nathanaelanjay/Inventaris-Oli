<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-100 flex flex-col z-30"
    style="box-shadow: 4px 0 24px rgba(0,0,0,0.04);">

    <!-- Logo -->
    <div class="px-6 py-5 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                <i class="fas fa-box text-white text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800 leading-tight">OliStock</p>
                <p class="text-xs text-slate-400 leading-tight">Inventory Management</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 overflow-y-auto">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 mb-3">Menu Utama</p>
        <div class="space-y-1">

            <!-- Dashboard -->
            <a href="/dashboard"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
               {{ request()->is('dashboard') ? 'text-white font-semibold' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-800' }}"
                style="{{ request()->is('dashboard') ? 'background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);' : '' }}">
                <i class="fas fa-th-large text-sm flex-shrink-0"></i>
                Dashboard
            </a>

            <!-- Produk -->
            <a href="/produk"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
               {{ request()->is('produk*') ? 'text-white font-semibold' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-800' }}"
                style="{{ request()->is('produk*') ? 'background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);' : '' }}">
                <i class="fas fa-box text-sm flex-shrink-0"></i>
                Produk
            </a>

            <!-- Barang Masuk -->
            <a href="/barang-masuk"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
               {{ request()->is('barang-masuk*') ? 'text-white font-semibold' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-800' }}"
                style="{{ request()->is('barang-masuk*') ? 'background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);' : '' }}">
                <i class="fas fa-arrows-up-down text-sm flex-shrink-0"></i>
                Barang Masuk
            </a>

            <!-- Barang Keluar -->
            <a href="/barang-keluar"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
               {{ request()->is('barang-keluar*') ? 'text-white font-semibold' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-800' }}"
                style="{{ request()->is('barang-keluar*') ? 'background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);' : '' }}">
                <i class="fas fa-exchange-alt text-sm flex-shrink-0"></i>
                Barang Keluar
            </a>

            <!-- Kategori -->
            <a href="/kategori"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
               {{ request()->is('kategori*') ? 'text-white font-semibold' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-800' }}"
                style="{{ request()->is('kategori*') ? 'background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);' : '' }}">
                <i class="fas fa-tag text-sm flex-shrink-0"></i>
                Kategori
            </a>

            <!-- Pemasok -->
            <a href="/pemasok"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
               {{ request()->is('pemasok*') ? 'text-white font-semibold' : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-800' }}"
                style="{{ request()->is('pemasok*') ? 'background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);' : '' }}">
                <i class="fas fa-users text-sm flex-shrink-0"></i>
                Pemasok
            </a>

        </div>
    </nav>

    <!-- User Profile & Logout -->
    <div class="px-3 py-4 border-t border-slate-100">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-slate-50 mb-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
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
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition-all">
                <i class="fas fa-right-from-bracket text-sm flex-shrink-0"></i>
                Keluar
            </button>
        </form>
    </div>
</div>