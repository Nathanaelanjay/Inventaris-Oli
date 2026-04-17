<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemasok - OliStock</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-50" style="font-family: 'Inter', sans-serif;">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        @include('layouts.sidebar')

        <!-- MAIN -->
        <main class="flex-1 overflow-y-auto ml-64">

            <!-- TOP BAR -->
            <div
                class="bg-white border-b border-slate-100 px-8 py-4 flex items-center justify-between sticky top-0 z-10">
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Pemasok</h1>
                    <p class="text-xs text-slate-400">Manajemen data Pemasok</p>
                </div>

                <button onclick="openModal('createModal')"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Pemasok
                </button>
            </div>

            <!-- CONTENT -->
            <div class="p-8">

                <!-- ALERT -->
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
                                <i class="fas fa-truck text-slate-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Daftar Pemasok</span>
                        </div>
                        <span class="text-xs text-slate-400">{{ $pemasok->count() }} pemasok ditemukan</span>
                    </div>

                    <!-- TABLE -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-left">
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Telepon</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Alamat</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase text-center">
                                        Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-50">
                                @forelse ($pemasok as $i => $s)
                                    <tr class="hover:bg-slate-50/70 transition-colors">

                                        <td class="px-6 py-3.5 text-xs text-slate-500">
                                            {{ $i + 1 }}
                                        </td>

                                        <td class="px-6 py-3.5 font-medium text-slate-800">
                                            {{ $s->nama_pemasok }}
                                        </td>

                                        <td class="px-6 py-3.5 text-slate-600">
                                            {{ $s->kontak ?? '-' }}
                                        </td>

                                        <td class="px-6 py-3.5 text-slate-600">
                                            {{ $s->alamat ?? '-' }}
                                        </td>

                                        <td class="px-6 py-3.5">
                                            <div class="flex items-center justify-center gap-2">

                                                <!-- EDIT -->
                                                <button onclick='openEdit(@json($s))'
                                                    class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg border border-amber-100">
                                                    <i class="fas fa-pen text-[10px]"></i>
                                                    Edit
                                                </button>

                                                <!-- DELETE -->
                                                <form action="{{ route('pemasok.destroy', $s->pemasok_id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Yakin hapus pemasok ini?')"
                                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg border border-red-100">
                                                        <i class="fas fa-trash text-[10px]"></i>
                                                        Hapus
                                                    </button>
                                                </form>

                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-16">
                                            <div class="flex flex-col items-center gap-3">
                                                <div
                                                    class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-truck text-slate-400 text-xl"></i>
                                                </div>
                                                <p class="text-sm text-slate-500">Belum ada pemasok</p>
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

    <div id="createModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('createModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-plus text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Tambah Pemasok</h2>
                        <p class="text-xs text-slate-400">Isi data pemasok baru</p>
                    </div>
                </div>

                <button onclick="closeModal('createModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('pemasok.store') }}" class="px-6 py-5 space-y-4">
                @csrf

                <!-- NAMA -->
                <div>
                    <input type="text" name="nama_pemasok" placeholder="Nama Pemasok"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>
                </div>

                <!-- KONTAK -->
                <div>
                    <input type="text" name="kontak" placeholder="Nomor Telepon"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm">
                </div>

                <!-- ALAMAT -->
                <div>
                    <textarea name="alamat" placeholder="Alamat"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm"></textarea>
                </div>

                <!-- ACTION -->
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('createModal')" class="px-4 py-2">
                        Batal
                    </button>

                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('editModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-pen text-amber-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 id="editTitle" class="text-base font-bold text-slate-800">
                            Edit Pemasok
                        </h2>
                        <p class="text-xs text-slate-400">
                            Perbarui data pemasok
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

                <!-- NAMA -->
                <div>
                    <label class="text-xs text-slate-500">Nama Pemasok</label>
                    <input type="text" id="e_nama" name="nama_pemasok"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>
                </div>

                <!-- TELEPON -->
                <div>
                    <label class="text-xs text-slate-500">Nomor Telepon</label>
                    <input type="text" id="e_telepon" name="kontak"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm">
                </div>

                <!-- ALAMAT -->
                <div>
                    <label class="text-xs text-slate-500">Alamat</label>
                    <textarea id="e_alamat" name="alamat"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm"></textarea>
                </div>

                <!-- ACTION -->
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
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openEdit(data) {
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            document.getElementById('editForm').action = `/pemasok/${data.pemasok_id}`;

            document.getElementById('e_nama').value = data.nama_pemasok;

            document.getElementById('e_telepon').value = data.kontak;

            document.getElementById('e_alamat').value = data.alamat;
        }
    </script>

</body>

</html>