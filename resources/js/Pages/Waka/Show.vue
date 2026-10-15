<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    Award,
    BadgeCheck,
    BookOpen,
    Briefcase,
    Building2,
    Calendar,
    CheckCircle2,
    ChevronRight,
    Compass,
    Cpu,
    ExternalLink,
    Factory,
    GraduationCap,
    HeartHandshake,
    Images,
    Layers,
    Lightbulb,
    Maximize2,
    Medal,
    Music,
    Quote,
    Radio,
    School,
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
import { computed } from 'vue';

const props = defineProps({
    waka: {
        type: Object,
        required: true,
    },
    allWaka: {
        type: Array,
        default: () => [],
    },
});

// Normalization for gallery
function normalizeGalleryItem(item) {
    if (typeof item === 'string') {
        return { url: item, caption: '' };
    }
    if (typeof item === 'object' && item !== null) {
        return { url: item.url || '', caption: item.caption || '' };
    }
    return { url: '', caption: '' };
}

const normalizedGallery = computed(() => {
    if (!props.waka.gallery || !Array.isArray(props.waka.gallery)) return [];
    return props.waka.gallery.map(normalizeGalleryItem).filter((g) => g.url);
});

const hasGallery = computed(() => normalizedGallery.value.length > 0);
const shouldAutoScrollGallery = computed(() => normalizedGallery.value.length >= 3);
const galleryCarouselItems = computed(() => {
    const items = normalizedGallery.value.map((img, idx) => ({
        ...img,
        trackKey: `base-${idx}`,
    }));

    if (!shouldAutoScrollGallery.value) {
        return items;
    }

    return [
        ...items,
        ...normalizedGallery.value.map((img, idx) => ({
            ...img,
            trackKey: `clone-${idx}`,
        })),
    ];
});
const galleryCarouselStyle = computed(() => {
    if (!shouldAutoScrollGallery.value) {
        return {};
    }
    return {
        '--gallery-duration': `${Math.max(normalizedGallery.value.length * 5, 18)}s`,
    };
});

// Detect WAKA Category for customized strategic focus pillars & extra sections
const wakaSlug = computed(() => (props.waka.slug || '').toLowerCase());
const isKesiswaan = computed(() => wakaSlug.value.includes('kesiswaan'));
const isKurikulum = computed(() => wakaSlug.value.includes('kurikulum'));
const isHumas = computed(() => wakaSlug.value.includes('humas') || wakaSlug.value.includes('bkk'));

// Quick stats
const highlightStats = computed(() => {
    if (isKesiswaan.value) {
        return [
            { value: '20+', label: 'Ekstrakurikuler & Minat Bakat', icon: Trophy, color: 'text-amber-600 bg-amber-50 border-amber-200' },
            { value: '100%', label: 'Penguatan Karakter & Disiplin', icon: HeartHandshake, color: 'text-emerald-600 bg-emerald-50 border-emerald-200' },
            { value: '5', label: 'Kejuruan Berprestasi Juara', icon: Medal, color: 'text-brand-600 bg-brand-50 border-brand-200' },
            { value: 'Aman', label: 'Sekolah Ramah & Inklusif', icon: ShieldCheck, color: 'text-purple-600 bg-purple-50 border-purple-200' },
        ];
    }
    if (isKurikulum.value) {
        return [
            { value: '100%', label: 'Kurikulum Selaras DUDI', icon: Factory, color: 'text-brand-600 bg-brand-50 border-brand-200' },
            { value: 'TEFA', label: 'Teaching Factory & PjBL', icon: Wrench, color: 'text-amber-600 bg-amber-50 border-amber-200' },
            { value: 'UKK', label: 'Uji Kompetensi Keahlian', icon: Award, color: 'text-emerald-600 bg-emerald-50 border-emerald-200' },
            { value: '5 Jurusan', label: 'Konsentrasi Vokasi Unggulan', icon: Sparkles, color: 'text-purple-600 bg-purple-50 border-purple-200' },
        ];
    }
    return [
        { value: '100+', label: 'Mitra Industri Nasional', icon: Building2, color: 'text-brand-600 bg-brand-50 border-brand-200' },
        { value: 'BKK', label: 'Bursa Kerja & Penyaluran', icon: Briefcase, color: 'text-emerald-600 bg-emerald-50 border-emerald-200' },
        { value: 'Magang', label: 'Program PKL Terpadu', icon: Users, color: 'text-amber-600 bg-amber-50 border-amber-200' },
        { value: 'Karir', label: 'Bimbingan & Technopreneur', icon: Compass, color: 'text-purple-600 bg-purple-50 border-purple-200' },
    ];
});

