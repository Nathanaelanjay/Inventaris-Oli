<!-- resources/views/barangmasuk/index.blade.php -->

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Masuk - OliStock</title>

    @vite('resources/css/app.css')

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-50" style="font-family: Inter, sans-serif;">

    <div class="flex h-screen overflow-hidden">

        @include('layouts.sidebar')

        <main class="flex-1 overflow-y-auto ml-64">

            <!-- TOP BAR -->
            <div
                class="bg-white border-b border-slate-100 px-8 py-4 flex items-center justify-between sticky top-0 z-10">
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Barang Masuk</h1>
                    <p class="text-xs text-slate-400">Manajemen stok barang masuk</p>
                </div>

                <button onclick="openModal('createModal')"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Barang Masuk
                </button>
            </div>

            <!-- CONTENT -->
            <div class="p-8">

                @if(session('success'))
                    <div
                        class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                        <i class="fas fa-circle-check text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- CARD -->
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">

                    <!-- HEADER -->
                    <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-slate-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Daftar Barang Masuk</span>
                        </div>

                        <span class="text-xs text-slate-400">
                            {{ $barangMasuk->count() }} data ditemukan
                        </span>
                    </div>

                    <!-- TABLE -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">

                            <thead>
                                <tr class="bg-slate-50 text-left">
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Harga Beli</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-50">

                                @forelse($barangMasuk as $i => $b)

                                    <tr class="hover:bg-slate-50/70 transition-colors">

                                        <td class="px-6 py-3">{{ $i + 1 }}</td>

                                        <td class="px-6 py-3">
                                            {{ date('d-m-Y H:i', strtotime($b->tanggal)) }}
                                        </td>

                                        <td class="px-6 py-3 font-medium text-slate-800">
                                            {{ $b->produk->nama_barang }}
                                        </td>

                                        <td class="px-6 py-3">
                                            {{ $b->jumlah }}
                                        </td>

                                        <td class="px-6 py-3">
                                            Rp {{ number_format($b->harga_beli, 0, ',', '.') }}
                                        </td>

                                        <td class="px-6 py-3 font-semibold text-emerald-600">
                                            Rp {{ number_format($b->total, 0, ',', '.') }}
                                        </td>

                                        <td class="px-6 py-3">
                                            <div class="flex items-center gap-2">

                                                <!-- EDIT -->
                                                <button onclick="openEdit(this)" data-id="{{ $b->barang_masuk_id }}"
                                                    data-produk="{{ $b->produk_id }}" data-jumlah="{{ $b->jumlah }}"
                                                    data-harga="{{ $b->harga_beli }}" data-total="{{ $b->total }}"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg border border-amber-100 transition-colors">

                                                    <i class="fas fa-pen text-[10px]"></i>
                                                    Edit
                                                </button>
                                                <!-- DELETE -->
                                                <form action="{{ route('barangmasuk.destroy', $b->barang_masuk_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" onclick="return confirm('Yakin hapus data ini?')"
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
                                        <td colspan="7" class="text-center py-16 text-slate-400">
                                            Belum ada data barang masuk
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

        <!-- OVERLAY -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('createModal')"></div>

        <!-- MODAL -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-plus text-blue-600 text-sm"></i>
                    </div>

                    <div>
                        <h2 class="text-base font-bold text-slate-800">
                            Tambah Barang Masuk
                        </h2>
                        <p class="text-xs text-slate-400">
                            Input stok barang masuk
                        </p>
                    </div>
                </div>

                <button onclick="closeModal('createModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <!-- FORM -->
            <form action="{{ route('barangmasuk.store') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf

                <!-- PRODUK -->
                <div>
                    <label class="text-xs text-slate-500">Pilih Produk</label>
                    <select name="produk_id" id="produk_id" onchange="ambilHarga()"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>

                        <option value="">-- Pilih Produk --</option>

                        @foreach($produk as $p)
                            <option value="{{ $p->produk_id }}" data-harga="{{ $p->harga_beli }}">
                                {{ $p->nama_barang }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- JUMLAH -->
                <div>
                    <label class="text-xs text-slate-500">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" onkeyup="hitungTotal()" placeholder="Masukkan jumlah"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>
                </div>

                <!-- HARGA BELI -->
                <div>
                    <label class="text-xs text-slate-500">Harga Beli</label>
                    <input type="number" name="harga_beli" id="harga_beli" readonly
                        class="w-full bg-slate-50 border rounded-xl px-3 py-2.5 text-sm">
                </div>

                <!-- TOTAL -->
                <div>
                    <label class="text-xs text-slate-500">Total</label>
                    <input type="number" name="total" id="total" readonly
                        class="w-full bg-slate-50 border rounded-xl px-3 py-2.5 text-sm">
                </div>

                <!-- ACTION -->
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">
                        Batal
                    </button>

                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-xl">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
    <!-- MODAL EDIT -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">

        <!-- OVERLAY -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editModal')"></div>

        <!-- MODAL -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-pen text-amber-600 text-sm"></i>
                    </div>

                    <div>
                        <h2 class="text-base font-bold text-slate-800">
                            Edit Barang Masuk
                        </h2>
                        <p class="text-xs text-slate-400">
                            Update data barang masuk
                        </p>
                    </div>
                </div>

                <button onclick="closeModal('editModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <!-- FORM -->
            <form method="POST" id="editForm" class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')

                <!-- PRODUK -->
                <div>
                    <label class="text-xs text-slate-500">Pilih Produk</label>

                    <select name="produk_id" id="e_produk" onchange="ambilHargaEdit()"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>

                        @foreach($produk as $p)
                            <option value="{{ $p->produk_id }}" data-harga="{{ $p->harga_beli }}">
                                {{ $p->nama_barang }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- JUMLAH -->
                <div>
                    <label class="text-xs text-slate-500">Jumlah</label>
                    <input type="number" name="jumlah" id="e_jumlah" onkeyup="hitungTotalEdit()"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>
                </div>

                <!-- HARGA BELI -->
                <div>
                    <label class="text-xs text-slate-500">Harga Beli</label>
                    <input type="number" name="harga_beli" id="e_harga" readonly
                        class="w-full bg-slate-50 border rounded-xl px-3 py-2.5 text-sm">
                </div>

                <!-- TOTAL -->
                <div>
                    <label class="text-xs text-slate-500">Total</label>
                    <input type="number" name="total" id="e_total" readonly
                        class="w-full bg-slate-50 border rounded-xl px-3 py-2.5 text-sm">
                </div>

                <!-- ACTION -->
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm rounded-xl">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function openEdit(btn) {

            document.getElementById('editModal').classList.remove('hidden');

            let id = btn.dataset.id;
            let produk = btn.dataset.produk;
            let jumlah = btn.dataset.jumlah;
            let harga = btn.dataset.harga;
            let total = btn.dataset.total;

            document.getElementById('editForm').action = `/barangmasuk/${id}`;

            document.getElementById('e_produk').value = produk;
            document.getElementById('e_jumlah').value = jumlah;
            document.getElementById('e_harga').value = harga;
            document.getElementById('e_total').value = total;
        }

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function ambilHarga() {
            let select = document.getElementById('produk_id');
            let harga = select.options[select.selectedIndex].dataset.harga;
            document.getElementById('harga_beli').value = harga;
            hitungTotal();
        }

        function hitungTotal() {
            let jumlah = document.getElementById('jumlah').value;
            let harga = document.getElementById('harga_beli').value;
            document.getElementById('total').value = jumlah * harga;
        }

        function ambilHargaEdit() {
            let select = document.getElementById('e_produk');
            let harga = select.options[select.selectedIndex].dataset.harga;
            document.getElementById('e_harga').value = harga;
            hitungTotalEdit();
        }

        function hitungTotalEdit() {
            let jumlah = document.getElementById('e_jumlah').value;
            let harga = document.getElementById('e_harga').value;
            document.getElementById('e_total').value = jumlah * harga;
        }

    </script>

</body>

</html>