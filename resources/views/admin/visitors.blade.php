@extends('admin.layout')

@section('title', 'Statistik Pengunjung')
@section('breadcrumb', 'Pengunjung')
@section('page-title', 'Statistik Pengunjung Website')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl p-8 text-white shadow-xl shadow-blue-900/10 border border-blue-600/30" style="background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #2563eb 100%) !important; color: #ffffff !important;">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full px-3.5 py-1 text-xs font-bold tracking-wide border mb-3" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; border-color: rgba(255, 255, 255, 0.25);">
                    <i class="fa-solid fa-chart-line text-emerald-300"></i>
                    <span>Real-time Traffic Analytics</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white" style="color: #ffffff !important;">
                    Statistik & Pelacak Pengunjung Website 📊
                </h2>
                <p class="mt-2 text-sm leading-relaxed max-w-2xl" style="color: #dbeafe !important;">
                    Pantau jumlah orang yang mengakses website sekolah, halaman paling populer, tren kunjungan harian, dan jenis perangkat pengunjung secara akurat.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white/15 border border-white/30 backdrop-blur-md">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Tracker Aktif</span>
                </div>
                <a href="{{ url('/') }}" target="_blank" class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all shadow-md flex items-center gap-2" style="background: #ffffff !important; color: #1d4ed8 !important;">
                    <i class="fa-solid fa-globe"></i>
                    Buka Web Publik
                </a>
            </div>
        </div>
    </div>

    <!-- 4 KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Hari Ini -->
        <div class="admin-card p-6 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-xl shadow-sm" style="background: #eff6ff; color: #2563eb;">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe;">
                    Hari Ini
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengunjung Hari Ini</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($todayUniqueVisitors) }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Total {{ number_format($todayPageviews) }} tayangan</span>
                @if($growthRate > 0)
                    <span class="font-bold text-emerald-600 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-trend-up"></i> +{{ $growthRate }}%
                    </span>
                @elseif($growthRate < 0)
                    <span class="font-bold text-slate-400 flex items-center gap-1">
                        {{ $growthRate }}%
                    </span>
                @else
                    <span class="font-bold text-slate-400">Stabil</span>
                @endif
            </div>
        </div>

        <!-- Bulan Ini -->
        <div class="admin-card p-6 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-xl shadow-sm" style="background: #faf5ff; color: #9333ea;">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold" style="background: #faf5ff; color: #7e22ce; border: 1px solid #f3e8ff;">
                    Bulan Ini
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengunjung Bulan Ini</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($thisMonthUniqueVisitors) }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Periode {{ date('F Y') }}</span>
                <span class="font-bold text-purple-600">Unik</span>
            </div>
        </div>

        <!-- Total Pengunjung Unik -->
        <div class="admin-card p-6 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-xl shadow-sm" style="background: #f0fdf4; color: #16a34a;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold" style="background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7;">
                    All-Time
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengunjung Unik</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalUniqueVisitors) }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Akumulasi IP Unik</span>
                <span class="font-bold text-emerald-600">Total User</span>
            </div>
        </div>

        <!-- Total Pageviews -->
        <div class="admin-card p-6 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-xl shadow-sm" style="background: #fffbeb; color: #d97706;">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold" style="background: #fffbeb; color: #b45309; border: 1px solid #fef3c7;">
                    Pageviews
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Tayangan Halaman</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalPageviews) }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Akses Seluruh Halaman</span>
                <span class="font-bold text-amber-600">Total Klik</span>
            </div>
        </div>
    </div>

    <!-- Chart: Tren Kunjungan 14 Hari Terakhir -->
    <div class="admin-card p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-chart-area text-blue-600"></i>
                    Grafik Tren Kunjungan Harian (14 Hari Terakhir)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbandingan jumlah pengunjung unik dan total tayangan halaman setiap hari</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-blue-600"></span>
                    <span class="text-slate-600">Tayangan (Pageviews)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                    <span class="text-slate-600">Pengunjung Unik</span>
                </div>
            </div>
        </div>

        <div class="relative w-full" style="height: 320px;">
            <canvas id="visitorChart"></canvas>
        </div>
    </div>

    <!-- 2 Columns: Top Pages & Device Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Halaman Paling Sering Dikunjungi -->
        <div class="lg:col-span-7">
            <div class="admin-card p-6 sm:p-7 h-full flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-fire text-amber-500"></i>
                            Halaman Paling Sering Dikunjungi
                        </h3>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Top Pages</span>
                    </div>

                    <div class="space-y-4">
                        @forelse($topPages as $index => $page)
                        @php
                            $barWidth = round(($page->views / $maxPageView) * 100);
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1.5">
                                <div class="flex items-center gap-2 truncate pr-2">
                                    <span class="h-5 w-5 rounded-md bg-slate-100 text-slate-600 flex items-center justify-center text-[10px] shrink-0 font-extrabold">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="truncate">{{ $page->page_title ?: $page->url }}</span>
                                    <span class="text-[10px] font-mono text-slate-400 font-normal">({{ $page->url }})</span>
                                </div>
                                <span class="font-extrabold text-blue-600 shrink-0">{{ number_format($page->views) }} view</span>
                            </div>
                            <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-500" style="width: {{ max($barWidth, 4) }}%;"></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 italic py-6 text-center">Belum ada data kunjungan halaman yang tercatat.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribusi Perangkat & Browser -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Perangkat -->
            <div class="admin-card p-6">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-mobile-screen-button text-purple-600"></i>
                    Jenis Perangkat Pengunjung
                </h3>

                <div class="space-y-3.5">
                    @forelse($deviceBreakdown as $dev)
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                            <span class="flex items-center gap-2">
                                @if($dev['device'] === 'Mobile')
                                    <i class="fa-solid fa-mobile-screen text-blue-500"></i> Smartphone (HP)
                                @elseif($dev['device'] === 'Tablet')
                                    <i class="fa-solid fa-tablet-screen-button text-purple-500"></i> Tablet / iPad
                                @else
                                    <i class="fa-solid fa-laptop text-emerald-500"></i> Desktop / Laptop
                                @endif
                            </span>
                            <span class="font-extrabold text-slate-900">{{ $dev['percentage'] }}% <span class="text-slate-400 font-normal text-[11px]">({{ number_format($dev['count']) }})</span></span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full {{ $dev['device'] === 'Mobile' ? 'bg-blue-500' : ($dev['device'] === 'Tablet' ? 'bg-purple-500' : 'bg-emerald-500') }}" style="width: {{ $dev['percentage'] }}%;"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic text-center py-4">Belum ada data perangkat.</p>
                    @endforelse
                </div>
            </div>

            <!-- Top Browser -->
            <div class="admin-card p-6">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2 mb-4">
                    <i class="fa-brands fa-chrome text-blue-500"></i>
                    Browser Pengunjung
                </h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($browsers as $brw)
                    <div class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center gap-2 text-xs">
                        <i class="fa-solid fa-globe text-slate-400"></i>
                        <span class="font-bold text-slate-700">{{ $brw->browser }}</span>
                        <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded-md bg-blue-50 text-blue-700">{{ number_format($brw->count) }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic">Belum ada data browser.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Log Pengunjung Terbaru -->
    <div class="admin-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600"></i>
                    Riwayat Kunjungan Real-Time (Terbaru)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar aktivitas akses pengunjung terakhir di website sekolah</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Live Logging
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/40 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-3.5">Halaman Yang Diakses</th>
                        <th class="px-6 py-3.5">Perangkat & Browser</th>
                        <th class="px-6 py-3.5">Sistem Operasi</th>
                        <th class="px-6 py-3.5">Alamat IP</th>
                        <th class="px-6 py-3.5 text-right">Waktu Kunjungan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($recentLogs as $log)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-3.5">
                            <div class="font-bold text-slate-800">{{ $log->page_title ?: 'Halaman Publik' }}</div>
                            <div class="text-[11px] font-mono text-slate-400 mt-0.5">{{ $log->url }}</div>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold {{ $log->device === 'Mobile' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">
                                    {{ $log->device }}
                                </span>
                                <span class="font-semibold text-slate-700">{{ $log->browser }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="font-medium text-slate-600">{{ $log->platform }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="font-mono text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">{{ $log->ip_address }}</span>
                        </td>
                        <td class="px-6 py-3.5 text-right text-slate-400 font-medium whitespace-nowrap">
                            {{ $log->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">
                            Belum ada riwayat kunjungan yang tercatat. Buka halaman depan website untuk memicu log pertama!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentLogs->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/40">
            {{ $recentLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('visitorChart');
        if (!ctx) return;

        const labels = {!! json_encode($chartLabels) !!};
        const visitorsData = {!! json_encode($chartVisitorsData) !!};
        const pageviewsData = {!! json_encode($chartPageviewsData) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Tayangan Halaman (Pageviews)',
                        data: pageviewsData,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Pengunjung Unik',
                        data: visitorsData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                        bodyFont: { size: 12, family: "'Plus Jakarta Sans', sans-serif" },
                        padding: 12,
                        cornerRadius: 12,
                        boxPadding: 6
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 11, weight: '600', family: "'Plus Jakarta Sans', sans-serif" },
                            color: '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" },
                            color: '#64748b'
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
