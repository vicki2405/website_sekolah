<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    Images,
    Trophy,
    BookOpen,
    Newspaper,
    Calendar,
    Users,
    Image,
    Mail,
    ArrowUpRight,
    Plus,
    Activity,
    ShieldCheck,
    ChevronRight,
    Link as LinkIcon,
    Sparkles,
    UserCircle
} from 'lucide-vue-next';

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            news: 0,
            programs: 0,
            teachers: 0,
            contacts: 0,
            gallery: 0,
            achievements: 0,
            agendas: 0
        })
    },
    recent_news: {
        type: Array,
        default: () => []
    }
});

const cards = [
    { title: 'Slider & Home', desc: 'Kelola banner dan konten beranda.', href: () => route('admin.home.index'), icon: Images, color: 'blue' },
    { title: 'Prestasi', desc: 'Kelola data prestasi dan penghargaan.', href: () => route('admin.prestasi.index'), icon: Trophy, color: 'amber' },
    { title: 'Program Keahlian', desc: 'Kelola jurusan dan kurikulum.', href: () => route('admin.programs.index'), icon: BookOpen, color: 'purple' },
    { title: 'Berita Sekolah', desc: 'Posting berita & pengumuman terbaru.', href: () => route('admin.news.index'), icon: Newspaper, color: 'emerald' },
    { title: 'Agenda Kegiatan', desc: 'Atur jadwal event dan agenda.', href: () => route('admin.agendas.index'), icon: Calendar, color: 'rose' },
    { title: 'Data Guru', desc: 'Kelola profil pengajar dan staff.', href: () => route('admin.teachers.index'), icon: Users, color: 'indigo' },
    { title: 'Galeri Foto', desc: 'Koleksi dokumentasi kegiatan.', href: () => route('admin.gallery.index'), icon: Image, color: 'pink' },
    { title: 'Pesan Masuk', desc: 'Lihat feedback dari pengunjung.', href: () => route('admin.contacts.index'), icon: Mail, color: 'cyan' },
    { title: 'Portal', icon: LinkIcon, desc: 'Kelola link menu eksternal.', href: () => route('admin.system-information.index'), color: 'slate' },
];

const statCards = [
    { label: 'Total Berita', value: props.stats.news, icon: Newspaper, color: 'text-blue-600', bg: 'bg-blue-100' },
    { label: 'Program Studi', value: props.stats.programs, icon: BookOpen, color: 'text-purple-600', bg: 'bg-purple-100' },
    { label: 'Total Pengajar', value: props.stats.teachers, icon: Users, color: 'text-emerald-600', bg: 'bg-emerald-100' },
    { label: 'Pesan Baru', value: props.stats.contacts, icon: Mail, color: 'text-rose-600', bg: 'bg-rose-100' },
];
</script>

