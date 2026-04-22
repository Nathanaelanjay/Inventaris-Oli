<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hutang Pembelian - OliStock</title>

    @vite('resources/css/app.css')

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-50" style="font-family: 'Inter', sans-serif;">
    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        @include('layouts.sidebar')

        <!-- MAIN -->
        <main class="flex-1 overflow-y-auto ml-64 bg-slate-50">

            <!-- TOP BAR -->
            <div
                class="bg-white border-b border-slate-100 shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-20">
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Hutang Pembelian</h1>
                    <p class="text-xs text-slate-400">Manajemen hutang supplier</p>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="p-8">

                <!-- SUCCESS -->
                @if(session('success'))
                    <div
                        class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                        <i class="fas fa-circle-check text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ERROR -->
                @if(session('error'))
                    <div
                        class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
                        <i class="fas fa-circle-xmark text-red-500"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- CARD -->
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">

                    <!-- HEADER -->
                    <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-invoice-dollar text-slate-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Daftar Hutang</span>
                        </div>
                        <span class="text-xs text-slate-400">{{ $hutang->count() }} data</span>
                    </div>
                    <form method="GET" class="px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                        <div class="flex flex-col md:flex-row gap-3 items-center">

                            <!-- STATUS -->
                            <select name="status" class="w-full md:w-64 border rounded-xl px-3 py-2 text-sm">
                                <option value="">Semua Status</option>
                                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="hutang" {{ request('status') == 'hutang' ? 'selected' : '' }}>Hutang
                                </option>
                                <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>Sebagian
                                </option>
                            </select>

                            <div class="flex gap-2 w-full md:w-auto md:ml-auto">
                                <button type="submit"
                                    class="w-full md:w-40 bg-emerald-500 text-white rounded-xl px-4 py-2 text-sm">
                                    Filter
                                </button>

                                <a href="{{ url()->current() }}"
                                    class="w-full md:w-40 text-center bg-slate-200 rounded-xl px-4 py-2 text-sm">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>

                    <!-- TABLE -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-left">
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Pemasok</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Terbayar</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Sisa</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Jatuh Tempo
                                    </th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase text-center">
                                        Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-50">
                                @forelse ($hutang as $i => $h)
                                    <tr class="hover:bg-slate-50/70">

                                        <td class="px-6 py-3 text-xs text-slate-500">{{ $i + 1 }}</td>

                                        <td class="px-6 py-3">
                                            <div class="font-medium text-slate-800">
                                                {{ data_get($h, 'barangMasuk.produk.pemasok.nama_pemasok', '-') }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-3 font-medium text-slate-700">
                                            Rp {{ number_format($h->total_hutang, 0, ',', '.') }}
                                        </td>

                                        <td class="px-6 py-3 text-green-600">
                                            Rp {{ number_format($h->total_terbayar ?? 0, 0, ',', '.') }}
                                        </td>

                                        <td class="px-6 py-3 text-red-600 font-semibold">
                                            Rp {{ number_format($h->sisa_hutang, 0, ',', '.') }}
                                        </td>

                                        <td class="px-6 py-3 text-xs text-slate-500">
                                            {{ \Carbon\Carbon::parse($h->tanggal_jatuh_tempo)->format('d-m-Y') }}
                                        </td>

                                        <td class="px-6 py-3">
                                            @if($h->status == 'lunas')
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full bg-emerald-50 text-emerald-600">Lunas</span>
                                            @elseif($h->status == 'sebagian')
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full bg-amber-50 text-amber-600">Sebagian</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full bg-red-50 text-red-600">Hutang</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-3">
                                            <div class="flex justify-center">
                                                @if($h->status != 'lunas')
                                                    <button onclick="openBayarModal({{ $h->hutang_id }})"
                                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs rounded-lg">
                                                        <i class="fas fa-money-bill-wave"></i>Bayar
                                                    </button>
                                                @else
                                                    <span class="text-xs text-slate-400">-</span>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-16">
                                            <div class="flex flex-col items-center gap-3">
                                                <div
                                                    class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-file-invoice-dollar text-slate-400 text-xl"></i>
                                                </div>
                                                <p class="text-sm text-slate-500">Belum ada data hutang</p>
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

    <!-- MODAL BAYAR -->
    <div id="bayarModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('bayarModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-wallet text-red-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Bayar Hutang</h2>
                        <p class="text-xs text-slate-400">Lakukan pembayaran hutang pembelian</p>
                    </div>
                </div>

                <button onclick="closeModal('bayarModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <!-- BODY -->
            <form method="POST" id="bayarForm" class="p-6">
                @csrf
                <input type="text" id="jumlah_bayar" placeholder="Masukkan jumlah bayar" autocomplete="off"
                    class="w-full border px-3 py-2 rounded-xl mb-4">
                <input type="hidden" name="jumlah_bayar" id="jumlah_bayar_real">

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('bayarModal')"
                        class="px-4 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50">
                        Batal
                    </button>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl">
                        Bayar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function openBayarModal(id) {
            document.getElementById('bayarModal').classList.remove('hidden');
            let url = "{{ route('hutang.bayar', ':id') }}";
            url = url.replace(':id', id);
            document.getElementById('bayarForm').action = url;
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

        const input = document.getElementById('jumlah_bayar');
        const hidden = document.getElementById('jumlah_bayar_real');

        input.addEventListener('input', function () {
            let angka = this.value.replace(/[^0-9]/g, '');
            hidden.value = angka;
            this.value = formatRupiah(angka);
        });

        function formatRupiah(angka) {
            if (!angka) return '';
            return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
        }
    </script>

</body>

</html>