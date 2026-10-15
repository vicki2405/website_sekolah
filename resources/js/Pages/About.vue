<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import {
    Award,
    BadgeCheck,
    BookOpen,
    Building2,
    CheckCircle2,
    ChevronRight,
    Compass,
    GraduationCap,
    HeartHandshake,
    Lightbulb,
    Quote,
    School,
    Shield,
    Sparkles,
    Target,
    Trophy,
    Users,
    Zap,
    ArrowRight,
    Clock,
} from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    achievements: {
        type: Array,
        default: () => [],
    },
    teachers: {
        type: Array,
        default: () => [],
    },
    profileSections: {
        type: Object,
        default: () => ({}),
    },
});

// Section Helpers
const getPrakata = () => props.profileSections?.prakata || null;
const getVisi = () => props.profileSections?.visi || null;
const getMisi = () => props.profileSections?.misi || null;
const getSejarah = () => props.profileSections?.sejarah || null;
const getTujuan = () => props.profileSections?.tujuan || null;

// Clean / Normalization Helpers
const misiList = computed(() => {
    const misi = getMisi();
    if (!misi?.content) {
        return [
            'Menyelenggarakan pendidikan vokasi berkualitas berbasis kurikulum industri terpadu.',
            'Membentuk insan berakhlak mulia, disiplin, bertaqwa, dan berwawasan kebangsaan.',
            'Menjalin kemitraan strategis dengan Dunia Usaha dan Dunia Industri (DUDI) skala nasional.',
            'Mengembangkan jiwa kewirausahaan, inovasi teknologi, dan kreativitas mandiri siswa.',
        ];
    }
    return misi.content
        .split('\n')
        .map((m) => m.replace(/^[0-9]+[.\-)]\s*/, '').trim())
        .filter(Boolean);
});

const tujuanList = computed(() => {
    const tujuan = getTujuan();
    if (!tujuan?.content) {
        return [
            'Menghasilkan lulusan yang siap kerja dengan sertifikasi kompetensi keahlian berstandar industri.',
            'Membekali siswa dengan kemampuan adaptasi teknologi digital dan daya saing global.',
            'Menyalurkan lulusan ke industri mitra, wirausaha mandiri, maupun perguruan tinggi vokasi unggulan.',
        ];
    }
    const html = tujuan.content || '';
    if (html.includes('<li')) {
        try {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const items = Array.from(doc.querySelectorAll('li'))
                .map((li) => (li.textContent || '').trim())
                .filter(Boolean);
            if (items.length > 0) return items;
        } catch {
            // fallback
        }
    }
    if (html.includes('<')) {
        try {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const text = (doc.body?.textContent || '').replace(/\r/g, '');
            const items = text.split('\n').map((t) => t.trim()).filter(Boolean);
            if (items.length > 0) return items;
        } catch {
            // fallback
        }
    }
    return html.split('\n').map((t) => t.trim()).filter(Boolean);
});

// Core Values
const coreValues = [
    {
        icon: HeartHandshake,
        title: 'Religius & Berakhlak',
        desc: 'Menanamkan nilai-nilai keislaman dan budi pekerti luhur dalam setiap sendi kehidupan sekolah.',
        color: 'from-emerald-500 to-teal-600',
        badge: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        iconBg: 'bg-emerald-100 text-emerald-600',
    },
    {
        icon: Award,
        title: 'Kompeten & Siap Kerja',
        desc: 'Penguasaan keahlian praktis berstandar industri dengan pengakuan sertifikasi nasional.',
        color: 'from-brand-500 to-blue-600',
        badge: 'bg-brand-50 text-brand-700 border-brand-200',
        iconBg: 'bg-brand-100 text-brand-600',
    },
    {
        icon: Lightbulb,
        title: 'Inovatif & Adaptif',
        desc: 'Mendorong daya cipta, pemanfaatan teknologi modern, dan solusi kreatif menghadapi tantangan zaman.',
        color: 'from-amber-500 to-orange-600',
        badge: 'bg-amber-50 text-amber-700 border-amber-200',
        iconBg: 'bg-amber-100 text-amber-600',
    },
    {
        icon: Zap,
        title: 'Mandiri & Berwirausaha',
        desc: 'Membangun mental mandiri, kepemimpinan tangguh, dan semangat wirausaha (*technopreneurship*).',
        color: 'from-purple-500 to-pink-600',
        badge: 'bg-purple-50 text-purple-700 border-purple-200',
        iconBg: 'bg-purple-100 text-purple-600',
    },
];

