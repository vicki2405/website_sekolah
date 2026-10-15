<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    ArrowLeft,
    ArrowRight,
    ArrowUpRight,
    Award,
    BadgeCheck,
    BookOpen,
    BriefcaseBusiness,
    Building2,
    Car,
    CheckCircle2,
    ChefHat,
    ChevronRight,
    Clock,
    Compass,
    Cpu,
    ExternalLink,
    Factory,
    GraduationCap,
    Hammer,
    Images,
    Layers,
    Maximize2,
    MessageCircle,
    Palette,
    Phone,
    School,
    Shield,
    Sparkles,
    Target,
    Users,
    Wrench,
    Zap,
    ChevronLeft,
    ChevronDown,
    X,
} from 'lucide-vue-next';
import { computed, ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    slug: String,
    program: Object,
});

const page = usePage();
const activeGalleryIndex = ref(null);

const activeActivityPhoto = computed(() => {
    if (activeGalleryIndex.value === null || !activityImages.value.length) return null;
    return activityImages.value[activeGalleryIndex.value] || null;
});

const openActivityLightbox = (index) => {
    activeGalleryIndex.value = index;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = 'hidden';
    }
};

const closeActivityLightbox = () => {
    activeGalleryIndex.value = null;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
};

const prevActivityPhoto = () => {
    if (activeGalleryIndex.value === null || !activityImages.value.length) return;
    activeGalleryIndex.value = (activeGalleryIndex.value - 1 + activityImages.value.length) % activityImages.value.length;
};

const nextActivityPhoto = () => {
    if (activeGalleryIndex.value === null || !activityImages.value.length) return;
    activeGalleryIndex.value = (activeGalleryIndex.value + 1) % activityImages.value.length;
};

const handleKeyDown = (e) => {
    if (activeGalleryIndex.value === null) return;
    if (e.key === 'Escape') closeActivityLightbox();
    if (e.key === 'ArrowLeft') prevActivityPhoto();
    if (e.key === 'ArrowRight') nextActivityPhoto();
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
});