// Strategic Pillars per division
const strategicPillars = computed(() => {
    if (isKurikulum.value) {
        return [
            {
                icon: Factory,
                title: 'Kurikulum Selaras Industri (DUDI)',
                desc: 'Penyelarasan materi ajar dengan standar industri terkemuka agar lulusan relevan dengan kebutuhan dunia kerja.',
                badge: 'Link & Match',
                color: 'from-brand-500 to-blue-600',
                iconBg: 'bg-brand-50 text-brand-600',
            },
            {
                icon: Wrench,
                title: 'Teaching Factory & PjBL',
                desc: 'Metode pembelajaran berbasis proyek riil dan teaching factory untuk mengasah keterampilan praktis siswa.',
                badge: 'Praktik Nyata',
                color: 'from-amber-500 to-orange-600',
                iconBg: 'bg-amber-50 text-amber-600',
            },
            {
                icon: Award,
                title: 'Uji Kompetensi Keahlian (UKK)',
                desc: 'Pengujian dan sertifikasi kompetensi keahlian siswa berstandar industri dan kurikulum vokasi nasional.',
                badge: 'Kompetensi Teruji',
                color: 'from-emerald-500 to-teal-600',
                iconBg: 'bg-emerald-50 text-emerald-600',
            },
            {
                icon: Sparkles,
                title: 'Penguatan Karakter & Nilai Islami',
                desc: 'Integrasi nilai-nilai keislaman, etos kerja tinggi, disiplin, dan integritas dalam proses belajar mengajar.',
                badge: 'Akhlak Mulia',
                color: 'from-purple-500 to-pink-600',
                iconBg: 'bg-purple-50 text-purple-600',
            },
        ];
    }

    if (isKesiswaan.value) {
        return [
            {
                icon: HeartHandshake,
                title: 'Pembinaan Karakter & Budi Pekerti',
                desc: 'Menanamkan nilai-nilai keislaman, kedisiplinan, kejujuran, etika sopan santun, dan tanggung jawab sosial.',
                badge: 'Akhlak Luhur',
                color: 'from-emerald-500 to-teal-600',
                iconBg: 'bg-emerald-50 text-emerald-600',
            },
            {
                icon: Trophy,
                title: 'Pengembangan Minat, Bakat & Prestasi',
                desc: 'Wadah pembinaan intensif kompetensi kejuruan (LKS), olahraga, sains terapan, seni budaya, dan teknologi.',
                badge: 'Juara Kompetisi',
                color: 'from-amber-500 to-orange-600',
                iconBg: 'bg-amber-50 text-amber-600',
            },
            {
                icon: Users,
                title: 'Kepemimpinan & Organisasi Siswa (IPM)',
                desc: 'Membentuk jiwa kepemimpinan tangguh melalui Ikatan Pelajar Muhammadiyah, Hizbul Wathan, dan Tapak Suci.',
                badge: 'Leadership & Softskills',
                color: 'from-brand-500 to-blue-600',
                iconBg: 'bg-brand-50 text-brand-600',
            },
            {
                icon: ShieldCheck,
                title: 'Iklim Sekolah Aman, Ramah & Inklusif',
                desc: 'Menciptakan budaya sekolah positif, bebas perundungan (anti-bullying), penuh rasa kekeluargaan dan saling peduli.',
                badge: 'Zona Nyaman & Positif',
                color: 'from-purple-500 to-pink-600',
                iconBg: 'bg-purple-50 text-purple-600',
            },
        ];
    }

    if (isHumas.value) {
        return [
            {
                icon: Building2,
                title: 'Kemitraan DUDI & Praktik Kerja Lapangan',
                desc: 'Menjalin MoU kemitraan dengan ratusan industri nasional untuk program PKL dan kelas industri.',
                badge: 'Jejaring Industri',
                color: 'from-brand-500 to-blue-600',
                iconBg: 'bg-brand-50 text-brand-600',
            },
            {
                icon: Briefcase,
                title: 'Bursa Kerja Khusus (BKK) & Rekrutmen',
                desc: 'Penyaluran tenaga kerja langsung ke perusahaan mitra sebelum siswa dinyatakan lulus.',
                badge: 'Penyaluran Kerja',
                color: 'from-emerald-500 to-teal-600',
                iconBg: 'bg-emerald-50 text-emerald-600',
            },
            {
                icon: Compass,
                title: 'Bimbingan Karir & Technopreneur',
                desc: 'Pendampingan konsultasi karir, pembekalan soft skills, dan inkubasi wirausaha mandiri.',
                badge: 'Siap Kerja & Usaha',
                color: 'from-amber-500 to-orange-600',
                iconBg: 'bg-amber-50 text-amber-600',
            },
            {
                icon: Sparkles,
                title: 'Publikasi & Komunikasi Publik',
                desc: 'Menjaga hubungan sinergis dengan orang tua, alumni, pemerintah, dan masyarakat luas.',
                badge: 'Branding Unggul',
                color: 'from-purple-500 to-pink-600',
                iconBg: 'bg-purple-50 text-purple-600',
            },
        ];
    }

    // Default
    return [
        {
            icon: Target,
            title: 'Tata Kelola Berorientasi Mutu',
            desc: 'Peningkatan mutu layanan pendidikan berstandar akreditasi unggul nasional.',
            badge: 'Mutu Unggul',
            color: 'from-brand-500 to-blue-600',
            iconBg: 'bg-brand-50 text-brand-600',
        },
        {
            icon: Zap,
            title: 'Inovasi & Adaptasi Berkelanjutan',
            desc: 'Pemanfaatan teknologi digital untuk percepatan transformasi institusi vokasi.',
            badge: 'Inovasi Digital',
            color: 'from-amber-500 to-orange-600',
            iconBg: 'bg-amber-50 text-amber-600',
        },
        {
            icon: Users,
            title: 'Sinergi Kolaboratif Pemangku Kepentingan',
            desc: 'Membangun kolaborasi solid antar guru, siswa, orang tua, dan mitra industri.',
            badge: 'Kolaborasi',
            color: 'from-emerald-500 to-teal-600',
            iconBg: 'bg-emerald-50 text-emerald-600',
        },
        {
            icon: HeartHandshake,
            title: 'Pemberdayaan Berkarakter Islami',
            desc: 'Menjunjung tinggi nilai kejujuran, dedikasi, dan akhlak mulia dalam setiap pelayanan.',
            badge: 'Karakter Mulia',
            color: 'from-purple-500 to-pink-600',
            iconBg: 'bg-purple-50 text-purple-600',
        },
    ];
});