// Historical Milestones (Custom from Admin or Fallback)
const activeMilestones = computed(() => {
    const custom = props.profileSections?.sejarah?.items;
    if (Array.isArray(custom) && custom.length > 0) {
        return custom.map((item) => ({
            year: item.year || '',
            title: item.title || '',
            desc: item.desc || '',
        }));
    }
    return [
        {
            year: '1995',
            title: 'Awal Pendirian',
            desc: 'Didirikan sebagai respon atas kebutuhan pendidikan kejuruan teknik yang berkualitas dan terjangkau di kawasan Genteng.',
        },
        {
            year: '2008',
            title: 'Ekspansi Program Keahlian',
            desc: 'Penambahan jurusan baru otomotif dan elektronika untuk menjawab permintaan industri manufaktur modern.',
        },
        {
            year: '2019',
            title: 'Kelas Industri Terpadu',
            desc: 'Peresmian kemitraan strategis kelas industri binaan bersama perusahaan terkemuka nasional.',
        },
        {
            year: 'Sekarang',
            title: 'SMK Pusat Keunggulan',
            desc: 'Menjadi SMK rujukan berakreditasi A dengan fasilitas modern, ribuan alumni, dan lulusan berdaya saing tinggi.',
        },
    ];
});

// Achievements preview (take top 3)
const topAchievements = computed(() => {
    const list = Array.isArray(props.achievements) ? props.achievements : [];
    return list.slice(0, 3);
});
</script>

