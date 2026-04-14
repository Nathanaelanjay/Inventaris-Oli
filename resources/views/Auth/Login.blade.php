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
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 rounded-2xl shadow-lg mb-4">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Welcome back</h1>
            </div>

            {{-- Card --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-8 shadow-sm">

                {{-- ERROR --}}
                @if(session('error'))
                    <div
                        class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm">
                        <i class="fas fa-triangle-exclamation w-5 h-5 mt-0.5 shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- SUCCESS --}}
                @if(session('success'))
                    <div
                        class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl mb-6 text-sm">
                        <i class="fas fa-circle-check w-5 h-5 mt-0.5 shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="/login" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-slate-400 text-sm"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email"
                                class="w-full bg-white border border-slate-200 text-slate-800 placeholder-slate-400 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition">
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
                                class="w-full bg-white border border-slate-200 text-slate-800 placeholder-slate-400 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition">
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-sm shadow-blue-200 text-sm mt-2">
                        Login
                    </button>
                </form>

                <p class="text-sm text-center mt-6 text-slate-500">
                    Belum punya akun?
                    <a href="/register"
                        class="text-blue-600 hover:text-blue-700 font-medium hover:underline transition">Register</a>
                </p>

            </div>

        </div>

    </div>
</body>

</html>