// Dynamic topic paragraphs with icons & color badges
const richParagraphs = computed(() => {
    let raw = props.waka.content || props.waka.description || '';
    if (!raw) return [];

    let text = raw.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
    const sentences = text.match(/[^.!?]+[.!?]+(\s|$)/g) || [text];
    const chunks = [];
    let current = [];

    sentences.forEach((sentence, index) => {
        current.push(sentence.trim());
        if (current.length >= 2 || index === sentences.length - 1) {
            chunks.push(current.join(' '));
            current = [];
        }
    });

    const iconPool = [
        { icon: HeartHandshake, badge: 'Pondasi Karakter', color: 'emerald' },
        { icon: Trophy, badge: 'Aktivitas & Prestasi', color: 'amber' },
        { icon: Sparkles, badge: 'Sinergi 5 Kejuruan', color: 'brand' },
        { icon: ShieldCheck, badge: 'Budaya & Iklim Belajar', color: 'purple' },
        { icon: Target, badge: 'Orientasi Masa Depan', color: 'blue' },
    ];

    return chunks.filter((c) => c.length > 20).map((content, idx) => {
        const meta = iconPool[idx % iconPool.length];
        return {
            text: content,
            icon: meta.icon,
            badge: meta.badge,
            color: meta.color,
        };
    });
});

