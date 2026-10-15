<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'STEMDA') }} Admin - @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">

    @vite('resources/css/app.pcss')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .sidebar-item-active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            box-shadow: 0 4px 14px -2px rgba(37, 99, 235, 0.45) !important;
            color: #ffffff !important;
        }
        .sidebar-item-active i {
            color: #ffffff !important;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .admin-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.04), 0 1px 2px -1px rgb(0 0 0 / 0.04);
            transition: all 0.25s ease;
        }
        .admin-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.06), 0 8px 10px -6px rgb(0 0 0 / 0.04);
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.875rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px -2px rgba(37, 99, 235, 0.35);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px -2px rgba(37, 99, 235, 0.45);
        }
        .btn-danger {
            background: #fef2f2;
            color: #dc2626;
            padding: 0.625rem 1.25rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.875rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #fecaca;
        }
        .btn-danger:hover {
            background: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>
<body class="m-0 font-sans text-slate-800 bg-slate-100 custom-scrollbar overflow-x-hidden antialiased min-h-screen">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-200/80 z-50 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 flex flex-col shadow-xl lg:shadow-none"
    >
        <!-- Logo Area -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="h-11 w-11 rounded-2xl bg-gradient-to-tr from-blue-700 to-blue-500 p-0.5 shadow-md shadow-blue-500/20 flex items-center justify-center">
                    <div class="h-full w-full bg-white rounded-[14px] flex items-center justify-center">
                        <i class="fa-solid fa-graduation-cap text-blue-600 text-lg"></i>
                    </div>
                </div>
                <div>
                    <span class="font-extrabold text-lg tracking-tight text-slate-900 block leading-tight">STEMDA</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Admin Control</span>
                </div>
            </a>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                v2.0
            </span>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto px-4 py-4 custom-scrollbar space-y-6">
            <nav class="space-y-1">
                <p class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Main Dashboard</p>

                @can('admin.dashboard.view')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.dashboard') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chart-pie w-5 text-center text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.visitors.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.visitors.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chart-line w-5 text-center text-sm {{ request()->routeIs('admin.visitors.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Statistik Pengunjung</span>
                    </a>
                @endcan

                <p class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2 pt-4">Content & Media</p>

                @can('admin.home.view')
                    <a href="{{ route('admin.home.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.home.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-house w-5 text-center text-sm {{ request()->routeIs('admin.home.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Home & Banner</span>
                    </a>
                @endcan

                @can('admin.profile.view')
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.profile.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-id-card w-5 text-center text-sm {{ request()->routeIs('admin.profile.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Profil Sekolah</span>
                    </a>
                @endcan

                @can('admin.news.view')
                    <a href="{{ route('admin.news.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.news.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-newspaper w-5 text-center text-sm {{ request()->routeIs('admin.news.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Berita & Artikel</span>
                    </a>
                @endcan

                @can('admin.programs.view')
                    <a href="{{ route('admin.programs.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.programs.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-graduation-cap w-5 text-center text-sm {{ request()->routeIs('admin.programs.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Program Keahlian</span>
                    </a>
                @endcan

                @can('admin.waka.view')
                    <a href="{{ route('admin.waka.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.waka.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-user-tie w-5 text-center text-sm {{ request()->routeIs('admin.waka.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>WAKA & Manajemen</span>
                    </a>
                @endcan

                @canany(['admin.teachers.view', 'admin.teacher_categories.view'])
                    <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.teachers.*') || request()->routeIs('admin.teacher-categories.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chalkboard-user w-5 text-center text-sm {{ request()->routeIs('admin.teachers.*') || request()->routeIs('admin.teacher-categories.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Guru & Tenaga Kerja</span>
                    </a>
                @endcanany

                @can('admin.gallery.view')
                    <a href="{{ route('admin.gallery.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.gallery.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-images w-5 text-center text-sm {{ request()->routeIs('admin.gallery.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Galeri Foto</span>
                    </a>
                @endcan

                @can('admin.facilities.view')
                    <a href="{{ route('admin.facilities.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.facilities.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-building w-5 text-center text-sm {{ request()->routeIs('admin.facilities.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Fasilitas Sekolah</span>
                    </a>
                @endcan

                @can('admin.system_information.view')
                    <a href="{{ route('admin.system-information.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.system-information.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-network-wired w-5 text-center text-sm {{ request()->routeIs('admin.system-information.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Portal</span>
                    </a>
                @endcan

                @can('admin.tefa.view')
                    <a href="{{ route('admin.tefa.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.tefa.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-cubes w-5 text-center text-sm {{ request()->routeIs('admin.tefa.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Teaching Factory</span>
                    </a>
                @endcan

                <p class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2 pt-4">Events & Interaksi</p>

                @can('admin.prestasi.view')
                    <a href="{{ route('admin.prestasi.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.prestasi.*') || request()->routeIs('admin.achievements.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-trophy w-5 text-center text-sm {{ request()->routeIs('admin.prestasi.*') || request()->routeIs('admin.achievements.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Prestasi & Lomba</span>
                    </a>
                @endcan

                @can('admin.agendas.view')
                    <a href="{{ route('admin.agendas.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.agendas.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-calendar-days w-5 text-center text-sm {{ request()->routeIs('admin.agendas.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Agenda & Kegiatan</span>
                    </a>
                @endcan

                @can('admin.contacts.view')
                    <a href="{{ route('admin.contacts.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.contacts.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-address-card w-5 text-center text-sm {{ request()->routeIs('admin.contacts.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span>Informasi PPDB</span>
                    </a>
                @endcan

                @canany(['admin.users.view', 'admin.roles.view'])
                    <p class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2 pt-4">Sistem & Keamanan</p>

                    @can('admin.users.view')
                        <a href="{{ route('admin.access.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.access.users.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-users-gear w-5 text-center text-sm {{ request()->routeIs('admin.access.users.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                            <span>Admin Users</span>
                        </a>
                    @endcan

                    @can('admin.roles.view')
                        <a href="{{ route('admin.access.roles.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.access.roles.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-shield-halved w-5 text-center text-sm {{ request()->routeIs('admin.access.roles.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                            <span>Hak Akses (Roles)</span>
                        </a>
                    @endcan

                    @can('admin.backup.view')
                        <a href="{{ route('admin.backup.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group font-semibold text-sm {{ request()->routeIs('admin.backup.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-database w-5 text-center text-sm {{ request()->routeIs('admin.backup.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                            <span>Database Backup</span>
                        </a>
                    @endcan
                @endcanany
            </nav>
        </div>

        <!-- Footer / Logout -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/60">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl text-slate-600 font-bold text-xs hover:bg-red-50 hover:text-red-600 transition-all group border border-slate-200/60 bg-white shadow-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket text-slate-400 group-hover:text-red-600 text-xs"></i>
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <main class="lg:ml-72 min-h-screen flex flex-col bg-slate-100">
        <!-- Top Navbar -->
        <header class="h-16 bg-white sticky top-0 z-30 px-6 sm:px-8 flex items-center justify-between border-b border-slate-200/90 shadow-sm">
            <div class="flex items-center gap-3">
                <button id="sidebar-toggle" class="lg:hidden p-2 rounded-xl bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200 transition-colors">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-400">Admin</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                    <span class="text-xs font-extrabold text-blue-600">@yield('breadcrumb', 'Overview')</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <!-- Preview Live Website Button -->
                <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-blue-50 text-slate-600 hover:text-blue-600 font-bold text-xs border border-slate-200 hover:border-blue-200 transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                    <span class="hidden sm:inline">Web Publik</span>
                </a>

                <div class="h-5 w-px bg-slate-200"></div>

                <!-- Admin Profile Pill -->
                <div class="flex items-center gap-2.5">
                    <div class="text-right hidden sm:block">
                        <span class="block text-xs font-bold text-slate-800 leading-none">{{ Auth::user()->name ?? 'Admin STEMDA' }}</span>
                        <span class="block text-[10px] text-slate-400 font-medium mt-0.5">{{ Auth::user()->email ?? 'admin@gmail.com' }}</span>
                    </div>
                    <div class="h-8 w-8 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 p-0.5 shadow-sm">
                        <div class="h-full w-full bg-white rounded-[9px] flex items-center justify-center font-black text-xs text-blue-600 uppercase">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Body -->
        <div class="flex-1 p-6 sm:p-8 animate-fade-in max-w-7xl w-full mx-auto">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="p-6 text-center text-xs font-semibold text-slate-400 border-t border-slate-200/60 bg-white/50">
            &copy; {{ date('Y') }} SMK Muhammadiyah 2 Genteng. Admin Dashboard System.
        </footer>
    </main>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.NProgress) {
                NProgress.configure({ showSpinner: false, trickleSpeed: 120, minimum: 0.08 });
                NProgress.done(true);
            }

            if (window.lucide) {
                try {
                    lucide.createIcons();
                } catch (e) {}
            }

            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggle = document.getElementById('sidebar-toggle');

            function toggleSidebar() {
                if (!sidebar || !overlay) return;
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }

            toggle?.addEventListener('click', toggleSidebar);
            overlay?.addEventListener('click', toggleSidebar);
        });
    </script>
</body>
</html>
