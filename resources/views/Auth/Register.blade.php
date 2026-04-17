<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4">

        <div class="w-full max-w-md">

            {{-- Logo / Brand --}}
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-emerald-500 rounded-2xl shadow-lg mb-4">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Create Account</h1>
            </div>

            {{-- Card --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-8 shadow-sm">

                {{-- ERROR VALIDATION --}}
                @if($errors->any())
                    <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl mb-6 p-4">
                        <i class="fas fa-triangle-exclamation w-5 h-5 mt-0.5 shrink-0 text-red-500"></i>
                        <ul class="text-red-600 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/register" class="space-y-5">
                    @csrf

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Nama</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-slate-400 text-sm"></i>
                            </div>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama"
                                class="w-full bg-white border border-slate-200 text-slate-800 placeholder-slate-400 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 transition">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-slate-400 text-sm"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email"
                                class="w-full bg-white border border-slate-200 text-slate-800 placeholder-slate-400 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 transition">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400 text-sm"></i>
                            </div>
                            <input type="password" name="password" placeholder="Masukkan password"
                                class="w-full bg-white border border-slate-200 text-slate-800 placeholder-slate-400 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Role</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-shield text-slate-400 text-sm"></i>
                            </div>

                            <select name="role_id"
                                class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 transition">

                                <option value="">Pilih Role</option>

                                @foreach($roles as $role)
                                    <option value="{{ $role->role_id }}" {{ old('role_id') == $role->role_id ? 'selected' : '' }}>
                                        {{ $role->nama_role }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-sm shadow-emerald-200 text-sm mt-2">
                        Register
                    </button>
                </form>

                <p class="text-sm text-center mt-6 text-slate-500">
                    Sudah punya akun?
                    <a href="/login"
                        class="text-emerald-600 hover:text-emerald-700 font-medium hover:underline transition">Login</a>
                </p>

            </div>

        </div>
    </div>
</body>

</html>