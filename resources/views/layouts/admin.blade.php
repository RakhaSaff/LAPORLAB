<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — LAPORLAB Saintek')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        :root {
            --sidebar-width: 260px;
            --green-primary: #059669;
            --green-dark: #047857;
            --green-light: #d1fae5;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #065f46;
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid #1e293b;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-item:hover { background: var(--sidebar-hover); color: #e2e8f0; }

        .nav-item.active {
            background: var(--sidebar-active);
            color: #fff;
            font-weight: 600;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: #10b981;
            border-radius: 0 4px 4px 0;
        }

        .nav-item i { width: 18px; text-align: center; font-size: 15px; }

        .nav-badge {
            margin-left: auto;
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 20px;
            min-width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid #1e293b;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8fafc;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 22px 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 0 0 0 80px;
            opacity: 0.06;
        }

        .stat-card.green::after  { background: #059669; }
        .stat-card.orange::after { background: #f59e0b; }
        .stat-card.blue::after   { background: #3b82f6; }
        .stat-card.red::after    { background: #ef4444; }

        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-diproses { background: #dbeafe; color: #1e40af; }
        .badge-selesai  { background: #d1fae5; color: #065f46; }
        .badge-rendah   { background: #f0fdf4; color: #166534; }
        .badge-sedang   { background: #fffbeb; color: #92400e; }
        .badge-tinggi   { background: #fef2f2; color: #991b1b; }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table td {
            padding: 14px 16px;
            font-size: 13.5px;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .data-table tr:hover td { background: #f8fafc; }

        .card {
            background: white;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-in   { animation: fadeInUp 0.4s ease forwards; }
        .fade-in-1 { animation-delay: 0.05s; opacity: 0; }
        .fade-in-2 { animation-delay: 0.1s;  opacity: 0; }
        .fade-in-3 { animation-delay: 0.15s; opacity: 0; }
        .fade-in-4 { animation-delay: 0.2s;  opacity: 0; }

        /* Dropdown profil */
        .profile-dropdown { position: relative; }
        .profile-dropdown-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            min-width: 220px;
            z-index: 999;
            overflow: hidden;
            display: none;
        }
        .profile-dropdown-menu.open { display: block; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow">
                L
            </div>
            <div>
                <div class="text-white font-bold text-base tracking-tight">LAPORLAB</div>
                <div class="text-emerald-400 text-xs font-medium">Saintek · Admin</div>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav">
        <p class="text-xs font-semibold text-slate-600 uppercase tracking-widest px-3 mb-2 mt-2">Menu Utama</p>

        <a href="{{ route('admin.laporan-masuk.index') }}"
           class="nav-item {{ request()->routeIs('admin.laporan-masuk*') ? 'active' : '' }}">
            <i class="fa-solid fa-inbox"></i>
            Laporan Masuk
            <span class="nav-badge" id="badge-masuk">–</span>
        </a>

        <a href="{{ route('admin.perlu-verifikasi') }}"
           class="nav-item {{ request()->routeIs('admin.perlu-verifikasi*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-check"></i>
            Perlu Diverifikasi
            <span class="nav-badge" id="badge-verifikasi">–</span>
        </a>

        <a href="{{ route('admin.manajemen-perbaikan') }}"
           class="nav-item {{ request()->routeIs('admin.manajemen-perbaikan*') ? 'active' : '' }}">
            <i class="fa-solid fa-screwdriver-wrench"></i>
            Manajemen Perbaikan
        </a>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i>
            Dashboard
        </a>

        <a href="{{ route('admin.rekapan') }}"
           class="nav-item {{ request()->routeIs('admin.rekapan*') ? 'active' : '' }}">
            <i class="fa-solid fa-box-archive"></i>
            Rekapan
        </a>

        <a href="{{ route('admin.import.index') }}"
           class="nav-item {{ request()->routeIs('admin.import*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-import"></i>
            Import Laporan
        </a>

        <a href="{{ route('admin.master-aset.index') }}"
           class="nav-item {{ request()->routeIs('admin.master-aset*') ? 'active' : '' }}">
            <i class="fa-solid fa-database"></i>
            Master Data
        </a>

        {{-- ✅ Label Aset dihapus --}}

    </nav>

    <div class="sidebar-footer">
        <div class="flex items-center gap-3 px-2">
            <div class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="overflow-hidden flex-1">
                <div class="text-white text-sm font-semibold truncate">
                    {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                </div>
                <div class="text-slate-400 text-xs truncate">
                    {{ Auth::guard('admin')->user()->jabatan ?? 'Administrator' }}
                </div>
            </div>
            <a href="{{ route('admin.profil') }}"
               class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-emerald-800 text-slate-400 hover:text-emerald-400 transition-all duration-200 shrink-0"
               title="Profil Saya">
                <i class="fa-solid fa-gear text-sm"></i>
            </a>
        </div>
    </div>
</aside>

<div class="main-content">
    <div class="topbar">
        <div class="flex items-center gap-3">
            <button onclick="document.getElementById('sidebar').classList.toggle('open')"
                    class="md:hidden text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <div>
                <h2 class="text-base font-bold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                <p class="text-xs text-gray-500">@yield('page-subtitle', 'LAPORLAB Saintek · FST UINSA')</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <div class="text-xs text-gray-500" id="realtime-clock"></div>
                <div class="text-xs text-gray-400" id="realtime-date"></div>
            </div>

            <div class="profile-dropdown">
                <button onclick="toggleProfileDropdown()"
                        class="flex items-center gap-2 px-2 py-1.5 rounded-xl hover:bg-gray-100 transition-all duration-200 group">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">
                        {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-left hidden sm:block">
                        <div class="text-xs font-semibold text-gray-800 leading-tight">
                            {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ Auth::guard('admin')->user()->jabatan ?? 'Administrator' }}
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 group-hover:text-gray-600 hidden sm:block"></i>
                </button>

                <div class="profile-dropdown-menu" id="profileDropdownMenu">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold">
                                {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ Auth::guard('admin')->user()->email ?? '' }}
                                </div>
                                <div class="text-xs text-emerald-600 font-medium">
                                    {{ Auth::guard('admin')->user()->jabatan ?? 'Administrator' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('admin.profil') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-emerald-600 transition-colors">
                            <i class="fa-solid fa-user w-4 text-center text-gray-400"></i>
                            Profil Saya
                        </a>
                    </div>

                    <div class="border-t border-gray-100 py-1">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6 lg:p-8">
        @yield('content')
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const timeEl = document.getElementById('realtime-clock');
        const dateEl = document.getElementById('realtime-date');
        if (timeEl) timeEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        if (dateEl) dateEl.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    }
    updateClock();
    setInterval(updateClock, 1000);

    function toggleProfileDropdown() {
        document.getElementById('profileDropdownMenu').classList.toggle('open');
    }

    document.addEventListener('click', function(e) {
        const dropdown = document.querySelector('.profile-dropdown');
        if (!dropdown.contains(e.target)) {
            document.getElementById('profileDropdownMenu').classList.remove('open');
        }
    });
</script>

@stack('scripts')
</body>
</html>