<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - OliStock</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-50" style="font-family: 'Inter', sans-serif;">
    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->F
        @include('layouts.sidebar')

        <!--  MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto ml-64">

            <!-- Top Bar -->
            <div
                class="bg-white border-b border-slate-100 px-8 py-4 flex items-center justify-between sticky top-0 z-10">
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Produk</h1>
                    <p class="text-xs text-slate-400">Kelola data produk inventaris</p>
                </div>
                <button onclick="openModal('createModal')"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm shadow-blue-200">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Produk
                </button>
            </div>

            <!-- Content Area -->
            <div class="p-8">

                <!-- Flash Messages -->
                @if(session('success'))
                    <div
                        class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                        <i class="fas fa-circle-check text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Stats Row -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-cube text-blue-600 text-sm"></i>
                            </div>
                            <span
                                class="text-[10px] font-semibold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full">Aktif</span>
                        </div>
                        <p class="text-2xl font-bold text-slate-800">{{ $produk->count() }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Total produk terdaftar</p>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-dolly text-emerald-600 text-sm"></i>
                            </div>
                            <span
                                class="text-[10px] font-semibold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full">Unit</span>
                        </div>
                        <p class="text-2xl font-bold text-slate-800">{{ $produk->sum('stok') }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Total produk tersedia</p>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 bg-red-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-triangle-exclamation text-red-500 text-sm"></i>
                            </div>
                            <span
                                class="text-[10px] font-semibold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">Perhatian</span>
                        </div>
                        <p class="text-2xl font-bold text-red-500">
                            {{ $produk->filter(fn($p) => $p->stok <= $p->stok_minimum)->count() }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">Produk stok menipis</p>
                    </div>
                </div>
                <!-- Table Card -->
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">

                    <!-- Table Header -->
                    <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-list text-slate-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Daftar Produk</span>
                        </div>
                        <span class="text-xs text-slate-400">{{ $produk->count() }} produk ditemukan</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-left">
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Kode</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Nama Produk</th>
                                    <th
                                        class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                                        Stok</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Harga Jual</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Kategori</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Pemasok</th>
                                    <th
                                        class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse ($produk as $p)
                                    <tr class="hover:bg-slate-50/70 transition-colors group">
                                        <td class="px-6 py-3.5">
                                            <span
                                                class="font-mono text-xs font-medium text-slate-600 bg-slate-100 px-2 py-1 rounded">
                                                {{ $p->kode_barang }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <span class="font-medium text-slate-800">{{ $p->nama_barang }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            @if($p->stok <= $p->stok_minimum)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
                                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                    {{ $p->stok }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                    {{ $p->stok }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 font-medium text-slate-700">
                                            Rp {{ number_format($p->harga_jual, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3.5">
                                            @if($p->kategori)
                                                <span
                                                    class="text-xs bg-blue-50 text-blue-600 font-medium px-2.5 py-1 rounded-full border border-blue-100">
                                                    {{ $p->kategori->nama_kategori }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-sm text-slate-600">
                                            {{ $p->pemasok->nama_pemasok ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <div class="flex items-center justify-center gap-2">
                                                <button onclick='openEdit(@json($p))'
                                                    class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg border border-amber-100 transition-colors">
                                                    <i class="fas fa-pen text-[10px]"></i>
                                                    Edit
                                                </button>
                                                <form action="{{ route('produk.destroy', $p->produk_id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg border border-red-100 transition-colors">
                                                        <i class="fas fa-trash text-[10px]"></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-16">
                                            <div class="flex flex-col items-center gap-3">
                                                <div
                                                    class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-box-open text-slate-400 text-xl"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-slate-600">Belum ada produk</p>
                                                    <p class="text-xs text-slate-400 mt-0.5">Tambahkan produk pertama Anda
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>


    <!-- MODAL CREATE -->
    <div id="createModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('createModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-plus text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Tambah Produk</h2>
                        <p class="text-xs text-slate-400">Isi data produk baru</p>
                    </div>
                </div>
                <button onclick="closeModal('createModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('produk.store') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kode Barang</label>
                        <input name="kode_barang" placeholder="contoh: PRD-001"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Barang</label>
                        <input name="nama_barang" placeholder="Nama produk"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Stok</label>
                        <input name="stok" type="number" placeholder="0"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Stok Minimum</label>
                        <input name="stok_minimum" type="number" placeholder="0"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga Beli</label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">Rp</span>
                            <input name="harga_beli" type="number" placeholder="0"
                                class="w-full border border-slate-200 rounded-xl pl-8 pr-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga Jual</label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">Rp</span>
                            <input name="harga_jual" type="number" placeholder="0"
                                class="w-full border border-slate-200 rounded-xl pl-8 pr-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kategori</label>
                    <select name="kategori_id"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition bg-white">
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->kategori_id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pemasok</label>
                    <select name="pemasok_id"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition bg-white">
                        <option value="">Pilih Pemasok</option>
                        @foreach ($pemasok as $s)
                            <option value="{{ $s->pemasok_id }}">{{ $s->nama_pemasok }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-50 mt-2">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm shadow-blue-200">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- MODAL EDIT -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-pen text-amber-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Edit Produk</h2>
                        <p class="text-xs text-slate-400">Perbarui data produk</p>
                    </div>
                </div>
                <button onclick="closeModal('editModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="editForm" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kode Barang</label>
                        <input id="e_kode" name="kode_barang"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Barang</label>
                        <input id="e_nama" name="nama_barang"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Stok</label>
                        <input id="e_stok" name="stok" type="number"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Stok Minimum</label>
                        <input id="e_stok_minimum" name="stok_minimum" type="number"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga Beli</label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">Rp</span>
                            <input id="e_harga_beli" name="harga_beli" type="number"
                                class="w-full border border-slate-200 rounded-xl pl-8 pr-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga Jual</label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">Rp</span>
                            <input id="e_harga_jual" name="harga_jual" type="number"
                                class="w-full border border-slate-200 rounded-xl pl-8 pr-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition">
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-50 mt-2">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm shadow-amber-200">
                        Update Produk
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openEdit(p) {
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            document.getElementById('editForm').action = '/produk/' + p.produk_id;

            document.getElementById('e_kode').value = p.kode_barang;
            document.getElementById('e_nama').value = p.nama_barang;
            document.getElementById('e_stok').value = p.stok;
            document.getElementById('e_stok_minimum').value = p.stok_minimum;
            document.getElementById('e_harga_beli').value = p.harga_beli;
            document.getElementById('e_harga_jual').value = p.harga_jual;
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal('createModal');
                closeModal('editModal');
            }
        });
    </script>

</body>

</html>