// Program Theme Profiles
const PROGRAM_THEMES = {
    tkr: {
        badge: 'Otomotif & Kendaraan Ringan',
        gradient: 'from-blue-600 via-indigo-600 to-cyan-500',
        glow: 'rgba(37, 99, 235, 0.25)',
        accentBg: 'bg-blue-500/10 text-blue-400 border-blue-500/20',
        accentBadge: 'bg-blue-500/20 text-blue-300 border-blue-400/30',
        heroSubtitle: 'Membentuk teknisi otomotif profesional yang menguasai teknologi kendaraan modern, diagnosa komputer, dan standar bengkel internasional.',
        icon: Car,
        competencies: [
            {
                icon: Wrench,
                title: 'Perawatan & Overhaul Mesin',
                desc: 'Diagnosa dan perbaikan menyeluruh pada engine bensin dan diesel modern.',
            },
            {
                icon: Cpu,
                title: 'Engine Management & EFI',
                desc: 'Troubleshooting sistem injeksi elektronik menggunakan diagnostic scanner mutakhir.',
            },
            {
                icon: Zap,
                title: 'Sistem Kelistrikan Bodi & AC',
                desc: 'Penguasaan wiring diagram, sistem penerangan, audio, dan pendingin kabin mobil.',
            },
            {
                icon: Hammer,
                title: 'Chassis & Power Train',
                desc: 'Perbaikan sistem transmisi manual & otomatis, sistem rem ABS, kemudi, dan suspensi.',
            },
        ],
        careers: [
            { title: 'Teknisi Ahli Otomotif', desc: 'Mekanik spesialis di bengkel resmi APM maupun bengkel modern mandiri.' },
            { title: 'Service Advisor', desc: 'Konsultan teknis layanan servis pelanggan di dealer dan bengkel otomotif.' },
            { title: 'Wirausaha Bengkel Mandiri', desc: 'Membuka usaha jasa servis, tune-up, cuci mobil, dan modifikasi kendaraan.' },
            { title: 'Quality Control Industri', desc: 'Inspektor standar perakitan komponen pada industri manufaktur otomotif.' },
        ],
        partners: ['Toyota', 'Daihatsu', 'Astra International', 'Mitsubishi Motors', 'Honda'],
    },
    tbsm: {
        badge: 'Sepeda Motor & Roda Dua',
        gradient: 'from-amber-500 via-orange-600 to-rose-600',
        glow: 'rgba(234, 88, 12, 0.25)',
        accentBg: 'bg-orange-500/10 text-orange-400 border-orange-500/20',
        accentBadge: 'bg-orange-500/20 text-orange-300 border-orange-400/30',
        heroSubtitle: 'Mencetak teknisi handal dalam pemeliharaan, injeksi FI, tune-up, dan overhaul sepeda motor berstandar industri resmi.',
        icon: Wrench,
        competencies: [
            {
                icon: Wrench,
                title: 'Teknologi Injeksi (PGM-FI / YMJET)',
                desc: 'Setting, kalibrasi sensor ECU, dan pembersihan sistem injeksi bahan bakar motor.',
            },
            {
                icon: Layers,
                title: 'CVT & Transmisi Matic',
                desc: 'Pemeliharaan dan perbaikan komponen transmisi otomatis (CVT) sepeda motor kekinian.',
            },
            {
                icon: Zap,
                title: 'Kelistrikan & Smart Key System',
                desc: 'Sistem pengapian digital, keyless, starter elektrik, dan indikator panel speedometer.',
            },
            {
                icon: Shield,
                title: 'Chassis, Pengereman & Ban',
                desc: 'Perawatan suspensi teleskopik, hidrolik, sistem rem cakram/CBS, dan balancing roda.',
            },
        ],
        careers: [
            { title: 'Mekanik Sepeda Motor Resmi', desc: 'Teknisi profesional di jaringan AHASS, Yamaha, atau bengkel resmi roda dua.' },
            { title: 'Teknisi Modifikasi & Balap', desc: 'Spesialis modifikasi mesin, kelistrikan, dan upgrade performa motor.' },
            { title: 'Owner Workshop Mandiri', desc: 'Pemilik usaha bengkel servis motor, spare parts, dan detailing motor.' },
            { title: 'Service Coordinator', desc: 'Pengelola alur operasional dan penerimaan servis di bengkel modern.' },
        ],
        partners: ['Astra Honda Motor', 'Yamaha Indonesia', 'Suzuki Indomobil', 'Federal Oil'],
    },
    tsm: {
        badge: 'Sepeda Motor & Roda Dua',
        gradient: 'from-amber-500 via-orange-600 to-rose-600',
        glow: 'rgba(234, 88, 12, 0.25)',
        accentBg: 'bg-orange-500/10 text-orange-400 border-orange-500/20',
        accentBadge: 'bg-orange-500/20 text-orange-300 border-orange-400/30',
        heroSubtitle: 'Mencetak teknisi handal dalam pemeliharaan, injeksi FI, tune-up, dan overhaul sepeda motor berstandar industri resmi.',
        icon: Wrench,
        competencies: [
            { icon: Wrench, title: 'Teknologi Injeksi (FI)', desc: 'Kalibrasi ECU dan pembersihan sistem injeksi bahan bakar.' },
            { icon: Layers, title: 'CVT & Transmisi Matic', desc: 'Pemeliharaan komponen transmisi otomatis sepeda motor.' },
            { icon: Zap, title: 'Kelistrikan & Smart Key', desc: 'Sistem pengapian digital, keyless, dan panel speedometer.' },
            { icon: Shield, title: 'Chassis & Pengereman', desc: 'Perawatan suspensi, sistem rem cakram/CBS, dan roda.' },
        ],
        careers: [
            { title: 'Mekanik Motor Resmi', desc: 'Teknisi di jaringan dealer dan bengkel resmi roda dua.' },
            { title: 'Wirausaha Bengkel Motor', desc: 'Membuka usaha bengkel servis dan penyedia suku cadang.' },
            { title: 'Quality Control Perusahaan Otomotif', desc: 'Pengendali mutu pada lini produksi komponen sepeda motor.' },
        ],
        partners: ['Astra Honda Motor', 'Yamaha Indonesia', 'Suzuki Indomobil'],
    },
    tei: {
        badge: 'Elektronika Industri & Otomasi',
        gradient: 'from-teal-500 via-emerald-600 to-cyan-600',
        glow: 'rgba(13, 148, 136, 0.25)',
        accentBg: 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        accentBadge: 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30',
        heroSubtitle: 'Menguasai otomasi industri, PLC, mikrokontroler, IoT, dan perancangan sistem elektronika kendali modern.',
        icon: Cpu,
        competencies: [
            {
                icon: Cpu,
                title: 'Pemrograman PLC & SCADA',
                desc: 'Desain diagram tangga (ladder logic) dan pengontrolan mesin industri otomatis.',
            },
            {
                icon: Zap,
                title: 'Mikrokontroler & Internet of Things (IoT)',
                desc: 'Integrasi mikrokontroler Arduino/ESP dengan sensor dan dashboard pemantauan cloud.',
            },
            {
                icon: Layers,
                title: 'Perancangan PCB & Perakitan Sirkuit',
                desc: 'Skema desain sirkuit elektronika, routing PCB, soldering presisi, dan uji sinyal.',
            },
            {
                icon: Factory,
                title: 'Pneumatik & Elektro-Pneumatik',
                desc: 'Sistem penggerak udara bertekanan untuk aktuator mekanik pada lini pabrik cerdas.',
            },
        ],
        careers: [
            { title: 'Teknisi Otomasi & PLC', desc: 'Programmer dan pemelihara sistem kontrol otomatis pada pabrik modern.' },
            { title: 'Teknisi Elektronika & Maintenance', desc: 'Spesialis perbaikan instrumentasi dan kelistrikan industri manufaktur.' },
            { title: 'IoT & Hardware Developer', desc: 'Pengembang perangkat keras pintar berbasis sensor dan koneksi internet.' },
            { title: 'Wirausaha Servis Elektronik', desc: 'Penyedia solusi perbaikan peralatan elektronik dan otomasi rumah/kantor.' },
        ],
        partners: ['Panasonic', 'Schneider Electric', 'Omron', 'Polytron', 'Siemens'],
    },
    animasi: {
        badge: 'Seni Digital & Animasi',
        gradient: 'from-violet-600 via-purple-600 to-fuchsia-500',
        glow: 'rgba(147, 51, 234, 0.25)',
        accentBg: 'bg-purple-500/10 text-purple-400 border-purple-500/20',
        accentBadge: 'bg-purple-500/20 text-purple-300 border-purple-400/30',
        heroSubtitle: 'Melahirkan kreator animasi 2D/3D, ilustrator, dan desainer aset grafis yang siap bersaing di industri kreatif global.',
        icon: Palette,
        competencies: [
            {
                icon: Palette,
                title: 'Konsep Seni & Desain Karakter',
                desc: 'Penciptaan karakter original, ekspresi, siluet, dan visual background lingkungan cerita.',
            },
            {
                icon: Images,
                title: 'Animasi 2D Tradisional & Digital',
                desc: 'Prinsip 12 animasi, frame-by-frame, puppet rigging, dan visual storytelling dinamis.',
            },
            {
                icon: Layers,
                title: 'Modeling 3D & Rigging',
                desc: 'Pembuatan objek 3 dimensi, texturing, skeleton rigging, dan pencahayaan studio 3D.',
            },
            {
                icon: Sparkles,
                title: 'Compositing & Visual Effects (VFX)',
                desc: 'Penyatuan render layer, efek visual partikel, sound design, dan final rendering video.',
            },
        ],
        careers: [
            { title: '2D / 3D Animator', desc: 'Kreator pergerakan karakter untuk film animasi, iklan TV, dan media digital.' },
            { title: '3D Modeler & Texture Artist', desc: 'Pembuat aset model 3 dimensi untuk industri game, VR/AR, dan film.' },
            { title: 'Concept Artist & Storyboarder', desc: 'Perancang visual visualisasi naskah cerita dan layout adegan animasi.' },
            { title: 'Freelance Digital Artist', desc: 'Kreator mandiri penyedia jasa ilustrasi dan aset grafis pasar internasional.' },
        ],
        partners: ['Infinite Frameworks Studios', 'MSV Studio', 'Studio Animasi Indonesia', 'Wacom'],
    },
    kuliner: {
        badge: 'Tata Boga & Seni Kuliner',
        gradient: 'from-amber-600 via-orange-500 to-yellow-500',
        glow: 'rgba(217, 119, 6, 0.25)',
        accentBg: 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        accentBadge: 'bg-amber-500/20 text-amber-300 border-amber-400/30',
        heroSubtitle: 'Mendidik chef berbakat yang menguasai seni pengolahan hidangan nusantara & internasional, pastry, bakery, serta manajemen restoran.',
        icon: ChefHat,
        competencies: [
            {
                icon: ChefHat,
                title: 'Pengolahan Hidangan Nusantara & Kontinental',
                desc: 'Teknik memasak otentik nusantara, western cuisine, saus dasar, dan seni plating mewah.',
            },
            {
                icon: Sparkles,
                title: 'Pastry, Bakery & Cake Decoration',
                desc: 'Pembuatan aneka roti, kue kering, pastry berlapis, dan seni dekorasi kue modern.',
            },
            {
                icon: Shield,
                title: 'Sanitasi, Higiene & Keamanan Pangan',
                desc: 'Penerapan standar operasional HACCP, kebersihan dapur komersial, dan keamanan pangan.',
            },
            {
                icon: Building2,
                title: 'Manajemen Restoran & Food Costing',
                desc: 'Perhitungan harga pokok produksi, pelayanan banquet meja makan, dan kewirausahaan boga.',
            },
        ],
        careers: [
            { title: 'Chef / Demi Chef de Partie', desc: 'Koki profesional di hotel berbintang, restoran fine dining, atau kapal pesiar.' },
            { title: 'Pastry & Bakery Chef', desc: 'Ahli pembuatan roti dan kue di bakery premium, cafe, atau industri perhotelan.' },
            { title: 'Food Entrepreneur / Caterer', desc: 'Pemilik usaha kuliner mandiri, bakery shop, cafe modern, atau katering pesta.' },
            { title: 'Food Stylist & Content Creator', desc: 'Penata tampilan makanan untuk fotografi komersial, majalah, dan media sosial.' },
        ],
        partners: ['Aston Hotel Group', 'Santika Hotel', 'Indonesian Chef Association', 'Boga Group'],
    },
};