<template>
    <Head title="Dashboard Overview" />

    <AdminLayout title="Dashboard" breadcrumb="Overview">
        <div class="space-y-8 pb-12">
            <!-- Welcome Section -->
            <div 
                v-motion
                :initial="{ opacity: 0, y: 20 }"
                :enter="{ opacity: 1, y: 0 }"
                class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 sm:p-12 text-white shadow-2xl"
            >
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-blue-500/20 text-blue-300 text-[10px] font-bold uppercase tracking-wider rounded-full border border-blue-500/30">Admin Dashboard</span>
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider">System Active</span>
                        </div>
                        <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                            Selamat Datang, <span class="text-blue-400">{{ $page.props.auth.user.name }}</span>!
                        </h1>
                        <p class="mt-4 text-slate-400 text-sm sm:text-lg leading-relaxed font-medium">
                            Kelola seluruh konten website sekolah dengan mudah. Perubahan yang Anda lakukan akan langsung tampil di halaman publik.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <Link :href="route('home')" target="_blank" class="inline-flex items-center gap-2 rounded-2xl px-6 py-3 bg-white text-slate-900 hover:bg-slate-100 text-sm font-bold transition-all active:scale-95 shadow-lg">
                                Lihat Website
                                <ArrowUpRight class="h-4 w-4" />
                            </Link>
                            <Link :href="route('admin.news.create')" class="inline-flex items-center gap-2 rounded-2xl px-6 py-3 bg-blue-600 text-white hover:bg-blue-700 text-sm font-bold transition-all active:scale-95 shadow-lg shadow-blue-600/20">
                                <Plus class="h-4 w-4" />
                                Buat Berita Baru
                            </Link>
                        </div>
                    </div>
                    <div class="hidden xl:block">
                        <div class="relative">
                            <div class="absolute -inset-4 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
                            <Activity class="h-32 w-32 text-blue-500/50 relative z-10" />
                        </div>
                    </div>
                </div>
                
                <!-- Background Decorations -->
                <div class="absolute -right-20 -top-20 h-64 w-64 bg-blue-600/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 h-64 w-64 bg-purple-600/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div 
                    v-for="(stat, idx) in statCards" 
                    :key="idx"
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :enter="{ opacity: 1, y: 0, transition: { delay: 100 * idx } }"
                    class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div :class="[stat.bg, 'h-12 w-12 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500 shadow-sm']">
                            <component :is="stat.icon" :class="[stat.color, 'h-6 w-6']" />
                        </div>
                        <ChevronRight class="h-4 w-4 text-slate-300 group-hover:translate-x-1 transition-transform" />
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ stat.label }}</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-1">{{ stat.value }}</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Quick Navigation Cards -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-xl font-black text-slate-900 flex items-center gap-3">
                            <Sparkles class="h-5 w-5 text-blue-500" />
                            Manajemen Konten
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <Link
                            v-for="(card, idx) in cards"
                            :key="card.title"
                            :href="card.href()"
                            v-motion
                            :initial="{ opacity: 0, scale: 0.95 }"
                            :enter="{ opacity: 1, scale: 1, transition: { delay: 50 * idx } }"
                            class="group relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-6 transition-all duration-300 hover:shadow-2xl hover:border-blue-200"
                        >
                            <!-- Hover Background Effect -->
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 bg-gradient-to-br from-blue-50/50 via-transparent to-purple-50/50"></div>

                            <div class="relative flex items-start gap-4">
                                <div class="h-14 w-14 rounded-2xl bg-slate-50 border border-slate-100 grid place-items-center shrink-0 group-hover:bg-white group-hover:shadow-lg transition-all duration-500">
                                    <component :is="card.icon" class="h-7 w-7 text-slate-600 group-hover:text-blue-600 transition-colors" />
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ card.title }}</h3>
                                        <ArrowUpRight class="h-4 w-4 text-slate-300 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all" />
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500 leading-relaxed line-clamp-2">
                                        {{ card.desc }}
                                    </p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Recent Activities & Side Cards -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Recent News -->
                    <div 
                        v-motion
                        :initial="{ opacity: 0, x: 20 }"
                        :enter="{ opacity: 1, x: 0 }"
                        class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden"
                    >
                        <div class="p-6 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                <Newspaper class="h-4 w-4 text-blue-500" />
                                Berita Terbaru
                            </h3>
                            <Link :href="route('admin.news.index')" class="text-[10px] font-bold text-blue-600 uppercase tracking-wider hover:text-blue-700">Lihat Semua</Link>
                        </div>
                        <div class="divide-y divide-slate-50">
                            <div v-for="news in recent_news" :key="news.id" class="p-5 hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="h-1.5 w-1.5 rounded-full bg-blue-500"></div>
                                    <p class="text-xs font-bold text-slate-700 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ news.title }}</p>
                                </div>
                                <div class="flex items-center justify-between mt-2 ml-4.5">
                                    <span class="text-[10px] font-medium text-slate-400 italic">{{ news.created_at_human || 'Baru saja' }}</span>
                                    <span v-if="news.is_published" class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Published</span>
                                </div>
                            </div>
                            <div v-if="recent_news.length === 0" class="p-10 text-center">
                                <p class="text-xs text-slate-400 font-medium italic">Belum ada berita.</p>
                            </div>
                        </div>
                    </div>

                    <!-- System Info Card -->
                    <div 
                        v-motion
                        :initial="{ opacity: 0, x: 20 }"
                        :enter="{ opacity: 1, x: 0, transition: { delay: 200 } }"
                        class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-200/50 relative overflow-hidden group"
                    >
                        <div class="relative z-10">
                            <div class="h-12 w-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-md">
                                <ShieldCheck class="h-6 w-6 text-white" />
                            </div>
                            <h4 class="font-bold text-xl mb-2">Keamanan Sistem</h4>
                            <p class="text-blue-100 text-xs leading-relaxed mb-6">Sistem Anda dipantau secara otomatis untuk memastikan performa maksimal dan keamanan data.</p>
                            
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest bg-white/10 p-3 rounded-xl border border-white/10">
                                    <div class="h-2 w-2 rounded-full bg-emerald-400"></div>
                                    Auto Backup Active
                                </div>
                                <div class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest bg-white/10 p-3 rounded-xl border border-white/10">
                                    <div class="h-2 w-2 rounded-full bg-emerald-400"></div>
                                    SSL Encryption Secure
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative Circle -->
                        <div class="absolute -right-10 -bottom-10 h-40 w-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.font-black {
    font-weight: 900;
}
.font-display {
    font-family: 'Figtree', sans-serif;
}
</style>
