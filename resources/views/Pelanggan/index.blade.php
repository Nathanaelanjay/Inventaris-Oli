<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggan - OliStock</title>

    @vite('resources/css/app.css')

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-50" style="font-family: 'Inter', sans-serif;">

    <div class="flex h-screen overflow-hidden">

        @include('layouts.sidebar')

        <main class="flex-1 overflow-y-auto ml-64">

            <!-- TOP BAR -->
            <div
                class="bg-white border-b border-slate-100 px-8 py-4 flex items-center justify-between sticky top-0 z-10">
                <div>
                    <h1 class="text-lg font-bold text-slate-800">Pelanggan</h1>
                    <p class="text-xs text-slate-400">Manajemen data pelanggan</p>
                </div>

                <button onclick="openModal('createModal')"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Pelanggan
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
                                <i class="fas fa-user text-slate-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Daftar Pelanggan</span>
                        </div>

                        <span class="text-xs text-slate-400">{{ $pelanggan->count() }} pelanggan ditemukan</span>
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
                                @forelse ($pelanggan as $i => $p)
                                    <tr class="hover:bg-slate-50/70">

                                        <td class="px-6 py-3 text-xs text-slate-500">
                                            {{ $i + 1 }}
                                        </td>

                                        <td class="px-6 py-3 font-medium text-slate-800">
                                            {{ $p->nama_bengkel }}
                                        </td>

                                        <td class="px-6 py-3 text-slate-600">
                                            {{ $p->no_telp ?? '-' }}
                                        </td>

                                        <td class="px-6 py-3 text-slate-600">
                                            {{ $p->alamat ?? '-' }}
                                        </td>

                                        <td class="px-6 py-3">
                                            <div class="flex justify-center gap-2">

                                                <!-- EDIT -->
                                                <button onclick='openEdit(@json($p))'
                                                    class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg border border-amber-100">
                                                    <i class="fas fa-pen text-[10px]"></i>
                                                    Edit
                                                </button>

                                                <!-- DELETE -->
                                                <form action="{{ route('pelanggan.destroy', $p->pelanggan_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Yakin hapus pelanggan ini?')"
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
                                        <td colspan="5" class="text-center py-16 text-slate-400">
                                            Belum ada pelanggan
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
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('createModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-plus text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Tambah Pelanggan</h2>
                        <p class="text-xs text-slate-400">Isi data pelanggan baru</p>
                    </div>
                </div>

                <button onclick="closeModal('createModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('pelanggan.store') }}" class="px-6 py-5 space-y-4">
                @csrf

                <!-- NAMA BENGKEL -->
                <div>
                    <input type="text" name="nama_bengkel" placeholder="Nama Bengkel"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>
                </div>

                <!-- NO TELP -->
                <div>
                    <input type="text" name="no_telp" placeholder="Nomor Telepon"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm">
                </div>

                <!-- ALAMAT -->
                <div>
                    <textarea name="alamat" placeholder="Alamat"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm"></textarea>
                </div>

                <!-- ACTION -->
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50">
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
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('editModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-pen text-amber-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">
                            Edit Pelanggan
                        </h2>
                        <p class="text-xs text-slate-400">
                            Perbarui data pelanggan
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
                    <label class="text-xs text-slate-500">Nama Bengkel</label>
                    <input type="text" id="e_nama" name="nama_bengkel"
                        class="w-full border rounded-xl px-3 py-2.5 text-sm" required>
                </div>

                <!-- TELEPON -->
                <div>
                    <label class="text-xs text-slate-500">Nomor Telepon</label>
                    <input type="text" id="e_telepon" name="no_telp"
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
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50">
                        Batal
                    </button>

                    <button class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm rounded-xl">
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

            document.getElementById('editForm').action = `/pelanggan/${data.pelanggan_id}`;

            document.getElementById('e_nama').value = data.nama_bengkel;
            document.getElementById('e_telepon').value = data.no_telp;
            document.getElementById('e_alamat').value = data.alamat;
        }
    </script>

</body>

</html>