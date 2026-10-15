<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import {
    Activity,
    Award,
    BadgeCheck,
    BookOpen,
    Building2,
    Calendar,
    CheckCircle2,
    ChevronRight,
    Compass,
    Cpu,
    ExternalLink,
    Filter,
    Flame,
    GraduationCap,
    HeartHandshake,
    Layers,
    Lightbulb,
    Maximize2,
    Medal,
    Music,
    Radio,
    School,
    Search,
    Shield,
    ShieldCheck,
    Sparkles,
    Star,
    Target,
    Trophy,
    UserCheck,
    Users,
    Wrench,
    Zap,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps({
    achievements: {
        type: Array,
        default: () => [],
    },
    page: {
        type: Object,
        default: () => ({}),
    },
});

// Search & Filter State
const searchQuery = ref('');
const selectedCategory = ref('all');

// Categories
const categories = [
    { id: 'all', label: 'Semua Prestasi', icon: Trophy },
    { id: 'lomba', label: 'Kejuaraan Siswa', icon: Medal },
    { id: 'kejuruan', label: 'LKS & Vokasi', icon: Wrench },
    { id: 'religius', label: 'Seni & Keagamaan', icon: Sparkles },
    { id: 'statistik', label: 'Statistik Sekolah', icon: School },
];

// Dynamic Icon Resolver
const iconMap = {
    trophy: Trophy,
    medal: Medal,
    users: Users,
    school: School,
    'graduation-cap': GraduationCap,
    'chalkboard-teacher': GraduationCap,
    'check-circle': CheckCircle2,
    award: Award,
    sparkles: Sparkles,
    wrench: Wrench,
    shield: Shield,
    heart: HeartHandshake,
    zap: Zap,
    cpu: Cpu,
    music: Music,
};

const getIcon = (name) => {
    if (!name) return Trophy;
    const lower = String(name).toLowerCase().trim();
    return iconMap[lower] ?? Trophy;
};

// Filtered Achievements
const filteredAchievements = computed(() => {
    const list = Array.isArray(props.achievements) ? props.achievements : [];
    const query = searchQuery.value.toLowerCase().trim();
    const cat = selectedCategory.value;

    return list.filter((item) => {
        const title = (item.title || '').toLowerCase();
        const desc = (item.description || '').toLowerCase();
        const val = (item.value || '').toLowerCase();

        // Search text match
        const matchesQuery = !query || title.includes(query) || desc.includes(query) || val.includes(query);
        if (!matchesQuery) return false;

        // Category filter match
        if (cat === 'all') return true;

        if (cat === 'statistik') {
            return !item.image && (val.includes('+') || !isNaN(Number(val)));
        }
        if (cat === 'lomba') {
            return title.includes('juara') || desc.includes('juara') || desc.includes('lomba') || !!item.image;
        }
        if (cat === 'kejuruan') {
            return title.includes('lks') || desc.includes('otomotif') || desc.includes('elektronika') || desc.includes('kejuruan') || desc.includes('animasi') || desc.includes('kuliner');
        }
        if (cat === 'religius') {
            return title.includes('adzan') || title.includes('fashmu') || desc.includes('shalat') || desc.includes('qur') || desc.includes('seni') || desc.includes('tapak suci');
        }

        return true;
    });
});

// Dynamic Stats from Admin Settings
const activeStats = computed(() => {
    if (Array.isArray(props.page?.stats) && props.page.stats.length > 0) {
        return props.page.stats;
    }
    return [];
});

const colorThemeMap = {
    amber: { iconBg: 'bg-amber-50 border-amber-200 text-amber-600', text: 'text-slate-900 group-hover:text-amber-600' },
    brand: { iconBg: 'bg-brand-50 border-brand-200 text-brand-600', text: 'text-brand-600' },
    emerald: { iconBg: 'bg-emerald-50 border-emerald-200 text-emerald-600', text: 'text-emerald-600' },
    purple: { iconBg: 'bg-purple-50 border-purple-200 text-purple-600', text: 'text-purple-600' },
    rose: { iconBg: 'bg-rose-50 border-rose-200 text-rose-600', text: 'text-rose-600' },
    cyan: { iconBg: 'bg-cyan-50 border-cyan-200 text-cyan-600', text: 'text-cyan-600' },
};

const getStatColor = (colorName, idx) => {
    const defaultColors = ['amber', 'brand', 'emerald', 'purple', 'rose', 'cyan'];
    const resolvedColor = colorName || defaultColors[idx % defaultColors.length];
    return colorThemeMap[resolvedColor] || colorThemeMap.amber;
};

// Image preview modal state
const activePreviewImage = ref(null);
const activePreviewTitle = ref('');

const openPreview = (image, title) => {
    if (!image) return;
    activePreviewImage.value = image;
    activePreviewTitle.value = title || 'Dokumentasi Prestasi';
};

const closePreview = () => {
    activePreviewImage.value = null;
};
</script>

<template>
    <Head title="Prestasi & Kebanggaan Sekolah - SMKS Muhammadiyah 2 Genteng" />

    <MainLayout>
        <div class="min-h-screen bg-slate-50/50 text-slate-800 selection:bg-brand-500 selection:text-white">
            <!-- ================= HERO HEADER SECTION (VIBRANT STEMDA BLUE WITH PROPER TOP NAVBAR CLEARANCE) ================= -->
            <section class="relative bg-gradient-to-br from-brand-700 via-brand-600 to-sky-600 text-white pt-28 pb-16 sm:pt-32 sm:pb-20 md:pt-36 md:pb-28 overflow-hidden shadow-xl">
                <!-- Background Decorative SVG & Lighting -->
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 100 C 30 0 70 0 100 100 Z" fill="white"></path>
                    </svg>
                </div>
                <!-- Ambient Floating Light Orbs -->
                <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-white/15 blur-3xl pointer-events-none animate-pulse-slow"></div>
                <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-cyan-400/25 blur-3xl pointer-events-none animate-pulse-slow"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <!-- Breadcrumbs -->
                    <nav class="flex items-center gap-2 text-xs font-semibold text-brand-100/80 mb-5 sm:mb-6">
                        <Link :href="route('home')" class="hover:text-white transition-colors">Beranda</Link>
                        <ChevronRight class="w-3.5 h-3.5 text-brand-200/60" />
                        <span class="text-white font-bold">Prestasi & Kebanggaan</span>
                    </nav>

                    <div class="max-w-4xl space-y-4 sm:space-y-5">
                        <!-- Badges with glowing animation -->
                        <div class="inline-flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-white/15 border border-white/25 text-white text-[11px] sm:text-xs font-black uppercase tracking-wider backdrop-blur shadow-sm hover:scale-105 transition-transform">
                                <Trophy class="w-3.5 h-3.5 text-amber-300 animate-bounce-subtle" />
                                <span>Galeri Sang Juara</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-amber-400/20 border border-amber-300/30 text-white text-[11px] sm:text-xs font-black uppercase tracking-wider backdrop-blur shadow-sm hover:scale-105 transition-transform">
                                <Sparkles class="w-3.5 h-3.5 text-amber-300" />
                                <span>Budaya Prestasi STEMDA</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-emerald-400/20 border border-emerald-300/30 text-white text-[11px] sm:text-xs font-black uppercase tracking-wider backdrop-blur shadow-sm hover:scale-105 transition-transform">
                                <BadgeCheck class="w-3.5 h-3.5 text-emerald-300" />
                                <span>Akreditasi A Unggul</span>
                            </span>
                        </div>

                        <!-- Main Title -->
                        <h1 class="text-3xl sm:text-5xl md:text-6xl font-black text-white tracking-tight leading-[1.15]">
                            Prestasi & Jejak <span class="text-cyan-200">Sang Juara</span>
                        </h1>

                        <p class="text-brand-50 text-sm sm:text-base md:text-lg leading-relaxed max-w-3xl font-normal">
                            Dokumentasi torehan medali, piala penghargaan, dan pencapaian membanggakan siswa-siswi SMKS Muhammadiyah 2 Genteng di bidang kejuruan, akademik, olahraga, seni, dan keagamaan.
                        </p>
                    </div>
                </div>
            </section>

            <!-- ================= STATS TICKER (FLOATING METRIC CARDS - DYNAMIC FROM ADMIN SETTINGS) ================= -->
            <section v-if="activeStats.length > 0" class="relative -mt-6 sm:-mt-8 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="grid gap-3 sm:gap-4"
                    :class="{
                        'grid-cols-1': activeStats.length === 1,
                        'grid-cols-2': activeStats.length === 2,
                        'grid-cols-2 lg:grid-cols-3': activeStats.length === 3,
                        'grid-cols-2 lg:grid-cols-4': activeStats.length >= 4,
                    }"
                >
                    <div
                        v-for="(stat, sIdx) in activeStats"
                        :key="`stat-card-${sIdx}`"
                        class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xl shadow-slate-200/60 hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between group"
                    >
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <div
                                class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl border flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-sm"
                                :class="getStatColor(stat.color, sIdx).iconBg"
                            >
                                <component :is="getIcon(stat.icon)" class="w-5 h-5 sm:w-6 sm:h-6 animate-bounce-subtle" />
                            </div>
                            <span
                                class="text-xl sm:text-2xl font-black transition-colors"
                                :class="getStatColor(stat.color, sIdx).text"
                            >
                                {{ stat.value }}
                            </span>
                        </div>
                        <div>
                            <div class="text-[11px] sm:text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                                {{ stat.label }}
                            </div>
                            <div v-if="stat.sublabel" class="text-[10px] sm:text-[11px] font-medium text-slate-500 leading-tight mt-0.5">
                                {{ stat.sublabel }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= INTERACTIVE SEARCH & CATEGORY FILTER (TOUCH & SWIPE FRIENDLY) ================= -->
            <section class="pt-8 sm:pt-10 pb-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4 p-3.5 sm:p-5 rounded-2xl bg-white border border-slate-200/80 shadow-md">
                    <!-- Category Horizontal Scroll on Mobile -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1.5 md:pb-0 touch-pan-x snap-x [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                        <button
                            v-for="cat in categories"
                            :key="cat.id"
                            type="button"
                            @click="selectedCategory = cat.id"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-extrabold whitespace-nowrap shrink-0 snap-start transition-all duration-300"
                            :class="selectedCategory === cat.id
                                ? 'bg-brand-500 text-white shadow-md shadow-brand-500/25 scale-105'
                                : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200'"
                        >
                            <component :is="cat.icon" class="w-3.5 h-3.5" />
                            <span>{{ cat.label }}</span>
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="relative w-full md:w-72 shrink-0">
                        <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari prestasi / nama lomba..."
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                        />
                    </div>
                </div>
            </section>

            <!-- ================= ACHIEVEMENTS GRID SECTION ================= -->
            <section class="py-8 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="filteredAchievements.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <article
                        v-for="(item, idx) in filteredAchievements"
                        :key="item.id || `achieve-${idx}`"
                        class="group relative rounded-[2.25rem] bg-white border border-slate-200/90 hover:border-brand-300 shadow-md hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-500 hover:-translate-y-1.5 flex flex-col justify-between overflow-hidden"
                    >
                        <!-- Top Image if exists -->
                        <div v-if="item.image" class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                            <img
                                :src="item.image"
                                :alt="item.title"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <!-- Click to Zoom Button -->
                            <button
                                type="button"
                                @click="openPreview(item.image, item.title)"
                                class="absolute top-3 right-3 h-10 w-10 rounded-full bg-white/30 backdrop-blur-md border border-white/40 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100 transition-all duration-300 hover:bg-white hover:text-brand-600"
                                title="Perbesar Foto"
                            >
                                <Maximize2 class="w-4 h-4" />
                            </button>

                            <!-- Badge on Image -->
                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-600 text-white text-[11px] font-extrabold shadow-md backdrop-blur border border-white/30">
                                    <Trophy class="w-3 h-3 text-amber-300" />
                                    <span>Prestasi Resmi</span>
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 sm:p-7 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <!-- Icon badge if no image or as accent -->
                                <div class="flex items-center justify-between">
                                    <div class="h-11 w-11 rounded-2xl bg-brand-50 border border-brand-200 text-brand-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-brand-500 group-hover:text-white transition-all duration-300 shadow-sm">
                                        <component :is="getIcon(item.icon)" class="w-5 h-5" />
                                    </div>
                                    <span v-if="item.value" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-black">
                                        <Star class="w-3 h-3 text-amber-500 fill-amber-500" />
                                        <span>{{ item.value }}</span>
                                    </span>
                                </div>

                                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 leading-snug group-hover:text-brand-600 transition-colors">
                                    {{ item.title }}
                                </h3>

                                <p v-if="item.description" class="text-slate-600 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
                                    {{ item.description }}
                                </p>
                            </div>

                            <!-- Card Footer -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400">
                                <span class="flex items-center gap-1.5 text-brand-600">
                                    <CheckCircle2 class="w-3.5 h-3.5 text-emerald-500" />
                                    <span>Terverifikasi SMKS Muhammadiyah 2</span>
                                </span>
                            </div>
                        </div>

                        <!-- Bottom Accent Border -->
                        <div class="h-1.5 w-full bg-gradient-to-r from-brand-500 via-sky-400 to-emerald-400"></div>
                    </article>
                </div>

                <!-- Empty State -->
                <div v-else class="p-12 rounded-[2.5rem] bg-white border border-slate-200 text-center space-y-4 shadow-sm">
                    <div class="h-16 w-16 rounded-3xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center mx-auto">
                        <Trophy class="w-8 h-8" />
                    </div>
                    <h4 class="text-lg font-extrabold text-slate-800">Tidak ada prestasi yang sesuai kriteria pencarian</h4>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">
                        Silakan ubah kata kunci pencarian atau pilih kategori prestasi lainnya.
                    </p>
                    <button
                        type="button"
                        @click="searchQuery = ''; selectedCategory = 'all'"
                        class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-md transition-all"
                    >
                        Reset Filter
                    </button>
                </div>
            </section>

            <!-- ================= APPRECIATION & TALENT ECOSYSTEM BANNER ================= -->
            <section class="py-16 pb-32 sm:pb-20 bg-white border-t border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 text-brand-600 border border-brand-200 text-xs font-extrabold uppercase tracking-widest">
                            <Sparkles class="w-3.5 h-3.5" />
                            <span>Ekosistem Prestasi</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                            Komitmen Pembinaan Bakat Juara
                        </h2>
                        <p class="text-slate-500 text-xs sm:text-sm">
                            STEMDA memberikan dukungan penuh bagi siswa untuk mengasah keahlian dan meraih penghargaan bergengsi.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-6 rounded-[2rem] bg-slate-50 border border-slate-200 hover:border-brand-300 transition-all group hover:-translate-y-1">
                            <div class="h-12 w-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <Sparkles class="w-6 h-6" />
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900 mb-2">Bimbingan Intensif & Pelatih Ahli</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Setiap perwakilan lomba mendapatkan pendampingan terstruktur dari guru pembimbing dan instruktur profesional berlisensi.
                            </p>
                        </div>

                        <div class="p-6 rounded-[2rem] bg-slate-50 border border-slate-200 hover:border-brand-300 transition-all group hover:-translate-y-1">
                            <div class="h-12 w-12 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <Award class="w-6 h-6" />
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900 mb-2">Apresiasi & Beasiswa Berprestasi</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Sekolah memberikan penghargaan, sertifikat prestasi, dan beasiswa khusus bagi siswa-siswi yang berhasil mengharumkan almamater.
                            </p>
                        </div>

                        <div class="p-6 rounded-[2rem] bg-slate-50 border border-slate-200 hover:border-brand-300 transition-all group hover:-translate-y-1">
                            <div class="h-12 w-12 rounded-2xl bg-brand-50 border border-brand-200 text-brand-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <Trophy class="w-6 h-6" />
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900 mb-2">Jejaring Lomba Tingkat Nasional</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Mengikutsertakan siswa secara aktif dalam ajang LKS Vokasi, Olimpiade Sains, kejuaraan olahraga, dan festival kreatif nasional.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- ================= IMAGE PREVIEW MODAL LIGHTBOX ================= -->
        <div
            v-if="activePreviewImage"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-md p-4 animate-fade-in"
            @click.self="closePreview"
        >
            <div class="relative max-w-4xl w-full bg-white rounded-3xl overflow-hidden shadow-2xl border border-white/20">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <h4 class="text-sm font-extrabold text-slate-900 truncate pr-4">{{ activePreviewTitle }}</h4>
                    <button
                        type="button"
                        @click="closePreview"
                        class="p-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 transition-colors font-bold text-xs"
                    >
                        ✕ Tutup
                    </button>
                </div>
                <div class="max-h-[75vh] overflow-auto bg-slate-900 flex items-center justify-center p-2">
                    <img :src="activePreviewImage" :alt="activePreviewTitle" class="max-h-[70vh] w-auto object-contain rounded-xl" />
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
@keyframes pulseSlow {
    0%, 100% { opacity: 0.15; transform: scale(1); }
    50% { opacity: 0.25; transform: scale(1.08); }
}

@keyframes bounceSubtle {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-4px); }
}

.animate-pulse-slow {
    animation: pulseSlow 8s ease-in-out infinite;
}

.animate-bounce-subtle {
    animation: bounceSubtle 3s ease-in-out infinite;
}
</style>
