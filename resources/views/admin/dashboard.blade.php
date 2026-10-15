@extends('admin.layout')

@section('title', 'Dashboard Overview')
@section('breadcrumb', 'Overview')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Welcome / Hero Card (High Contrast Executive Dark Blue Gradient) -->
    <div class="relative overflow-hidden rounded-3xl p-8 sm:p-10 text-white shadow-xl shadow-blue-900/10 border border-blue-600/30" style="background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #2563eb 100%) !important; color: #ffffff !important;">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="absolute right-1/3 -bottom-20 h-48 w-48 rounded-full bg-blue-400/20 blur-xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full px-3.5 py-1 text-xs font-bold tracking-wide border mb-4" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; border-color: rgba(255, 255, 255, 0.25);">
                    <i class="fa-solid fa-sparkles text-amber-300"></i>
                    <span>Control Center &bull; {{ date('l, d F Y') }}</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white" style="color: #ffffff !important;">
                    Selamat Datang, {{ Auth::user()->name ?? 'Admin Sekolah' }}! 👋
                </h2>
                <p class="mt-2 text-sm leading-relaxed max-w-2xl" style="color: #dbeafe !important;">
                    Kelola seluruh konten utama, publikasi berita, statistik prestasi, program keahlian, dan manajemen data sekolah SMK Muhammadiyah 2 Genteng.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ url('/') }}" target="_blank" class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all shadow-md flex items-center gap-2 hover:opacity-95" style="background: #ffffff !important; color: #1d4ed8 !important;">
                    <i class="fa-solid fa-globe"></i>
                    Lihat Web Publik
                </a>
                <a href="{{ route('admin.news.create') }}" class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 border hover:bg-white/20" style="background: rgba(255, 255, 255, 0.18) !important; color: #ffffff !important; border-color: rgba(255, 255, 255, 0.35) !important;">
                    <i class="fa-solid fa-plus"></i>
                    Tulis Berita Baru
                </a>
            </div>
        </div>
    </div>

    <!-- 4 KPI Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- News Stat -->
        <div class="admin-card p-6 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-xl shadow-sm" style="background: #eff6ff; color: #2563eb;">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe;">
                    Portal
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Berita</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $newsCount }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('admin.news.index') }}" class="font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group-hover:gap-1.5 transition-all">
                    Kelola Berita <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Programs Stat -->
        <div class="admin-card p-6 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-xl shadow-sm" style="background: #faf5ff; color: #9333ea;">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold" style="background: #faf5ff; color: #7e22ce; border: 1px solid #f3e8ff;">
                    Jurusan
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Program Keahlian</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $programCount }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('admin.programs.index') }}" class="font-bold text-purple-600 hover:text-purple-700 flex items-center gap-1 group-hover:gap-1.5 transition-all">
                    Lihat Program <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Teachers Stat -->
        <div class="admin-card p-6 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-xl shadow-sm" style="background: #fffbeb; color: #d97706;">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold" style="background: #fffbeb; color: #b45309; border: 1px solid #fef3c7;">
                    Civitas
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Guru & Tenaga Kerja</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $teacherCount }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('admin.teachers.index') }}" class="font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1 group-hover:gap-1.5 transition-all">
                    Daftar Guru <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Achievements Stat -->
        <div class="admin-card p-6 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-xl shadow-sm" style="background: #f0fdf4; color: #16a34a;">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold" style="background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7;">
                    Prestasi
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Item Prestasi</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $achievementCount ?? 0 }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('admin.prestasi.index') }}" class="font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 group-hover:gap-1.5 transition-all">
                    Kelola Prestasi <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid: Recent News + Quick Shortcuts -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Activities / News Table -->
        <div class="lg:col-span-8">
            <div class="admin-card overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                    <div>
                        <h3 class="font-bold text-slate-900 flex items-center gap-2 text-base">
                            <i class="fa-solid fa-newspaper text-blue-600"></i>
                            Berita & Artikel Terbaru
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar publikasi artikel terakhir yang ada di website</p>
                    </div>
                    <a href="{{ route('admin.news.index') }}" class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200 transition-all shadow-sm">
                        Lihat Semua
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/40">
                                <th class="px-6 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Judul Berita</th>
                                <th class="px-6 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($recentNews ?? [] as $news)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-2 w-2 rounded-full {{ ($news->is_published ?? false) ? 'bg-emerald-500' : 'bg-amber-400' }}"></div>
                                        <a href="{{ route('admin.news.edit', $news->id) }}" class="font-bold text-slate-800 hover:text-blue-600 transition-colors line-clamp-1">
                                            {{ $news->title }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($news->is_published ?? false)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase">
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 uppercase">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-slate-400 font-medium">
                                    {{ \Carbon\Carbon::parse($news->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-slate-400 text-sm italic">
                                    Belum ada berita yang diterbitkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Shortcuts & System Status -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Quick Menu Shortcuts -->
            <div class="admin-card p-6">
                <h3 class="font-bold text-slate-900 flex items-center gap-2 text-base mb-4">
                    <i class="fa-solid fa-bolt text-amber-500"></i>
                    Akses Cepat
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.prestasi.index') }}" class="p-3.5 rounded-2xl bg-slate-50 hover:bg-blue-50 border border-slate-200/80 hover:border-blue-200 transition-all text-left group">
                        <div class="h-8 w-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-trophy"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-800">Prestasi</div>
                        <div class="text-[10px] text-slate-400">Atur highlight</div>
                    </a>

                    <a href="{{ route('admin.programs.index') }}" class="p-3.5 rounded-2xl bg-slate-50 hover:bg-purple-50 border border-slate-200/80 hover:border-purple-200 transition-all text-left group">
                        <div class="h-8 w-8 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-800">Jurusan</div>
                        <div class="text-[10px] text-slate-400">5 Konsentrasi</div>
                    </a>

                    <a href="{{ route('admin.profile.index') }}" class="p-3.5 rounded-2xl bg-slate-50 hover:bg-emerald-50 border border-slate-200/80 hover:border-emerald-200 transition-all text-left group">
                        <div class="h-8 w-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-800">Profil</div>
                        <div class="text-[10px] text-slate-400">Visi, Misi, Sejarah</div>
                    </a>

                    <a href="{{ route('admin.visitors.index') }}" class="p-3.5 rounded-2xl bg-slate-50 hover:bg-emerald-50 border border-slate-200/80 hover:border-emerald-200 transition-all text-left group">
                        <div class="h-8 w-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-800">Pengunjung</div>
                        <div class="text-[10px] text-slate-400">Trafik & Analytics</div>
                    </a>

                    <a href="{{ route('admin.gallery.index') }}" class="p-3.5 rounded-2xl bg-slate-50 hover:bg-pink-50 border border-slate-200/80 hover:border-pink-200 transition-all text-left group">
                        <div class="h-8 w-8 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-images"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-800">Galeri</div>
                        <div class="text-[10px] text-slate-400">Dokumentasi</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
