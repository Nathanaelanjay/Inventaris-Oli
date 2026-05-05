<!-- Sidebar Super Admin -->
<div class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-100 flex flex-col z-30"
    style="box-shadow: 4px 0 24px rgba(0,0,0,0.04);">

    @php
        $menu = request()->segment(1);

        $isDashboard = $menu == 'dashboardadmin' || $menu == 'superadmin';
        $isLog = $menu == 'log-activity';
    @endphp

    <!-- Logo -->
    <div class="px-6 py-5 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                style="background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);">
                <i class="fas fa-user-shield text-white text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800">SuperAdmin</p>
                <p class="text-xs text-slate-400">Control Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 overflow-y-auto">
        <p class="text-xs font-semibold text-slate-400 uppercase px-3 mb-3">Menu</p>

        <div class="space-y-1">

            <!-- Dashboard -->
            <a href="/dashboardadmin"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isDashboard ? 'text-white font-semibold bg-purple-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-chart-line"></i>
                Dashboard
            </a>

            <!-- Log Aktivitas -->
            <a href="/log-activity"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                {{ $isLog ? 'text-white font-semibold bg-purple-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <i class="fas fa-clock-rotate-left"></i>
                Log Aktivitas
            </a>

        </div>
    </nav>

    <!-- User -->
    <div class="px-3 py-4 border-t border-slate-100">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-slate-50 mb-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                style="background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);">
                {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-700 truncate">{{ auth()->user()->nama }}</p>
                <p class="text-xs text-slate-400">Super Admin</p>
            </div>
        </div>

        <form action="/logout" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50">
                <i class="fas fa-right-from-bracket"></i>
                Logout
            </button>
        </form>
    </div>

</div>