// Ekstrakurikuler & Wadah Bakat Siswa (khusus Kesiswaan / umum)
const extraCurriculars = [
    { name: 'Ikatan Pelajar Muhammadiyah (IPM)', category: 'Organisasi & Kepemimpinan', icon: Users, color: 'text-brand-600 bg-brand-50 border-brand-200' },
    { name: 'Kepanduan Hizbul Wathan (HW)', category: 'Kepanduan & Kedisiplinan', icon: Compass, color: 'text-emerald-600 bg-emerald-50 border-emerald-200' },
    { name: 'Tapak Suci Putera Muhammadiyah', category: 'Seni Bela Diri & Fisik', icon: Shield, color: 'text-rose-600 bg-rose-50 border-rose-200' },
    { name: 'Robotika & IT Innovation Club', category: 'Teknologi & Riset Vokasi', icon: Cpu, color: 'text-blue-600 bg-blue-50 border-blue-200' },
    { name: 'Olahraga (Futsal, Basket, Voli)', category: 'Sport & Sportivitas', icon: Trophy, color: 'text-amber-600 bg-amber-50 border-amber-200' },
    { name: 'Seni Musik, Hadrah & Paduan Suara', category: 'Kesenian & Budaya', icon: Music, color: 'text-purple-600 bg-purple-50 border-purple-200' },
    { name: 'Palang Merah Remaja (PMR)', category: 'Kemanusiaan & Kesehatan', icon: HeartHandshake, color: 'text-red-600 bg-red-50 border-red-200' },
    { name: 'Broadcasting & Multimedia Club', category: 'Kreativitas Konten Digital', icon: Radio, color: 'text-cyan-600 bg-cyan-50 border-cyan-200' },
];

// 5 Program Keahlian synergy list
const programsList = [
    { name: 'Teknik Kendaraan Ringan', code: 'TKR', icon: Wrench, color: 'text-rose-600 bg-rose-50 border-rose-200' },
    { name: 'Teknik Sepeda Motor', code: 'TBSM', icon: Zap, color: 'text-amber-600 bg-amber-50 border-amber-200' },
    { name: 'Teknik Elektronika Industri', code: 'TEI', icon: Cpu, color: 'text-blue-600 bg-blue-50 border-blue-200' },
    { name: 'Animasi', code: 'ANIMASI', icon: Sparkles, color: 'text-purple-600 bg-purple-50 border-purple-200' },
    { name: 'Kuliner', code: 'KULINER', icon: Award, color: 'text-emerald-600 bg-emerald-50 border-emerald-200' },
];
</script>