<template>
    <Head title="Profil Sekolah - SMKS Muhammadiyah 2 Genteng" />
    <MainLayout>
        <div class="min-h-screen bg-slate-50/50 text-slate-800 selection:bg-brand-500 selection:text-white">
            <!-- ================= HERO HEADER SECTION (OFFICIAL STEMDA BRAND BLUE) ================= -->
            <section class="relative bg-gradient-to-br from-brand-700 via-brand-600 to-sky-600 text-white pt-28 pb-16 sm:pt-32 sm:pb-20 md:pt-36 md:pb-28 overflow-hidden shadow-lg">
                <!-- Background Decorative Pattern -->
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 100 C 30 0 70 0 100 100 Z" fill="white"></path>
                    </svg>
                </div>
                <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-sky-400/20 blur-3xl pointer-events-none"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <!-- Breadcrumbs -->
                    <nav class="flex items-center gap-2 text-xs font-semibold text-brand-100/80 mb-6">
                        <Link :href="route('home')" class="hover:text-white transition-colors">Beranda</Link>
                        <ChevronRight class="w-3.5 h-3.5 text-brand-200/60" />
                        <span class="text-white font-bold">Profil Sekolah</span>
                    </nav>

                    <div class="max-w-4xl space-y-5">
                        <!-- Badges -->
                        <div class="inline-flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/15 border border-white/25 text-white text-xs font-black uppercase tracking-wider backdrop-blur">
                                <School class="w-3.5 h-3.5 text-cyan-300" />
                                <span>Profil Institusi</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-400/20 border border-emerald-300/30 text-white text-xs font-black uppercase tracking-wider backdrop-blur">
                                <BadgeCheck class="w-3.5 h-3.5 text-emerald-300" />
                                <span>Akreditasi A Unggul</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-amber-400/20 border border-amber-300/30 text-white text-xs font-black uppercase tracking-wider backdrop-blur">
                                <Sparkles class="w-3.5 h-3.5 text-amber-300" />
                                <span>SMK Pusat Keunggulan</span>
                            </span>
                        </div>

                        <!-- Main Title -->
                        <h1 class="text-3xl sm:text-5xl md:text-6xl font-black text-white tracking-tight leading-[1.1]">
                            Mencetak Generasi Vokasi <span class="text-cyan-200">Unggul & Berkarakter</span>
                        </h1>

                        <p class="text-brand-50 text-base sm:text-lg md:text-xl leading-relaxed max-w-3xl font-normal">
                            Mengenal lebih dekat <span class="text-white font-bold">SMKS Muhammadiyah 2 Genteng (STEMDA)</span> — lembaga pendidikan kejuruan rujukan dengan integritas tinggi, fasilitas modern, dan jejaring industri terkemuka.
                        </p>

                        <!-- Quick Navigation Pills -->
                        <div class="flex flex-wrap items-center gap-2 pt-2">
                            <a href="#sambutan" class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-xs font-bold text-white transition-all">
                                💬 Sambutan Kepala Sekolah
                            </a>
                            <a href="#visi-misi" class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-xs font-bold text-white transition-all">
                                🎯 Visi & Misi
                            </a>
                            <a href="#nilai-utama" class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-xs font-bold text-white transition-all">
                                ⭐ Nilai Karakter
                            </a>
                            <a href="#sejarah" class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-xs font-bold text-white transition-all">
                                📜 Sejarah & Statistik
                            </a>
                            <a href="#prestasi" class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-xs font-bold text-white transition-all">
                                🏆 Prestasi Unggulan
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= PRAKATA KEPALA SEKOLAH ================= -->
            <section id="sambutan" v-if="getPrakata()" class="py-16 md:py-24 bg-white border-b border-slate-200/80 relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                        <!-- Left: Principal Portrait -->
                        <div class="lg:col-span-5 flex flex-col items-center lg:items-start">
                            <div class="relative group w-full max-w-sm">
                                <!-- Subtle Glow Frame -->
                                <div class="absolute -inset-2 rounded-[2.5rem] bg-gradient-to-tr from-brand-500 to-cyan-400 opacity-20 blur-xl group-hover:opacity-30 transition duration-500"></div>

                                <div class="relative rounded-[2.25rem] bg-white border-4 border-white p-2 shadow-2xl shadow-brand-500/10 overflow-hidden">
                                    <div class="aspect-[3/4] rounded-[1.75rem] overflow-hidden bg-slate-100 relative">
                                        <img
                                            v-if="getPrakata().image"
                                            :src="getPrakata().image"
                                            :alt="getPrakata().principal_name || 'Kepala Sekolah'"
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                        />
                                        <div v-else class="w-full h-full flex flex-col items-center justify-center p-6 text-slate-400">
                                            <GraduationCap class="w-20 h-20 text-slate-300 mb-3" />
                                            <span class="text-xs font-bold uppercase tracking-wider">Foto Kepala Sekolah</span>
                                        </div>

                                        <div class="absolute bottom-4 left-4 right-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-600 text-white border border-white/40 text-[11px] font-bold shadow-lg backdrop-blur">
                                                <BadgeCheck class="w-3.5 h-3.5 text-cyan-300" />
                                                <span>Pimpinan Institusi</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 text-center lg:text-left">
                                <h3 class="text-2xl font-extrabold text-slate-900">
                                    {{ getPrakata().principal_name || 'Kepala SMKS Muhammadiyah 2 Genteng' }}
                                </h3>
                                <p class="text-sm font-bold text-brand-600 mt-1 uppercase tracking-widest">
                                    Kepala Sekolah
                                </p>
                            </div>
                        </div>

                        <!-- Right: Content -->
                        <div class="lg:col-span-7 space-y-5">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 text-brand-600 border border-brand-200 text-xs font-extrabold uppercase tracking-widest">
                                <Quote class="w-3.5 h-3.5" />
                                <span>Sambutan & Prakata</span>
                            </div>

                            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                                Membimbing Masa Depan dengan <span class="text-brand-600">Kompetensi & Nilai Luhur</span>
                            </h2>

                            <div class="relative p-6 sm:p-8 rounded-[2rem] bg-gradient-to-br from-brand-50/50 via-white to-slate-50 border border-brand-100 shadow-xl shadow-brand-500/5 space-y-4">
                                <Quote class="w-12 h-12 text-brand-500/10 absolute top-4 right-6 pointer-events-none" />
                                <div class="text-slate-600 text-base md:text-lg leading-relaxed space-y-4 whitespace-pre-line relative z-10">
                                    {{ getPrakata().content }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= VISI & MISI SECTION ================= -->
            <section id="visi-misi" class="py-16 md:py-24 bg-slate-50 border-b border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 text-brand-600 border border-brand-200 text-xs font-extrabold uppercase tracking-widest">
                            <Compass class="w-3.5 h-3.5" />
                            <span>Arah & Tujuan</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight">
                            Visi & Misi Institusi
                        </h2>
                        <p class="text-slate-500 text-sm sm:text-base">
                            Landasan kokoh dan peta jalan strategis SMKS Muhammadiyah 2 Genteng dalam memajukan mutu pendidikan vokasi di tanah air.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                        <!-- Left: Visi Card -->
                        <div class="lg:col-span-5 flex flex-col">
                            <div class="h-full relative rounded-[2.25rem] bg-gradient-to-br from-brand-600 via-brand-700 to-blue-800 text-white p-8 sm:p-10 shadow-xl shadow-brand-500/20 flex flex-col justify-between overflow-hidden group">
                                <div class="absolute -top-20 -right-20 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                                <div>
                                    <div class="h-12 w-12 rounded-2xl bg-white/15 border border-white/20 grid place-items-center text-white mb-6 group-hover:scale-110 transition-transform">
                                        <Target class="w-6 h-6 text-cyan-300" />
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-widest text-cyan-200">Pernyataan Visi</span>
                                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1 mb-6">
                                        {{ getVisi()?.title || 'Visi Sekolah' }}
                                    </h3>
                                    <p class="text-brand-50 text-lg sm:text-xl font-medium leading-relaxed italic relative z-10 whitespace-pre-line">
                                        {{ getVisi()?.content || '"Menjadi lembaga pendidikan kejuruan yang unggul, berkarakter mulia, dan kompeten di tingkat global pada tahun 2030."' }}
                                    </p>
                                </div>

                                <div class="mt-8 pt-6 border-t border-white/20 flex items-center justify-between text-xs font-bold text-cyan-200 uppercase tracking-wider">
                                    <span>Target Global 2030</span>
                                    <BadgeCheck class="w-5 h-5 text-emerald-300" />
                                </div>
                            </div>
                        </div>

                        <!-- Right: Misi List -->
                        <div class="lg:col-span-7 flex flex-col justify-between">
                            <div class="p-6 sm:p-8 rounded-[2.25rem] bg-white border border-slate-200/80 shadow-lg shadow-slate-200/50">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="h-10 w-10 rounded-xl bg-brand-50 border border-brand-200 grid place-items-center text-brand-600">
                                        <CheckCircle2 class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pilar Aksi</span>
                                        <h3 class="text-xl font-extrabold text-slate-900">{{ getMisi()?.title || 'Misi Sekolah' }}</h3>
                                    </div>
                                </div>

                                <div class="space-y-3.5">
                                    <div
                                        v-for="(misi, idx) in misiList"
                                        :key="`misi-${idx}`"
                                        class="group p-4 sm:p-5 rounded-2xl bg-slate-50 hover:bg-brand-50/60 border border-slate-100 hover:border-brand-200 transition-all duration-300 flex items-start gap-4"
                                    >
                                        <div class="shrink-0 h-9 w-9 rounded-xl bg-brand-500 text-white font-black text-xs grid place-items-center shadow-md shadow-brand-500/20">
                                            0{{ idx + 1 }}
                                        </div>
                                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed group-hover:text-slate-900 transition-colors">
                                            {{ misi }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= CORE VALUES (4 PILAR KARAKTER) ================= -->
            <section id="nilai-utama" class="py-16 md:py-24 bg-white border-b border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 text-brand-600 border border-brand-200 text-xs font-extrabold uppercase tracking-widest">
                            <Sparkles class="w-3.5 h-3.5" />
                            <span>Pondasi Karakter</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight">
                            4 Pilar Karakter Utama Siswa
                        </h2>
                        <p class="text-slate-500 text-sm sm:text-base">
                            Nilai-nilai keunggulan yang dibentuk secara konsisten sepanjang proses pembelajaran di STEMDA.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div
                            v-for="(val, idx) in coreValues"
                            :key="`core-val-${idx}`"
                            class="group relative rounded-[2rem] bg-white hover:bg-slate-50/80 border border-slate-200/80 hover:border-brand-300 p-6 shadow-lg shadow-slate-100 hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between"
                        >
                            <div>
                                <div class="h-12 w-12 rounded-2xl grid place-items-center mb-5 group-hover:scale-110 transition-transform" :class="val.iconBg">
                                    <component :is="val.icon" class="h-6 w-6" />
                                </div>
                                <h3 class="text-lg font-extrabold text-slate-900 leading-tight mb-2 group-hover:text-brand-600 transition-colors">
                                    {{ val.title }}
                                </h3>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    {{ val.desc }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">
                                <span>Pilar 0{{ idx + 1 }}</span>
                                <CheckCircle2 class="w-4 h-4 text-emerald-500" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= TUJUAN SEKOLAH SECTION ================= -->
            <section id="tujuan" v-if="getTujuan()" class="py-16 md:py-24 bg-slate-50 border-b border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="max-w-3xl mb-10 space-y-3">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 text-xs font-extrabold uppercase tracking-widest">
                            <Target class="w-3.5 h-3.5" />
                            <span>Capaian Mutu</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                            {{ getTujuan()?.title || 'Tujuan Pendidikan Vokasi' }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="(tujuan, idx) in tujuanList"
                            :key="`tujuan-${idx}`"
                            class="p-6 rounded-[2rem] bg-white border border-slate-200/80 shadow-md shadow-slate-100 flex flex-col justify-between"
                        >
                            <div class="space-y-3">
                                <div class="h-10 w-10 rounded-xl bg-emerald-50 border border-emerald-200 grid place-items-center text-emerald-600 font-black text-sm">
                                    0{{ idx + 1 }}
                                </div>
                                <p class="text-slate-700 text-sm sm:text-base leading-relaxed font-medium">
                                    {{ tujuan }}
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                Standar Kompetensi Vokasi
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= SEJARAH & STATISTIK SEKOLAH ================= -->
            <section id="sejarah" class="py-16 md:py-24 bg-white border-b border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                        <!-- Left: Sejarah Overview -->
                        <div class="lg:col-span-5 space-y-5">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 text-brand-600 border border-brand-200 text-xs font-extrabold uppercase tracking-widest">
                                <Clock class="w-3.5 h-3.5" />
                                <span>Perjalanan Waktu</span>
                            </div>

                            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                                {{ getSejarah()?.title || 'Sejarah & Transformasi STEMDA' }}
                            </h2>

                            <div v-if="getSejarah()?.content" class="rich-content text-slate-600 text-base leading-relaxed space-y-4 whitespace-pre-line" v-html="getSejarah()?.content"></div>
                            <div v-else class="text-slate-600 text-base leading-relaxed space-y-4 font-normal">
                                <p>
                                    Didirikan pada tahun 1995, SMKS Muhammadiyah 2 Genteng bermula dari cita-cita luhur untuk menyediakan pendidikan vokasi teknik bermutu tinggi yang terjangkau bagi masyarakat luas.
                                </p>
                                <p>
                                    Kini, melalui inovasi berkelanjutan dan kemitraan erat dengan industri nasional, STEMDA telah bertransformasi menjadi salah satu SMK rujukan terkemuka dengan ribuan lulusan sukses di berbagai sektor.
                                </p>
                            </div>

                            <!-- 4 Key Statistics Grid -->
                            <div class="grid grid-cols-2 gap-4 pt-3">
                                <div class="p-5 rounded-2xl bg-brand-50/70 border border-brand-100 text-center">
                                    <div class="text-3xl sm:text-4xl font-extrabold text-brand-600">30+</div>
                                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-1">Tahun Pengalaman</div>
                                </div>
                                <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-100 text-center">
                                    <div class="text-3xl sm:text-4xl font-extrabold text-emerald-600">5000+</div>
                                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-1">Alumni Tersebar</div>
                                </div>
                                <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-100 text-center">
                                    <div class="text-3xl sm:text-4xl font-extrabold text-amber-600">50+</div>
                                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-1">Guru & Instruktur Ahli</div>
                                </div>
                                <div class="p-5 rounded-2xl bg-purple-50/70 border border-purple-100 text-center">
                                    <div class="text-3xl sm:text-4xl font-extrabold text-purple-600">A</div>
                                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-1">Akreditasi Unggul</div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Milestones Timeline Card -->
                        <div class="lg:col-span-7">
                            <div class="p-6 sm:p-8 rounded-[2.5rem] bg-slate-50 border border-slate-200/80 shadow-lg shadow-slate-100">
                                <h3 class="text-xl font-extrabold text-slate-900 mb-8 flex items-center gap-2.5">
                                    <Sparkles class="w-5 h-5 text-brand-600" />
                                    <span>Tonggak Capaian Bersejarah</span>
                                </h3>

                                <div class="relative pl-6 sm:pl-8 space-y-8 before:absolute before:left-2 sm:before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-brand-300">
                                    <div
                                        v-for="(mile, idx) in activeMilestones"
                                        :key="`mile-${idx}`"
                                        class="relative group"
                                    >
                                        <!-- Timeline Dot -->
                                        <div class="absolute -left-[31px] sm:-left-[39px] top-1.5 h-4 w-4 rounded-full bg-white border-2 border-brand-500 group-hover:scale-125 group-hover:bg-brand-500 transition-all"></div>

                                        <div class="p-5 rounded-2xl bg-white hover:bg-brand-50/40 border border-slate-200/80 hover:border-brand-200 transition-all shadow-sm">
                                            <span class="inline-block px-2.5 py-0.5 rounded-md bg-brand-100 text-brand-700 text-xs font-black tracking-wider mb-2">
                                                {{ mile.year }}
                                            </span>
                                            <h4 class="text-lg font-extrabold text-slate-900 mb-1.5">{{ mile.title }}</h4>
                                            <p class="text-slate-600 text-sm leading-relaxed">{{ mile.desc }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= PRESTASI UNGGULAN SECTION ================= -->
            <section id="prestasi" v-if="topAchievements.length > 0" class="py-16 md:py-24 bg-slate-50 border-b border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
                        <div class="space-y-2">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-extrabold uppercase tracking-widest">
                                <Trophy class="w-3.5 h-3.5" />
                                <span>Bukti Keunggulan</span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                                Prestasi & Prestise Siswa
                            </h2>
                        </div>
                        <Link :href="route('prestasi')" class="inline-flex items-center gap-2 text-sm font-bold text-brand-600 hover:text-brand-700 transition-colors">
                            <span>Lihat Semua Prestasi</span>
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="achieve in topAchievements"
                            :key="achieve.id"
                            class="group relative rounded-[2rem] overflow-hidden bg-white border border-slate-200/80 hover:border-brand-300 transition-all duration-300 hover:-translate-y-1 shadow-lg shadow-slate-100"
                        >
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                                <img
                                    v-if="achieve.image"
                                    :src="achieve.image"
                                    :alt="achieve.title"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                />
                                <div v-else class="w-full h-full grid place-items-center text-slate-400">
                                    <Trophy class="w-12 h-12" />
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-base font-extrabold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-2">
                                    {{ achieve.title }}
                                </h3>
                                <p v-if="achieve.description" class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                                    {{ achieve.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </MainLayout>
</template>