// Current active profile
const currentSlugKey = computed(() => {
    const s = (props.slug || props.program?.slug || '').toLowerCase().trim();
    if (s === 'tsm') return 'tbsm';
    return s;
});

const currentTheme = computed(() => {
    return PROGRAM_THEMES[currentSlugKey.value] || {
        badge: 'Program Keahlian',
        gradient: 'from-blue-600 via-indigo-600 to-cyan-500',
        glow: 'rgba(37, 99, 235, 0.25)',
        accentBg: 'bg-blue-500/10 text-blue-400 border-blue-500/20',
        accentBadge: 'bg-blue-500/20 text-blue-300 border-blue-400/30',
        heroSubtitle: 'Program keahlian unggulan berorientasi kerja dengan kurikulum terpadu dan pembelajaran berbasis proyek nyata.',
        icon: GraduationCap,
        competencies: [
            { icon: BookOpen, title: 'Teori & Konsep Fundamental', desc: 'Pemahaman materi dasar kejuruan yang komprehensif.' },
            { icon: Wrench, title: 'Praktik Keterampilan Intensif', desc: 'Pelatihan praktik terpadu dengan alat berstandar industri.' },
            { icon: Award, title: 'Uji Kompetensi & Sertifikasi', desc: 'Sertifikasi keahlian terstandarisasi UKK dan industri.' },
            { icon: BriefcaseBusiness, title: 'Kesiapan Kerja & Magang', desc: 'Program magang nyata di dunia usaha dan dunia industri.' },
        ],
        careers: [
            { title: 'Tenaga Kerja Profesional', desc: 'Siap langsung bekerja di berbagai sektor industri relevan.' },
            { title: 'Wirausaha Mandiri', desc: 'Membangun usaha mandiri dengan keterampilan praktis.' },
            { title: 'Melanjutkan Studi Vokasi', desc: 'Peluang melanjutkan ke perguruan tinggi teknik dan politeknik.' },
        ],
        partners: ['Dunia Usaha & Industri Terkait'],
    };
});

// Dynamic Icon Mapping for Custom Competencies
const ICON_MAP = {
    wrench: Wrench,
    cpu: Cpu,
    zap: Zap,
    layers: Layers,
    hammer: Hammer,
    shield: Shield,
    palette: Palette,
    chefhat: ChefHat,
    factory: Factory,
    sparkles: Sparkles,
    target: Target,
    bookopen: BookOpen,
    award: Award,
    briefcasebusiness: BriefcaseBusiness,
    car: Car,
    graduationcap: GraduationCap,
};