<template>
    <Head :title="`${waka.title} - SMKS Muhammadiyah 2 Genteng`" />

    <MainLayout>
        <div class="min-h-screen bg-slate-50/50 text-slate-800 selection:bg-brand-500 selection:text-white">
            <!-- ================= HERO HEADER SECTION (VIBRANT STEMDA BLUE WITH FLOATING AMBIENT GLOWS) ================= -->
            <section class="relative bg-gradient-to-br from-brand-700 via-brand-600 to-sky-600 text-white pt-28 pb-16 sm:pt-32 sm:pb-20 md:pt-36 md:pb-28 overflow-hidden shadow-xl">
                <!-- Background Animated Decorative Orbs & SVG Waves -->
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 100 C 30 0 70 0 100 100 Z" fill="white"></path>
                    </svg>
                </div>
                <!-- Ambient Floating Light Orbs with subtle CSS animation -->
                <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-white/15 blur-3xl pointer-events-none animate-pulse-slow"></div>
                <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-cyan-400/25 blur-3xl pointer-events-none animate-pulse-slow"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <!-- Breadcrumbs -->
                    <nav class="flex items-center gap-2 text-xs font-semibold text-brand-100/80 mb-6">
                        <Link :href="route('home')" class="hover:text-white transition-colors">Beranda</Link>
                        <ChevronRight class="w-3.5 h-3.5 text-brand-200/60" />
                        <span class="text-brand-200">Struktur Pimpinan</span>
                        <ChevronRight class="w-3.5 h-3.5 text-brand-200/60" />
                        <span class="text-white font-bold">{{ waka.title }}</span>
                    </nav>

                    <div class="max-w-4xl space-y-5">
                        <!-- Badges with glowing border -->
                        <div class="inline-flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/15 border border-white/25 text-white text-xs font-black uppercase tracking-wider backdrop-blur shadow-sm hover:scale-105 transition-transform">
                                <School class="w-3.5 h-3.5 text-cyan-300 animate-bounce-subtle" />
                                <span>Wakil Kepala Sekolah</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-400/20 border border-emerald-300/30 text-white text-xs font-black uppercase tracking-wider backdrop-blur shadow-sm hover:scale-105 transition-transform">
                                <BadgeCheck class="w-3.5 h-3.5 text-emerald-300" />
                                <span>Pimpinan Resmi Institusi</span>
                            </span>
                        </div>

                        <!-- Main Title with high impact -->
                        <h1 class="text-3xl sm:text-5xl md:text-6xl font-black text-white tracking-tight leading-[1.1]">
                            {{ waka.title }}
                        </h1>

                        <p class="text-brand-50 text-base sm:text-lg md:text-xl leading-relaxed max-w-3xl font-normal">
                            Mewujudkan tata kelola vokasi modern, pembinaan karakter berakhlak mulia, dan pengembangan potensi siswa menuju daya saing global.
                        </p>

                        <!-- Quick WAKA Switcher Pills -->
                        <div v-if="allWaka.length > 1" class="pt-3">
                            <span class="flex items-center gap-1.5 text-xs font-bold text-brand-200 uppercase tracking-widest mb-2">
                                <Sparkles class="w-3.5 h-3.5 text-cyan-300" />
                                <span>Pilih Bidang WAKA Lainnya:</span>
                            </span>
                            <div class="flex flex-wrap items-center gap-2">
                                <Link
                                    v-for="item in allWaka"
                                    :key="item.id"
                                    :href="route('waka.show', { slug: item.slug })"
                                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-300 transform hover:-translate-y-0.5"
                                    :class="item.slug === waka.slug
                                        ? 'bg-white text-brand-700 shadow-lg shadow-brand-900/20 ring-2 ring-white/50 font-black'
                                        : 'bg-white/15 hover:bg-white/25 text-white border border-white/20 hover:border-white/40'"
                                >
                                    {{ item.title }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= HIGHLIGHT STATS TICKER ================= -->
            <section class="relative -mt-8 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div
                        v-for="(stat, idx) in highlightStats"
                        :key="`stat-${idx}`"
                        class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-1 flex items-center gap-3.5 group"
                    >
                        <div class="h-11 w-11 rounded-xl flex items-center justify-center shrink-0 border group-hover:scale-110 transition-transform duration-300" :class="stat.color">
                            <component :is="stat.icon" class="w-5 h-5" />
                        </div>
                        <div class="min-w-0">
                            <div class="text-xl sm:text-2xl font-black text-slate-900 leading-tight group-hover:text-brand-600 transition-colors">
                                {{ stat.value }}
                            </div>
                            <div class="text-[11px] font-bold text-slate-500 leading-tight mt-0.5 line-clamp-1">
                                {{ stat.label }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= MAIN CONTENT SECTION ================= -->
            <section class="py-16 md:py-24 bg-white border-b border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
                        <!-- LEFT COLUMN: Executive Profile Photo & Quick Roles -->
                        <div class="lg:col-span-4 space-y-6">
                            <!-- Executive Photo Card with Animated Glow -->
                            <div class="relative group">
                                <div class="absolute -inset-2 rounded-[2.5rem] bg-gradient-to-tr from-brand-500 to-cyan-400 opacity-20 blur-xl group-hover:opacity-40 transition duration-500"></div>

                                <div class="relative rounded-[2.25rem] bg-white border-4 border-white p-2 shadow-2xl shadow-brand-500/10 overflow-hidden">
                                    <div class="aspect-[3/4] rounded-[1.75rem] overflow-hidden bg-slate-100 relative">
                                        <img
                                            v-if="waka.image"
                                            :src="waka.image"
                                            :alt="waka.title"
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                        />
                                        <div v-else class="w-full h-full flex flex-col items-center justify-center p-6 text-slate-400 bg-gradient-to-b from-slate-50 to-slate-100">
                                            <GraduationCap class="w-20 h-20 text-slate-300 mb-3 animate-bounce-subtle" />
                                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Foto Pimpinan</span>
                                        </div>

                                        <div class="absolute bottom-4 left-4 right-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-brand-600 text-white border border-white/40 text-xs font-bold shadow-lg backdrop-blur hover:scale-105 transition-transform">
                                                <BadgeCheck class="w-3.5 h-3.5 text-cyan-300" />
                                                <span>Pimpinan Bidang</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Name Card -->
                            <div class="p-6 rounded-[2rem] bg-slate-50 border border-slate-200/80 shadow-sm text-center group hover:border-brand-300 transition-colors">
                                <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-brand-600 transition-colors">
                                    {{ waka.photo_name || waka.title }}
                                </h3>
                                <p class="text-xs font-bold text-brand-600 uppercase tracking-widest mt-1">
                                    {{ waka.title }}
                                </p>
                                <div class="mt-4 pt-4 border-t border-slate-200/80 flex items-center justify-center gap-2 text-xs font-semibold text-slate-500">
                                    <School class="w-4 h-4 text-brand-500" />
                                    <span>SMKS Muhammadiyah 2 Genteng</span>
                                </div>
                            </div>

                            <!-- Quick Tupoksi Card with lively icons -->
                            <div class="p-6 rounded-[2rem] bg-gradient-to-br from-brand-50/60 to-slate-50 border border-brand-100 shadow-sm space-y-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-8 w-8 rounded-xl bg-brand-500 text-white grid place-items-center shadow-md shadow-brand-500/20">
                                        <Layers class="w-4 h-4" />
                                    </div>
                                    <h4 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Fokus & Tanggung Jawab</h4>
                                </div>
                                <ul class="space-y-2.5 text-xs text-slate-600 font-medium">
                                    <li class="flex items-start gap-2.5">
                                        <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" />
                                        <span>Perencanaan, pelaksanaan, & evaluasi program kerja bidang.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <CheckCircle2 class="w-4 h-4 text-brand-500 shrink-0 mt-0.5" />
                                        <span>Penguatan kolaborasi lintas kejuruan dan pengembangan bakat.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <CheckCircle2 class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" />
                                        <span>Penjaminan mutu layanan dan pembinaan karakter siswa.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: High-End Structured Narrative & Feature Cards -->
                        <div class="lg:col-span-8 space-y-10">
                            <!-- Executive Quote Banner with Animated Glow -->
                            <div class="relative p-6 sm:p-8 rounded-[2.25rem] bg-gradient-to-br from-brand-50/70 via-white to-slate-50 border border-brand-100 shadow-xl shadow-brand-500/5 overflow-hidden">
                                <div class="absolute -top-12 -right-12 w-32 h-32 bg-brand-500/10 rounded-full blur-2xl pointer-events-none"></div>
                                <Quote class="w-12 h-12 text-brand-500/10 absolute top-4 right-6 pointer-events-none" />
                                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-brand-100/80 text-brand-700 text-xs font-black uppercase tracking-wider mb-4">
                                    <Sparkles class="w-3.5 h-3.5 text-brand-600 animate-spin-slow" />
                                    <span>Komitmen & Arah Kebijakan</span>
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
                                    Membentuk Generasi Unggul dengan <span class="text-brand-600">Karakter & Daya Saing Tinggi</span>
                                </h2>
                                <p class="text-slate-600 text-sm sm:text-base leading-relaxed mt-3">
                                    Menghadirkan lingkungan pendidikan vokasi yang inspiratif, membina potensi minat dan bakat secara holistik, serta menanamkan budi pekerti luhur bagi setiap peserta didik.
                                </p>
                            </div>

                            <!-- Structured Thematic Narrative Cards with Custom Icons -->
                            <div class="space-y-6">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                                    <div class="flex items-center gap-2.5">
                                        <BookOpen class="w-5 h-5 text-brand-600" />
                                        <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                                            Uraian Program & Orientasi {{ waka.title }}
                                        </h3>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest hidden sm:inline-block">
                                        {{ richParagraphs.length }} Bagian Penjelasan
                                    </span>
                                </div>

                                <!-- Dynamic Narrative Cards with Interactive Hover Effects -->
                                <div v-if="richParagraphs.length > 0" class="space-y-4">
                                    <div
                                        v-for="(para, pIdx) in richParagraphs"
                                        :key="`para-${pIdx}`"
                                        class="p-6 rounded-2xl bg-white hover:bg-slate-50/70 border border-slate-200/80 hover:border-brand-300 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5 flex flex-col sm:flex-row sm:items-start gap-4 group"
                                    >
                                        <!-- Animated Icon & Number Badge -->
                                        <div class="shrink-0 flex items-center sm:flex-col gap-2">
                                            <div class="h-10 w-10 rounded-xl bg-brand-50 group-hover:bg-brand-500 text-brand-600 group-hover:text-white flex items-center justify-center font-black text-sm shadow-sm transition-all duration-300 group-hover:scale-110">
                                                <component :is="para.icon" class="w-5 h-5" />
                                            </div>
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider sm:mt-1">
                                                0{{ pIdx + 1 }}
                                            </span>
                                        </div>

                                        <!-- Text and Topic Badge -->
                                        <div class="space-y-2 flex-1">
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-slate-100 group-hover:bg-brand-100/70 text-slate-600 group-hover:text-brand-700 text-[11px] font-bold tracking-wide transition-colors">
                                                <span>{{ para.badge }}</span>
                                            </div>
                                            <p class="text-slate-700 text-sm sm:text-base leading-relaxed group-hover:text-slate-900 transition-colors">
                                                {{ para.text }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fallback if empty -->
                                <div v-else class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-slate-500 text-sm">
                                    Informasi detail mengenai bidang ini akan segera diperbarui.
                                </div>
                            </div>

                            <!-- 4 Core Strategic Focus Cards with Rich Animations -->
                            <div class="space-y-6 pt-4">
                                <div class="flex items-center gap-2.5 pb-2 border-b border-slate-200">
                                    <Target class="w-5 h-5 text-brand-600" />
                                    <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                                        4 Pilar Strategis & Program Prioritas
                                    </h3>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div
                                        v-for="(pillar, idx) in strategicPillars"
                                        :key="`pillar-${idx}`"
                                        class="group p-6 rounded-[2rem] bg-white hover:bg-slate-50/80 border border-slate-200/80 hover:border-brand-300 shadow-md hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between"
                                    >
                                        <div>
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="h-12 w-12 rounded-2xl grid place-items-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-sm" :class="pillar.iconBg">
                                                    <component :is="pillar.icon" class="h-6 w-6" />
                                                </div>
                                                <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 group-hover:bg-brand-50 group-hover:text-brand-700 transition-colors">
                                                    {{ pillar.badge }}
                                                </span>
                                            </div>
                                            <h4 class="text-base font-extrabold text-slate-900 leading-tight mb-2 group-hover:text-brand-600 transition-colors">
                                                {{ pillar.title }}
                                            </h4>
                                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                                {{ pillar.desc }}
                                            </p>
                                        </div>
                                        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">
                                            <span>Prioritas 0{{ idx + 1 }}</span>
                                            <CheckCircle2 class="w-4 h-4 text-emerald-500" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SPECIAL SECTION: Ekstrakurikuler & Wadah Bakat Siswa (Especially rich for Kesiswaan) -->
                            <div v-if="isKesiswaan" class="space-y-6 pt-4">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                                    <div class="flex items-center gap-2.5">
                                        <Trophy class="w-5 h-5 text-amber-600" />
                                        <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                                            Wadah Bakat & Ekstrakurikuler Unggulan
                                        </h3>
                                    </div>
                                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">
                                        Eksplorasi Potensi
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div
                                        v-for="(ekskul, eIdx) in extraCurriculars"
                                        :key="`ekskul-${eIdx}`"
                                        class="p-4 rounded-2xl bg-white hover:bg-slate-50/80 border border-slate-200/80 hover:border-brand-300 transition-all duration-300 hover:-translate-y-0.5 shadow-sm hover:shadow-md flex items-center gap-3.5 group"
                                    >
                                        <div class="h-11 w-11 rounded-xl flex items-center justify-center shrink-0 border group-hover:scale-110 transition-transform duration-300" :class="ekskul.color">
                                            <component :is="ekskul.icon" class="w-5 h-5" />
                                        </div>
                                        <div class="min-w-0">
                                            <h5 class="text-sm font-extrabold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-1">
                                                {{ ekskul.name }}
                                            </h5>
                                            <p class="text-[11px] font-medium text-slate-500 line-clamp-1 mt-0.5">
                                                {{ ekskul.category }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sinergi 5 Jurusan -->
                            <div class="p-6 sm:p-8 rounded-[2.25rem] bg-slate-50 border border-slate-200/80 space-y-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-2.5">
                                        <Factory class="w-5 h-5 text-brand-600" />
                                        <h4 class="text-base font-extrabold text-slate-900">
                                            Sinergi Pembinaan 5 Program Keahlian
                                        </h4>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500">
                                    Seluruh program kerja {{ waka.title }} membina dan mengayomi seluruh peserta didik di kelima konsentrasi keahlian:
                                </p>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 pt-2">
                                    <div
                                        v-for="prog in programsList"
                                        :key="prog.code"
                                        class="p-3.5 rounded-xl bg-white border border-slate-200 text-center flex flex-col items-center justify-center gap-1.5 shadow-sm hover:shadow-md hover:border-brand-300 transition-all duration-300 hover:-translate-y-0.5"
                                    >
                                        <component :is="prog.icon" class="w-5 h-5 text-brand-600" />
                                        <span class="font-extrabold text-xs text-slate-800">{{ prog.code }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= GALLERY SECTION ================= -->
            <section v-if="hasGallery" class="py-16 md:py-24 bg-slate-50 border-b border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between gap-4 mb-10">
                        <div class="space-y-1.5">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 text-brand-600 border border-brand-200 text-xs font-extrabold uppercase tracking-widest">
                                <Images class="w-3.5 h-3.5" />
                                <span>Dokumentasi</span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Galeri Aktivitas & Kegiatan Bidang
                            </h2>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest hidden sm:inline-block">
                            {{ normalizedGallery.length }} Foto Tersedia
                        </span>
                    </div>

                    <!-- Auto-scroll Gallery for Desktop -->
                    <div v-if="shouldAutoScrollGallery" class="hidden md:block gallery-mask">
                        <div class="gallery-track flex w-max gap-5" :style="galleryCarouselStyle">
                            <article
                                v-for="(img, idx) in galleryCarouselItems"
                                :key="img.trackKey + '-' + idx"
                                class="group w-[22rem] shrink-0 overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                            >
                                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                                    <img :src="img.url" :alt="img.caption || 'Kegiatan ' + (idx + 1)" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-slate-950/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                                </div>
                                <div class="p-4 border-t border-slate-100 min-h-[4rem]">
                                    <p class="text-xs font-bold text-slate-700 line-clamp-2">
                                        {{ img.caption || `Dokumentasi Kegiatan ${((idx % normalizedGallery.length) || 0) + 1}` }}
                                    </p>
                                </div>
                            </article>
                        </div>
                    </div>

                    <!-- Static Grid / Mobile Scroll -->
                    <div
                        :class="shouldAutoScrollGallery
                            ? 'flex gap-4 overflow-x-auto px-1 pb-2 md:hidden snap-x snap-mandatory touch-pan-x [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden'
                            : 'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6'"
                    >
                        <article
                            v-for="(img, idx) in normalizedGallery"
                            :key="'static-' + idx"
                            :class="shouldAutoScrollGallery
                                ? 'group w-[80vw] max-w-[20rem] shrink-0 snap-start overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm'
                                : 'group overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1'"
                        >
                            <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                                <img :src="img.url" :alt="img.caption || 'Kegiatan ' + (idx + 1)" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
                            </div>
                            <div class="p-4 border-t border-slate-100 min-h-[4rem]">
                                <p class="text-xs font-bold text-slate-700 line-clamp-2">
                                    {{ img.caption || `Dokumentasi Kegiatan ${idx + 1}` }}
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
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

@keyframes spinSlow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-pulse-slow {
    animation: pulseSlow 8s ease-in-out infinite;
}

.animate-bounce-subtle {
    animation: bounceSubtle 3s ease-in-out infinite;
}

.animate-spin-slow {
    animation: spinSlow 20s linear infinite;
}

.gallery-mask {
    overflow: hidden;
    mask-image: linear-gradient(to right, transparent 0, black 5%, black 95%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, transparent 0, black 5%, black 95%, transparent 100%);
}

.gallery-track {
    animation: gallery-marquee var(--gallery-duration, 22s) linear infinite;
}

.gallery-mask:hover .gallery-track {
    animation-play-state: paused;
}

@keyframes gallery-marquee {
    from {
        transform: translate3d(0, 0, 0);
    }

    to {
        transform: translate3d(calc(-50% - 0.625rem), 0, 0);
    }
}

@media (max-width: 767px) {
    .gallery-mask {
        mask-image: none;
        -webkit-mask-image: none;
    }
}

@media (prefers-reduced-motion: reduce) {
    .gallery-track {
        animation: none;
    }
}
</style>
