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

        <!-- SIDEBAR -->
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
                    <form method="GET" class="px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            {{-- Filter Kategori --}}
                            <select name="kategori"
                                class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400">
                                <option value="">Semua Kategori</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->kategori_id }}" {{ request('kategori') == $k->kategori_id ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Filter Pemasok --}}
                            <select name="pemasok"
                                class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400">
                                <option value="">Semua Pemasok</option>
                                @foreach($pemasok as $s)
                                    <option value="{{ $s->pemasok_id }}" {{ request('pemasok') == $s->pemasok_id ? 'selected' : '' }}>
                                        {{ $s->nama_pemasok }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Sorting Stok --}}
                            <select name="stok"
                                class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400">
                                <option value="">Urutkan Stok</option>
                                <option value="asc" {{ request('stok') == 'asc' ? 'selected' : '' }}>Terdikit ke Terbesar
                                </option>
                                <option value="desc" {{ request('stok') == 'desc' ? 'selected' : '' }}>Terbesar ke
                                    Terdikit</option>
                            </select>

                            {{-- Tombol --}}
                            <div class="flex gap-2">
                                <button type="submit"
                                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-xl px-4 py-2">
                                    Filter
                                </button>
                                <a href="{{ url()->current() }}"
                                    class="w-full text-center bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-medium rounded-xl px-4 py-2">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

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
                                        Harga Beli</th>
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
                                            Rp {{ number_format($p->harga_beli, 0, ',', '.') }}
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
                                                <button data-produk='@json($p)' onclick="openEdit(this)"
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

            <!-- HEADER -->
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
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <!-- FORM CREATE -->
            <form action="{{ route('produk.store') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <input name="kode_barang" placeholder="Kode Barang"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm">

                    <input name="nama_barang" placeholder="Nama Barang"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <input name="stok" type="number" placeholder="Stok"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm">

                    <input name="stok_minimum" type="number" placeholder="Stok Minimum"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <input name="harga_beli" type="number" placeholder="Harga Beli"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm">

                    <input name="harga_jual" type="number" placeholder="Harga Jual"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm">
                </div>

                <select id="e_kategori" name="kategori_id" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->kategori_id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>

                <select id="e_pemasok" name="pemasok_id" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    <option value="">-- Pilih Pemasok --</option>
                    @foreach ($pemasok as $s)
                        <option value="{{ $s->pemasok_id }}">{{ $s->nama_pemasok }}</option>
                    @endforeach
                </select>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('createModal')" class="px-4 py-2">
                        Batal
                    </button>
                    <button class="px-5 py-2 bg-blue-600 text-white rounded-xl">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- MODAL EDIT -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">

            <!-- HEADER -->
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
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form id="editForm" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500">Kode Barang</label>
                        <input id="e_kode" name="kode_barang" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    </div>

                    <div>
                        <label class="text-xs text-slate-500">Nama Barang</label>
                        <input id="e_nama" name="nama_barang" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500">Stok</label>
                        <input id="e_stok" name="stok" type="number"
                            class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    </div>

                    <div>
                        <label class="text-xs text-slate-500">Stok Minimum</label>
                        <input id="e_stok_minimum" name="stok_minimum" type="number"
                            class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500">Harga Beli</label>
                        <input id="e_harga_beli" name="harga_beli" type="number"
                            class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    </div>

                    <div>
                        <label class="text-xs text-slate-500">Harga Jual</label>
                        <input id="e_harga_jual" name="harga_jual" type="number"
                            class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div>
                    <label class="text-xs text-slate-500">Kategori</label>
                    <select id="e_kategori" name="kategori_id" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                        @foreach ($kategori as $k)
                            <option value="{{ $k->kategori_id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-500">Pemasok</label>
                    <select id="e_pemasok" name="pemasok_id" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                        @foreach ($pemasok as $s)
                            <option value="{{ $s->pemasok_id }}">{{ $s->nama_pemasok }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2">
                        Batal
                    </button>

                    <button type="submit" class="px-5 py-2 bg-amber-500 text-white rounded-xl">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- SCRIPT -->
    <script>

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function openEdit(btn) {
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            let data = JSON.parse(btn.dataset.produk);

            document.getElementById('editForm').action =
                '/produk/' + data.produk_id;

            document.getElementById('e_kode').value = data.kode_barang;
            document.getElementById('e_nama').value = data.nama_barang;
            document.getElementById('e_stok').value = data.stok;
            document.getElementById('e_stok_minimum').value = data.stok_minimum;
            document.getElementById('e_harga_beli').value = data.harga_beli;
            document.getElementById('e_harga_jual').value = data.harga_jual;
            document.getElementById('e_kategori').value = data.kategori_id;
            document.getElementById('e_pemasok').value = data.pemasok_id;
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