const resolveIconComponent = (icon) => {
    if (!icon) return Wrench;
    if (typeof icon === 'object' && icon !== null) return icon;
    const key = icon.toString().toLowerCase().replace(/[^a-z0-9]/g, '');
    return ICON_MAP[key] || Wrench;
};

const activeCompetencies = computed(() => {
    const custom = props.program?.competencies;
    if (Array.isArray(custom) && custom.length > 0) {
        return custom.map((item) => ({
            icon: resolveIconComponent(item.icon),
            title: item.title || '',
            desc: item.desc || '',
        }));
    }
    return currentTheme.value.competencies;
});

const activeCareers = computed(() => {
    const custom = props.program?.careers;
    if (Array.isArray(custom) && custom.length > 0) {
        return custom.map((item) => ({
            title: item.title || '',
            desc: item.desc || '',
        }));
    }
    return currentTheme.value.careers;
});

// Activity Gallery Normalization
const normalizeActivityImage = (item) => {
    if (typeof item === 'string') {
        const url = item.trim();
        return url ? { url, caption: '' } : null;
    }
    if (item && typeof item === 'object') {
        const url = (item.url ?? '').toString().trim();
        if (!url) return null;
        return {
            url,
            caption: (item.caption ?? '').toString().trim(),
        };
    }
    return null;
};

const activityImages = computed(() => {
    const items = Array.isArray(props.program?.activity_images) ? props.program.activity_images : [];
    return items.map(normalizeActivityImage).filter(Boolean);
});
const hasActivities = computed(() => activityImages.value.length > 0);

const isGalleryExpanded = ref(false);
const INITIAL_GALLERY_LIMIT = 6;

const displayedActivityImages = computed(() => {
    if (isGalleryExpanded.value || activityImages.value.length <= INITIAL_GALLERY_LIMIT) {
        return activityImages.value;
    }
    return activityImages.value.slice(0, INITIAL_GALLERY_LIMIT);
});

// Other programs for Quick Switcher
const otherPrograms = computed(() => {
    const list = page.props.keahlian_menu || [];
    return list.filter((p) => p.slug !== props.slug && p.slug !== currentSlugKey.value);
});

// Sanitize Quill HTML
const descriptionRaw = computed(() => (props.program?.description ?? '').toString());
const industryRaw = computed(() => (props.program?.industry_class_content ?? '').toString());

const sanitizeQuillHtmlForPublic = (html) => {
    let out = (html ?? '').toString();
    out = out.replace(/\sclass\s*=\s*(["'])([\s\S]*?)\1/gi, (_m, quote, value) => {
        const keep = (value ?? '')
            .toString()
            .split(/\s+/)
            .map((t) => t.trim())
            .filter(Boolean)
            .filter((t) => /^ql-align-(right|center|justify)$/.test(t));
        if (keep.length === 0) return '';
        return ` class=${quote}${keep.join(' ')}${quote}`;
    });
    out = out.replace(/\sstyle\s*=\s*(["'])([\s\S]*?)\1/gi, (_m, quote, value) => {
        const declarations = (value ?? '').toString().split(';').map((s) => s.trim()).filter(Boolean);
        const map = {};
        for (const decl of declarations) {
            const [rawKey, ...rest] = decl.split(':');
            const key = (rawKey ?? '').trim().toLowerCase();
            const val = rest.join(':').trim();
            if (!key || !val) continue;
            map[key] = val;
        }
        const align = (map['text-align'] ?? '').toLowerCase();
        const safeAlign = ['left', 'right', 'center', 'justify'].includes(align) ? align : '';
        if (!safeAlign) return '';
        return ` style=${quote}text-align:${safeAlign}${quote}`;
    });
    out = out.replace(/<span>\s*<\/span>/gi, '');
    return out;
};

const descriptionHtml = computed(() => sanitizeQuillHtmlForPublic(descriptionRaw.value));
const industryHtml = computed(() => sanitizeQuillHtmlForPublic(industryRaw.value));

const looksLikeRichHtml = (value) => /<(p|br|h[1-6]|ul|ol|li|img|blockquote|strong|em|u|a)\b/i.test((value ?? '').toString());
const hasMeaningfulContent = (value) => {
    const v = (value ?? '').toString().trim();
    if (v === '') return false;
    if (/<img\b/i.test(v)) return true;
    const text = v
        .replace(/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi, ' ')
        .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, ' ')
        .replace(/<[^>]*>/g, ' ')
        .replace(/&nbsp;/gi, ' ')
        .replace(/\s+/g, ' ')
        .trim();
    return text.length > 0;
};

const descriptionHasHtml = computed(() => looksLikeRichHtml(descriptionHtml.value) && hasMeaningfulContent(descriptionHtml.value));
const industryHasHtml = computed(() => looksLikeRichHtml(industryHtml.value) && hasMeaningfulContent(industryHtml.value));
const hasIndustryClass = computed(() => hasMeaningfulContent(industryHtml.value));

const descriptionParagraphs = computed(() => {
    const text = descriptionRaw.value;
    return text.split('\n').map((p) => p.trim()).filter((p) => p.length > 0);
});

const industryParagraphs = computed(() => {
    const text = industryRaw.value;
    return text.split('\n').map((p) => p.trim()).filter((p) => p.length > 0);
});
</script>

<template>
    <Head :title="program?.name ? `${program.name} - Keahlian STEMDA` : 'Program Keahlian'" />

    <MainLayout>
        <!-- 404 State if program not found -->
        <section v-if="!program" class="py-32 bg-slate-950 min-h-screen text-center flex items-center justify-center">
            <div class="max-w-lg mx-auto px-6 py-12 rounded-[2.5rem] bg-slate-900/80 border border-white/10 backdrop-blur-xl shadow-2xl">
                <div class="h-16 w-16 mx-auto mb-6 rounded-2xl bg-rose-500/20 text-rose-400 grid place-items-center border border-rose-500/30">
                    <Compass class="h-8 w-8 animate-spin" style="animation-duration: 8s;" />
                </div>
                <h1 class="text-2xl font-black text-white mb-2">Program Tidak Ditemukan</h1>
                <p class="text-slate-400 text-sm mb-8 leading-relaxed">
                    Data keahlian dengan slug "<span class="text-white font-mono font-bold">{{ slug }}</span>" belum terdaftar atau sedang tidak aktif.
                </p>
                <Link
                    :href="route('home')"
                    class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm shadow-lg shadow-blue-500/30 hover:scale-[1.02] transition-all"
                >
                    <ArrowLeft class="w-4 h-4" />
                    Kembali ke Beranda
                </Link>
            </div>
        </section>

        <!-- Main Modern Content -->
        <div v-else class="bg-slate-950 text-slate-100 selection:bg-brand-500 selection:text-white min-h-screen overflow-x-hidden">
            <!-- ================= HERO SECTION ================= -->
            <section class="relative pt-8 pb-16 md:pt-14 md:pb-24 overflow-hidden">
                <!-- Ambient Ambient Glow Backgrounds -->
                <div
                    class="absolute -top-32 -left-32 w-[35rem] h-[35rem] rounded-full blur-[120px] pointer-events-none opacity-40 transition-colors duration-1000"
                    :style="{ background: currentTheme.glow }"
                ></div>
                <div
                    class="absolute top-1/3 -right-32 w-[30rem] h-[30rem] rounded-full blur-[120px] pointer-events-none opacity-30 bg-indigo-600/30"
                ></div>
                <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none opacity-40"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <!-- Breadcrumbs & Badge Bar -->
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
                        <Link
                            :href="route('home')"
                            class="inline-flex items-center gap-2 text-xs md:text-sm font-semibold text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 px-4 py-2 rounded-xl border border-white/10 backdrop-blur transition-all"
                        >
                            <ArrowLeft class="w-4 h-4 text-slate-300" />
                            <span>Beranda / Keahlian</span>
                        </Link>

                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border text-[11px] font-extrabold uppercase tracking-widest" :class="currentTheme.accentBadge">
                            <Sparkles class="w-3.5 h-3.5" />
                            <span>{{ currentTheme.badge }}</span>
                        </div>
                    </div>

                    <!-- Main Hero Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <!-- Left Column: Title, Subtitle, Highlights (order-2 on mobile, order-1 on desktop) -->
                        <div class="order-2 lg:order-1 lg:col-span-7 space-y-6 text-center lg:text-left">
                            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-2xl bg-white/[0.07] border border-white/15 backdrop-blur shadow-inner">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr grid place-items-center text-white shadow-md" :class="currentTheme.gradient">
                                    <component :is="currentTheme.icon" class="h-5 w-5" />
                                </div>
                                <div class="text-left">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Kompetensi Keahlian</span>
                                    <h4 class="text-sm font-bold text-white leading-none">{{ program.name }}</h4>
                                </div>
                            </div>

                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.1]">
                                {{ program.name }}
                            </h1>

                            <p class="text-slate-300 text-sm sm:text-base md:text-lg leading-relaxed max-w-2xl font-normal mx-auto lg:mx-0">
                                {{ program.excerpt || currentTheme.heroSubtitle }}
                            </p>

                            <!-- Key Quick Stats Pill Banner -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 text-left">
                                <div class="p-3.5 rounded-2xl bg-white/[0.05] border border-white/10 backdrop-blur">
                                    <div class="flex items-center gap-2 text-slate-400 text-xs font-bold mb-1">
                                        <Clock class="w-3.5 h-3.5 text-blue-400" />
                                        <span>Masa Studi</span>
                                    </div>
                                    <div class="text-white font-extrabold text-base">{{ program.duration || '3 Tahun' }}</div>
                                </div>

                                <div class="p-3.5 rounded-2xl bg-white/[0.05] border border-white/10 backdrop-blur">
                                    <div class="flex items-center gap-2 text-slate-400 text-xs font-bold mb-1">
                                        <Target class="w-3.5 h-3.5 text-emerald-400" />
                                        <span>Rasio Praktik</span>
                                    </div>
                                    <div class="text-white font-extrabold text-base">70% Praktik</div>
                                </div>

                                <div class="p-3.5 rounded-2xl bg-white/[0.05] border border-white/10 backdrop-blur">
                                    <div class="flex items-center gap-2 text-slate-400 text-xs font-bold mb-1">
                                        <Award class="w-3.5 h-3.5 text-amber-400" />
                                        <span>Sertifikasi</span>
                                    </div>
                                    <div class="text-white font-extrabold text-base">UKK & Industri</div>
                                </div>

                                <div class="p-3.5 rounded-2xl bg-white/[0.05] border border-white/10 backdrop-blur">
                                    <div class="flex items-center gap-2 text-slate-400 text-xs font-bold mb-1">
                                        <BadgeCheck class="w-3.5 h-3.5 text-cyan-400" />
                                        <span>Akreditasi</span>
                                    </div>
                                    <div class="text-white font-extrabold text-base">Unggul (A)</div>
                                </div>
                            </div>

                            <!-- CTA Button Action Row -->
                            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-4">
                                <Link
                                    :href="route('contact')"
                                    class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl text-white font-black text-sm shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95"
                                    :class="`bg-gradient-to-r ${currentTheme.gradient}`"
                                >
                                    <span>Konsultasi & Info PPDB</span>
                                    <ArrowRight class="w-4 h-4" />
                                </Link>

                                <a
                                    href="#kompetensi"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/15 text-white font-bold text-sm border border-white/15 backdrop-blur transition-all active:scale-95"
                                >
                                    <span>Lihat Kurikulum</span>
                                </a>
                            </div>
                        </div>

                        <!-- Right Column: Logo (order-1 on mobile, order-2 on desktop) -->
                        <div class="order-1 lg:order-2 lg:col-span-5 flex items-center justify-center relative">
                            <div class="relative w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg mx-auto flex items-center justify-center py-2 lg:py-4 group">
                                <!-- Ambient Aura Glow behind Logo -->
                                <div
                                    class="absolute w-44 h-44 sm:w-64 sm:h-64 lg:w-96 lg:h-96 rounded-full opacity-60 blur-3xl pointer-events-none transition-all duration-700 group-hover:opacity-90 group-hover:scale-110"
                                    :style="{ background: currentTheme.glow || 'rgba(59, 130, 246, 0.45)' }"
                                ></div>

                                <!-- Floating Logo with responsive max-height -->
                                <div class="relative z-10 w-full flex items-center justify-center">
                                    <img
                                        v-if="program.image"
                                        :src="program.image"
                                        :alt="program.name"
                                        class="w-auto max-h-[180px] sm:max-h-[240px] md:max-h-[320px] lg:max-h-[460px] object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.85)] transition-all duration-700 group-hover:scale-105"
                                    />
                                    <div v-else class="text-center p-6">
                                        <div class="h-24 w-24 sm:h-32 sm:w-32 lg:h-40 lg:w-40 mx-auto mb-4 rounded-3xl bg-gradient-to-br grid place-items-center text-white shadow-2xl" :class="currentTheme.gradient">
                                            <component :is="currentTheme.icon" class="h-12 w-12 sm:h-16 sm:w-16 lg:h-20 lg:w-20" />
                                        </div>
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">SMKS Muhammadiyah 2 Genteng</p>
                                        <p class="text-xl font-black text-white mt-1">{{ program.name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= PROGRAM OVERVIEW & EDITORIAL ================= -->
            <section class="py-16 md:py-20 bg-slate-900/40 border-y border-white/10 relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                        <!-- Left Summary Column -->
                        <div class="lg:col-span-4 space-y-4">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest border" :class="currentTheme.accentBadge">
                                <BookOpen class="w-3.5 h-3.5" />
                                <span>Tentang Keahlian</span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                                Profil & Kurikulum Pembelajaran
                            </h2>
                            <p class="text-slate-400 text-sm leading-relaxed">
                                Kurikulum diselaraskan langsung dengan kebutuhan dunia usaha dan dunia industri (DUDI) untuk menjamin kompetensi lulusan.
                            </p>
                        </div>

                        <!-- Right Rich Content Column -->
                        <div class="lg:col-span-8">
                            <div class="p-6 md:p-8 rounded-[2rem] bg-white/[0.04] border border-white/10 backdrop-blur-xl shadow-xl">
                                <div
                                    v-if="descriptionHasHtml"
                                    class="rich-content text-slate-200 leading-relaxed text-base [&_h2]:text-xl [&_h2]:font-black [&_h2]:text-white [&_h2]:mt-6 [&_h3]:text-lg [&_h3]:font-bold [&_h3]:text-white [&_h3]:mt-4 [&_p]:mt-4 [&_p:first-child]:mt-0 [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:mt-4 [&_ol]:list-decimal [&_ol]:pl-5 [&_li]:mt-2 [&_li]:text-slate-300 [&_a]:text-cyan-400 [&_a]:underline [&_img]:mt-5 [&_img]:rounded-2xl [&_img]:border [&_img]:border-white/15 [&_blockquote]:border-l-4 [&_blockquote]:border-blue-500 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-slate-300"
                                    v-html="descriptionHtml"
                                ></div>

                                <div v-else class="space-y-4 text-slate-200 text-base leading-relaxed">
                                    <p v-for="(p, idx) in descriptionParagraphs" :key="`desc-p-${idx}`">
                                        {{ p }}
                                    </p>
                                    <p v-if="descriptionParagraphs.length === 0" class="text-slate-400 italic">
                                        Informasi detail mengenai kurikulum dan silabus keahlian ini dapat langsung dikonsultasikan melalui layanan informasi sekolah.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= PILAR KOMPETENSI (ID: kompetensi) ================= -->
            <section id="kompetensi" class="py-16 md:py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16 space-y-3">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest border" :class="currentTheme.accentBadge">
                            <Target class="w-3.5 h-3.5" />
                            <span>Fokus Utama</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight">
                            Kompetensi Keahlian yang Dipelajari
                        </h2>
                        <p class="text-slate-400 text-sm md:text-base">
                            Materi terstruktur berbasis project-based learning untuk menguasai keterampilan nyata di dunia kerja.
                        </p>
                    </div>

                    <!-- Competency Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div
                            v-for="(item, idx) in activeCompetencies"
                            :key="`comp-${idx}`"
                            class="group relative rounded-[2rem] bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 p-6 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10 flex flex-col justify-between"
                        >
                            <div>
                                <div class="h-12 w-12 rounded-2xl bg-white/10 border border-white/10 grid place-items-center text-white mb-5 group-hover:scale-110 transition-transform" :class="currentTheme.accentBg">
                                    <component :is="item.icon" class="h-6 w-6 text-white" />
                                </div>
                                <h3 class="text-lg font-black text-white leading-tight mb-2 group-hover:text-cyan-300 transition-colors">
                                    {{ item.title }}
                                </h3>
                                <p class="text-slate-400 text-xs md:text-sm leading-relaxed">
                                    {{ item.desc }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between text-[11px] font-extrabold text-slate-500 uppercase tracking-widest">
                                <span>Modul 0{{ idx + 1 }}</span>
                                <CheckCircle2 class="w-4 h-4 text-emerald-400 opacity-60 group-hover:opacity-100 transition-opacity" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= KELAS INDUSTRI SECTION ================= -->
            <section v-if="hasIndustryClass" class="py-16 md:py-20 bg-slate-900/60 border-y border-white/10 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="relative rounded-[2.5rem] overflow-hidden border border-white/20 shadow-2xl">
                        <!-- Decorative Industrial Gradient Canvas -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-slate-900 to-indigo-950/90"></div>
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_10%_20%,rgba(59,130,246,0.3)_0%,transparent_50%),radial-gradient(circle_at_90%_80%,rgba(16,185,129,0.2)_0%,transparent_50%)]"></div>

                        <div class="relative p-6 sm:p-10 md:p-14 z-10">
                            <div class="max-w-3xl mb-8 space-y-3">
                                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/20 text-[11px] font-extrabold uppercase tracking-widest text-cyan-300">
                                    <Factory class="w-3.5 h-3.5" />
                                    <span>Program Unggulan Binaan</span>
                                </div>
                                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                                    Kelas Industri & Standarisasi DU/DI
                                </h2>
                                <p class="text-slate-300 text-sm md:text-base leading-relaxed">
                                    Siswa dipersiapkan langsung oleh instruktur industri dengan peralatan dan kurikulum yang sinkron dengan kebutuhan operasional perusahaan.
                                </p>
                            </div>

                            <div class="p-6 md:p-8 rounded-2xl bg-slate-950/60 border border-white/15 backdrop-blur-xl">
                                <div
                                    v-if="industryHasHtml"
                                    class="rich-content text-slate-200 leading-relaxed text-sm md:text-base [&_p]:mt-3 [&_p:first-child]:mt-0 [&_ul]:mt-3 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:mt-3 [&_ol]:list-decimal [&_ol]:pl-5 [&_li]:mt-1.5 [&_a]:text-cyan-300 [&_a]:underline"
                                    v-html="industryHtml"
                                ></div>

                                <div v-else class="space-y-3 text-slate-200 text-sm md:text-base leading-relaxed">
                                    <p v-for="(p, idx) in industryParagraphs" :key="`ind-p-${idx}`">
                                        {{ p }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= GALERI KEGIATAN PRAKTIK ================= -->
            <section v-if="hasActivities" class="py-16 md:py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                        <div class="space-y-2">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest border" :class="currentTheme.accentBadge">
                                <Images class="w-3.5 h-3.5" />
                                <span>Dokumentasi Nyata</span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                                Galeri Aktivitas & Praktik Siswa
                            </h2>
                        </div>
                        <p class="text-slate-400 text-xs sm:text-sm max-w-md">
                            Suasana pembelajaran aktif di bengkel kerja, laboratorium, dan proyek kejuruan {{ program.name }}.
                        </p>
                    </div>
                </div>

                <!-- Gallery Carousel / Grid Container -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="(img, idx) in displayedActivityImages"
                            :key="`act-img-${idx}`"
                            @click="openActivityLightbox(idx)"
                            class="group relative rounded-[2rem] overflow-hidden bg-slate-900 border border-white/10 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 cursor-pointer"
                        >
                            <div class="relative aspect-[4/3] overflow-hidden bg-slate-950">
                                <img
                                    :src="img.url"
                                    :alt="img.caption || `Aktivitas ${program.name} ${idx + 1}`"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    loading="lazy"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>

                                <div class="absolute top-3 right-3 h-9 w-9 rounded-full bg-slate-900/80 border border-white/20 text-white grid place-items-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <Maximize2 class="h-4 w-4" />
                                </div>

                                <div class="absolute bottom-0 inset-x-0 p-5">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-cyan-400">Dokumentasi {{ idx < 9 ? '0' + (idx + 1) : (idx + 1) }}</span>
                                    <p class="text-white text-sm font-bold mt-0.5 line-clamp-2 leading-snug">
                                        {{ img.caption || `Praktik Kerja ${program.name}` }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expand / Collapse Button for 6+ Images -->
                    <div v-if="activityImages.length > INITIAL_GALLERY_LIMIT" class="mt-10 text-center">
                        <button
                            type="button"
                            @click="isGalleryExpanded = !isGalleryExpanded"
                            class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/20 backdrop-blur-xl transition-all shadow-xl hover:scale-105 active:scale-95 group"
                        >
                            <span>{{ isGalleryExpanded ? 'Tampilkan Lebih Sedikit' : `Lihat Semua Foto (${activityImages.length} Dokumentasi)` }}</span>
                            <ChevronDown
                                class="w-4 h-4 transition-transform duration-300"
                                :class="{ 'rotate-180': isGalleryExpanded }"
                            />
                        </button>
                    </div>
                </div>
            </section>

            <!-- Activity Image Lightbox Modal -->
            <Teleport to="body">
                <Transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="activeActivityPhoto"
                        class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-xl p-4 sm:p-6"
                        @click.self="closeActivityLightbox"
                    >
                        <button
                            type="button"
                            @click="closeActivityLightbox"
                            aria-label="Tutup preview"
                            class="absolute top-5 right-5 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-white/20 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                        >
                            <X class="w-6 h-6" />
                        </button>

                        <button
                            v-if="activityImages.length > 1"
                            type="button"
                            @click="prevActivityPhoto"
                            aria-label="Foto sebelumnya"
                            class="absolute left-4 sm:left-6 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-brand-500 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                        >
                            <ChevronLeft class="w-6 h-6" />
                        </button>

                        <button
                            v-if="activityImages.length > 1"
                            type="button"
                            @click="nextActivityPhoto"
                            aria-label="Foto selanjutnya"
                            class="absolute right-4 sm:right-6 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-brand-500 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                        >
                            <ChevronRight class="w-6 h-6" />
                        </button>

                        <div class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center" @click.stop>
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/15 max-h-[75vh] bg-black/40 flex items-center justify-center">
                                <img
                                    :src="activeActivityPhoto.url"
                                    :alt="activeActivityPhoto.caption || program.name"
                                    class="max-h-[75vh] w-auto max-w-full object-contain rounded-3xl"
                                />
                            </div>

                            <div class="mt-4 w-full max-w-2xl px-4 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center text-white flex items-center justify-between gap-4">
                                <div class="text-left min-w-0 flex-1">
                                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-cyan-300">
                                        {{ program.name }}
                                    </span>
                                    <h4 class="text-sm sm:text-base font-bold truncate">{{ activeActivityPhoto.caption || 'Dokumentasi Praktik Kejuruan' }}</h4>
                                </div>
                                <div class="text-xs font-semibold text-slate-300 shrink-0 px-3 py-1 rounded-full bg-white/10">
                                    {{ (activeGalleryIndex || 0) + 1 }} / {{ activityImages.length }}
                                </div>
                            </div>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <!-- ================= PROSPEK KARIR LULUSAN ================= -->
            <section class="py-16 md:py-20 bg-slate-900/40 border-t border-white/10 relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest border" :class="currentTheme.accentBadge">
                            <BriefcaseBusiness class="w-3.5 h-3.5" />
                            <span>Peluang Masa Depan</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Prospek Karir & Peluang Kerja Lulusan
                        </h2>
                        <p class="text-slate-400 text-sm md:text-base">
                            Keahlian yang diajarkan membuka jalan luas untuk langsung bekerja di industri, berwirausaha mandiri, maupun melanjutkan studi.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div
                            v-for="(career, idx) in activeCareers"
                            :key="`career-${idx}`"
                            class="p-6 rounded-[2rem] bg-white/[0.04] border border-white/10 backdrop-blur-xl hover:border-cyan-500/30 transition-all hover:bg-white/[0.07]"
                        >
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 grid place-items-center text-white mb-4 shadow-md">
                                <BriefcaseBusiness class="h-5 w-5" />
                            </div>
                            <h3 class="text-base font-extrabold text-white mb-2">{{ career.title }}</h3>
                            <p class="text-slate-400 text-xs md:text-sm leading-relaxed">{{ career.desc }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ================= QUICK PROGRAM SWITCHER ================= -->
            <section v-if="otherPrograms.length > 0" class="py-14 bg-slate-950 border-t border-white/10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                        <div>
                            <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Jelajahi Pilihan Lain</span>
                            <h3 class="text-xl font-black text-white">Program Keahlian Lainnya di STEMDA</h3>
                        </div>
                    </div>

                    <!-- Switcher Grid Cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <Link
                            v-for="other in otherPrograms"
                            :key="other.slug"
                            :href="route('keahlian.show', { slug: other.slug })"
                            class="group p-4 rounded-2xl bg-white/[0.04] hover:bg-white/[0.09] border border-white/10 hover:border-white/20 transition-all duration-200 flex items-center justify-between"
                        >
                            <div class="min-w-0 pr-2">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Keahlian</p>
                                <p class="text-sm font-bold text-white group-hover:text-cyan-300 truncate transition-colors">{{ other.name }}</p>
                            </div>
                            <div class="h-8 w-8 rounded-lg bg-white/5 group-hover:bg-blue-600/30 text-slate-400 group-hover:text-white grid place-items-center shrink-0 transition-colors">
                                <ChevronRight class="w-4 h-4" />
                            </div>
                        </Link>
                    </div>
                </div>
            </section>

            <!-- ================= BOTTOM CTA BANNER ================= -->
            <section class="py-16 md:py-20 relative overflow-hidden bg-slate-900">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="text-center p-8 sm:p-12 md:p-16 rounded-[2.5rem] bg-gradient-to-r from-blue-900/60 via-indigo-900/60 to-purple-900/60 border border-white/20 shadow-2xl backdrop-blur-2xl space-y-6">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-xs font-extrabold uppercase tracking-widest text-cyan-300">
                            <Sparkles class="w-4 h-4" />
                            <span>Penerimaan Peserta Didik Baru (PPDB)</span>
                        </div>

                        <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                            Siap Raih Masa Depan Hebat Bersama {{ program.name }}?
                        </h2>

                        <p class="text-slate-300 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
                            Daftarkan diri Anda atau konsultasikan minat bakat Anda langsung dengan tim konseling kejuruan SMK Negeri STEMDA.
                        </p>

                        <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                            <Link
                                :href="route('contact')"
                                class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-white text-slate-950 font-black text-sm shadow-xl hover:bg-slate-100 hover:scale-105 transition-all duration-300"
                            >
                                <MessageCircle class="w-4 h-4 text-blue-600" />
                                <span>Hubungi Panitia PPDB</span>
                            </Link>

                            <Link
                                :href="route('about')"
                                class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-white/10 hover:bg-white/15 text-white font-bold text-sm border border-white/20 backdrop-blur transition-all"
                            >
                                <School class="w-4 h-4" />
                                <span>Profil Sekolah</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </MainLayout>
</template>

<style scoped>
/* Scoped adjustments for optimal responsiveness and typography */
.rich-content :deep(img) {
    max-width: 100%;
    height: auto;
    border-radius: 1rem;
    margin-top: 1rem;
    margin-bottom: 1rem;
}

.rich-content :deep(p) {
    margin-top: 0.75rem;
    margin-bottom: 0.75rem;
}

.rich-content :deep(ul),
.rich-content :deep(ol) {
    padding-left: 1.25rem;
    margin-top: 0.75rem;
    margin-bottom: 0.75rem;
}
</style>
