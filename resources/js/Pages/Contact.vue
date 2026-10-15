<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import {
    Calendar,
    CheckCircle2,
    Clock,
    Download,
    ExternalLink,
    FileText,
    GraduationCap,
    HelpCircle,
    Info,
    Mail,
    MapPin,
    Maximize2,
    MessageCircle,
    Phone,
    Route as RouteIcon,
    School,
    Sparkles,
    Building2,
    Users,
    X,
    ChevronLeft,
    ChevronRight,
    ArrowRight,
    Images
} from 'lucide-vue-next';

const props = defineProps({
    ppdb: {
        type: Object,
        default: () => ({}),
    },
});

const isPosterModalOpen = ref(false);
const currentPosterIndex = ref(0);
const isHovered = ref(false);
let autoplayInterval = null;

const posterImagesList = computed(() => {
    if (Array.isArray(props.ppdb?.poster_images) && props.ppdb.poster_images.length > 0) {
        return props.ppdb.poster_images;
    }
    if (props.ppdb?.poster_image) {
        return [props.ppdb.poster_image];
    }
    return ['/storage/images/dinas.png'];
});

const currentPoster = computed(() => {
    return posterImagesList.value[currentPosterIndex.value] || posterImagesList.value[0];
});

const posterImage = computed(() => {
    return currentPoster.value;
});

const nextPoster = () => {
    if (posterImagesList.value.length <= 1) return;
    currentPosterIndex.value = (currentPosterIndex.value + 1) % posterImagesList.value.length;
};

const prevPoster = () => {
    if (posterImagesList.value.length <= 1) return;
    currentPosterIndex.value = (currentPosterIndex.value - 1 + posterImagesList.value.length) % posterImagesList.value.length;
};

const goToPoster = (index) => {
    currentPosterIndex.value = index;
};

const startAutoplay = () => {
    stopAutoplay();
    if (posterImagesList.value.length <= 1) return;
    // Autoplay interval: 6 detik per pergantian agar pengunjung nyaman membaca konten poster
    autoplayInterval = setInterval(() => {
        if (!isHovered.value && !isPosterModalOpen.value) {
            nextPoster();
        }
    }, 6000);
};

const stopAutoplay = () => {
    if (autoplayInterval) {
        clearInterval(autoplayInterval);
        autoplayInterval = null;
    }
};

const whatsappUrl = computed(() => {
    const rawNumber = (props.ppdb?.whatsapp_number || '081236453325').replace(/[^0-9]/g, '');
    const phone = rawNumber.startsWith('0') ? '62' + rawNumber.slice(1) : rawNumber;
    const text = encodeURIComponent(props.ppdb?.whatsapp_text || 'Halo Panitia PPDB SMKS Muhammadiyah 2 Genteng, saya ingin bertanya tentang pendaftaran siswa baru.');
    return `https://wa.me/${phone}?text=${text}`;
});

const registrationLink = computed(() => {
    return props.ppdb?.registration_link || whatsappUrl.value;
});

// Waves array helper
const wavesList = computed(() => {
    if (!props.ppdb?.waves) {
        return [
            { name: 'Gelombang 1', period: '1 Januari - 31 Maret 2026', note: 'Potongan Biaya Khusus & Bebas Tes Tertulis', status: 'Dibuka' },
            { name: 'Gelombang 2', period: '1 April - 31 Mei 2026', note: 'Seleksi Minat Bakat Kejuruan', status: 'Segera' },
            { name: 'Gelombang 3', period: '1 Juni - 10 Juli 2026', note: 'Penutupan Kuota Kelas Reguler', status: 'Segera' },
        ];
    }

    return props.ppdb.waves
        .split('\n')
        .map(line => line.trim())
        .filter(Boolean)
        .map((line, idx) => {
            const parts = line.split(':');
            return {
                name: parts[0]?.trim() || `Gelombang ${idx + 1}`,
                period: parts[1]?.trim() || line,
                note: idx === 0 ? 'Pendaftaran Sedang Berlangsung' : 'Tahap Berikutnya',
                status: idx === 0 ? 'Dibuka' : 'Segera'
            };
        });
});

// Requirements array helper
const requirementsList = computed(() => {
    if (!props.ppdb?.requirements) {
        return [
            'Fotokopi Ijazah / Surat Keterangan Lulus (SKL) SMP/MTs dilegalisir (2 lembar)',
            'Fotokopi Kartu Keluarga (KK) & Akta Kelahiran (2 lembar)',
            'Fotokopi KTP Orang Tua / Wali (2 lembar)',
            'Pas Foto berwarna 3x4 background merah/biru (4 lembar)',
            'Fotokopi Kartu KIP / PKH / KIS (bagi yang memiliki)',
            'Mengisi formulir pendaftaran secara online atau di posko sekolah',
        ];
    }

    return props.ppdb.requirements
        .split('\n')
        .map(line => line.trim())
        .filter(Boolean);
});

// Steps array helper
const stepsList = computed(() => {
    if (!props.ppdb?.steps) {
        return [
            { step: '01', title: 'Pendaftaran Formulir', desc: 'Isi formulir pendaftaran secara online melalui website atau datang langsung ke posko PPDB STEMDA.' },
            { step: '02', title: 'Verifikasi Berkas', desc: 'Serahkan kelengkapan dokumen administrasi (Ijazah/SKL, KK, Akta, Pas Foto) ke tim verifikator.' },
            { step: '03', title: 'Tes Minat & Wawancara', desc: 'Mengikuti konsultasi peminatan program kejuruan dan wawancara singkat bersama wali murid.' },
            { step: '04', title: 'Daftar Ulang & Seragam', desc: 'Penerimaan surat tanda diterima, penyelesaian daftar ulang, dan pengukuran seragam praktik.' },
        ];
    }

    return props.ppdb.steps
        .split('\n')
        .map(line => line.trim())
        .filter(Boolean)
        .map((line, idx) => {
            const cleanText = line.replace(/^\d+[\.\)]\s*/, '');
            return {
                step: `0${idx + 1}`,
                title: cleanText.split('.')[0] || cleanText,
                desc: cleanText.split('.').slice(1).join('.').trim() || cleanText,
            };
        });
});

const openPosterModal = () => {
    isPosterModalOpen.value = true;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = 'hidden';
    }
};

const closePosterModal = () => {
    isPosterModalOpen.value = false;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
};

const handleKeyDown = (e) => {
    if (e.key === 'Escape' && isPosterModalOpen.value) {
        closePosterModal();
    } else if (e.key === 'ArrowRight') {
        nextPoster();
    } else if (e.key === 'ArrowLeft') {
        prevPoster();
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
    startAutoplay();
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    stopAutoplay();
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
});
</script>

<template>
    <Head title="Informasi PPDB & Pendaftaran Siswa Baru" />

    <MainLayout>
        <!-- Hero Section -->
        <div class="relative bg-slate-950 pt-28 pb-32 overflow-hidden">
            <!-- Ambient Lighting -->
            <div class="absolute inset-0 pointer-events-none opacity-30">
                <div class="absolute -top-48 -right-40 h-[38rem] w-[38rem] rounded-full blur-3xl bg-gradient-to-br from-brand-500 to-indigo-500 animate-pulse"></div>
                <div class="absolute -bottom-64 -left-40 h-[40rem] w-[40rem] rounded-full blur-3xl bg-gradient-to-tr from-cyan-500 to-emerald-500 animate-pulse"></div>
                <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:24px_24px]"></div>
            </div>

            <div
                class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10"
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :enter="{ opacity: 1, y: 0, transition: { duration: 700 } }"
            >
                <!-- Status Pill -->
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/[0.08] hover:bg-white/[0.12] border border-white/20 text-white text-xs sm:text-sm font-black uppercase tracking-widest backdrop-blur-xl mb-6 mx-auto shadow-2xl">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <Sparkles class="w-3.5 h-3.5 text-amber-300" />
                    <span>{{ ppdb?.hero_badge || 'Penerimaan Peserta Didik Baru 2026/2027' }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-white tracking-tight mb-6 leading-tight">
                    Informasi <span class="bg-gradient-to-r from-cyan-400 via-brand-400 to-indigo-300 bg-clip-text text-transparent">PPDB STEMDA</span>
                </h1>

                <p class="text-base sm:text-lg md:text-xl text-slate-300 max-w-3xl mx-auto font-normal leading-relaxed mb-10">
                    {{ ppdb?.hero_subtitle || 'Bergabunglah bersama SMKS Muhammadiyah 2 Genteng, sekolah vokasi pusat keunggulan siap kerja di era industri 4.0.' }}
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a
                        :href="registrationLink"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-brand-500 via-brand-600 to-blue-600 text-white font-black text-sm sm:text-base shadow-xl shadow-brand-600/35 hover:shadow-brand-500/50 hover:scale-105 active:scale-95 transition-all duration-300 group"
                    >
                        <span>Daftar Sekarang Online</span>
                        <ArrowRight class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                    </a>

                    <a
                        :href="whatsappUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2.5 px-7 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm sm:text-base shadow-xl shadow-emerald-600/30 hover:scale-105 active:scale-95 transition-all duration-300"
                    >
                        <MessageCircle class="w-5 h-5" />
                        <span>Chat WhatsApp Panitia</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Container -->
        <div class="py-16 md:py-24 bg-slate-50 relative -mt-10 rounded-t-[3rem] shadow-[0_-20px_40px_rgba(0,0,0,0.08)] min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">

                <!-- ================= SECTION 1: POSTER BROSUR SHOWCASE ================= -->
                <section class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center" v-motion-slide-visible-bottom>
                    <!-- Left: Poster Card with Lightbox Preview & Autoplay Carousel -->
                    <div class="lg:col-span-5 flex justify-center">
                        <div
                            class="relative w-full max-w-md group"
                            @mouseenter="isHovered = true"
                            @mouseleave="isHovered = false"
                        >
                            <div
                                class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80 bg-slate-900 cursor-pointer min-h-[380px] flex items-center justify-center select-none"
                                @click="openPosterModal"
                            >
                                <!-- Poster image with smooth fade transition -->
                                <Transition name="poster-fade" mode="out-in">
                                    <img
                                        :key="currentPoster"
                                        :src="currentPoster"
                                        :alt="`Poster PPDB SMKS Muhammadiyah 2 Genteng - ${currentPosterIndex + 1}`"
                                        class="w-full h-auto max-h-[540px] object-contain transition-transform duration-700 group-hover:scale-105"
                                    />
                                </Transition>

                                <!-- Top Badges: Counter & Autoplay status -->
                                <div class="absolute top-4 inset-x-4 flex items-center justify-between pointer-events-none z-10">
                                    <div v-if="posterImagesList.length > 1" class="px-3 py-1 rounded-full bg-slate-950/75 backdrop-blur-md border border-white/20 text-white font-bold text-xs flex items-center gap-1.5 shadow-lg">
                                        <Images class="w-3.5 h-3.5 text-brand-400" />
                                        <span>{{ currentPosterIndex + 1 }} / {{ posterImagesList.length }}</span>
                                    </div>
                                    <div v-else></div>

                                    <div v-if="posterImagesList.length > 1" class="px-2.5 py-1 rounded-full bg-slate-950/75 backdrop-blur-md border border-white/20 text-[10px] text-white/90 font-medium flex items-center gap-1.5 shadow-lg">
                                        <span class="w-2 h-2 rounded-full" :class="isHovered ? 'bg-amber-400' : 'bg-emerald-400 animate-pulse'"></span>
                                        <span>{{ isHovered ? 'Dijeda' : 'Otomatis' }}</span>
                                    </div>
                                </div>

                                <!-- Previous / Next Navigation Arrows (Only if > 1 poster) -->
                                <div v-if="posterImagesList.length > 1" class="absolute inset-y-0 inset-x-2 flex items-center justify-between pointer-events-none z-10">
                                    <button
                                        type="button"
                                        @click.stop="prevPoster"
                                        aria-label="Poster sebelumnya"
                                        class="pointer-events-auto h-10 w-10 rounded-full bg-slate-950/60 hover:bg-brand-600 text-white grid place-items-center backdrop-blur-md border border-white/20 shadow-lg transition-all transform hover:scale-110 active:scale-95 focus:outline-none"
                                    >
                                        <ChevronLeft class="w-5 h-5" />
                                    </button>
                                    <button
                                        type="button"
                                        @click.stop="nextPoster"
                                        aria-label="Poster selanjutnya"
                                        class="pointer-events-auto h-10 w-10 rounded-full bg-slate-950/60 hover:bg-brand-600 text-white grid place-items-center backdrop-blur-md border border-white/20 shadow-lg transition-all transform hover:scale-110 active:scale-95 focus:outline-none"
                                    >
                                        <ChevronRight class="w-5 h-5" />
                                    </button>
                                </div>

                                <!-- Dots Indicator at bottom -->
                                <div v-if="posterImagesList.length > 1" class="absolute bottom-4 inset-x-0 flex items-center justify-center gap-1.5 z-10 pointer-events-none">
                                    <button
                                        v-for="(_, idx) in posterImagesList"
                                        :key="idx"
                                        type="button"
                                        @click.stop="goToPoster(idx)"
                                        :aria-label="`Pindah ke poster ${idx + 1}`"
                                        class="pointer-events-auto h-2 rounded-full transition-all duration-300 focus:outline-none shadow"
                                        :class="currentPosterIndex === idx ? 'w-6 bg-brand-400' : 'w-2 bg-white/60 hover:bg-white'"
                                    ></button>
                                </div>

                                <!-- Hover Overlay for Lightbox Click -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-between p-6 pointer-events-none">
                                    <span class="text-white font-bold text-sm">Klik untuk Perbesar</span>
                                    <div class="h-10 w-10 rounded-2xl bg-brand-500 text-white grid place-items-center shadow-lg">
                                        <Maximize2 class="w-5 h-5" />
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-center gap-3">
                                <button
                                    type="button"
                                    @click="openPosterModal"
                                    class="px-5 py-2.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 font-bold text-xs shadow-sm flex items-center gap-2 transition-colors"
                                >
                                    <Maximize2 class="w-4 h-4 text-brand-600" />
                                    <span>Lihat Layar Penuh</span>
                                </button>
                                <a
                                    :href="currentPoster"
                                    :download="`Brosur-PPDB-STEMDA-${currentPosterIndex + 1}.jpg`"
                                    target="_blank"
                                    class="px-5 py-2.5 rounded-xl bg-brand-50 hover:bg-brand-100 border border-brand-200 text-brand-700 font-bold text-xs shadow-sm flex items-center gap-2 transition-colors"
                                >
                                    <Download class="w-4 h-4" />
                                    <span>Unduh Brosur</span>
                                </a>
                            </div>

                            <p v-if="posterImagesList.length > 1" class="text-center text-[11px] text-slate-500 mt-2.5 font-medium">
                                Poster berganti otomatis setiap 6 detik. Arahkan kursor / sentuh untuk menjeda membaca.
                            </p>
                        </div>
                    </div>

                    <!-- Right: Info Summary & Highlights -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest bg-brand-50 text-brand-700 border border-brand-200">
                            <School class="w-3.5 h-3.5" />
                            <span>Penerimaan Siswa Baru</span>
                        </div>

                        <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                            Wujudkan Masa Depan Gemilang Bersama STEMDA
                        </h2>

                        <p class="text-slate-600 text-base sm:text-lg leading-relaxed">
                            SMKS Muhammadiyah 2 Genteng membuka pendaftaran peserta didik baru tahun ajaran 2026/2027. Dapatkan pembelajaran vokasi berkualitas, fasilitas praktik standar industri internasional, dan jaminan penyaluran kerja melalui program BKK & TEFA.
                        </p>

                        <!-- Highlight Features Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-start gap-3.5">
                                <div class="h-10 w-10 rounded-xl bg-brand-50 text-brand-600 grid place-items-center shrink-0">
                                    <GraduationCap class="w-5 h-5" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900">5 Pilihan Kejuruan</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">TKR, TBSM, TEI, Animasi, dan Kuliner.</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-start gap-3.5">
                                <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 grid place-items-center shrink-0">
                                    <Building2 class="w-5 h-5" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900">Kelas Industri</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Kurikulum & sertifikasi standar mitra industri.</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-start gap-3.5">
                                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 grid place-items-center shrink-0">
                                    <CheckCircle2 class="w-5 h-5" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900">Pusat Keunggulan</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">SMK PK dengan kurikulum industri resmi.</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-start gap-3.5">
                                <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 grid place-items-center shrink-0">
                                    <Users class="w-5 h-5" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900">Bursa Kerja Khusus</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Kerjasama 50+ industri terkemuka.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= SECTION 2: GELOMBANG PENDAFTARAN ================= -->
                <section v-motion-slide-visible-bottom>
                    <div class="text-center max-w-3xl mx-auto mb-12">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest bg-indigo-50 text-indigo-700 border border-indigo-200 mb-3">
                            <Calendar class="w-3.5 h-3.5" />
                            <span>Jadwal Pendaftaran</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                            Gelombang & Waktu Pendaftaran
                        </h2>
                        <p class="text-slate-500 text-sm sm:text-base mt-2">
                            Pastikan Anda mendaftar lebih awal untuk mendapatkan kuota jurusan pilihan serta fasilitas khusus.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="(wave, idx) in wavesList"
                            :key="idx"
                            class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 relative flex flex-col justify-between group"
                        >
                            <div>
                                <div class="flex items-center justify-between gap-3 mb-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider"
                                        :class="idx === 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'"
                                    >
                                        {{ wave.status }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-400">Tahap 0{{ idx + 1 }}</span>
                                </div>

                                <h3 class="text-xl font-black text-slate-900 group-hover:text-brand-600 transition-colors">
                                    {{ wave.name }}
                                </h3>

                                <div class="mt-4 flex items-start gap-2.5 text-sm font-semibold text-slate-700">
                                    <Clock class="w-4 h-4 text-brand-500 mt-0.5 shrink-0" />
                                    <span>{{ wave.period }}</span>
                                </div>

                                <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                                    {{ wave.note }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-100">
                                <a
                                    :href="registrationLink"
                                    target="_blank"
                                    class="w-full py-3 rounded-2xl font-bold text-xs flex items-center justify-center gap-2 transition-colors"
                                    :class="idx === 0 ? 'bg-brand-600 hover:bg-brand-700 text-white shadow-md' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                                >
                                    <span>Daftar Tahap Ini</span>
                                    <ArrowRight class="w-3.5 h-3.5" />
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= SECTION 3: ALUR PENDAFTARAN (STEPS) ================= -->
                <section v-motion-slide-visible-bottom>
                    <div class="text-center max-w-3xl mx-auto mb-12">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest bg-cyan-50 text-cyan-800 border border-cyan-200 mb-3">
                            <RouteIcon class="w-3.5 h-3.5" />
                            <span>Prosedur Mudah</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                            Alur Pendaftaran Siswa Baru
                        </h2>
                        <p class="text-slate-500 text-sm sm:text-base mt-2">
                            4 langkah praktis dari pengisian formulir hingga resmi menjadi siswa SMKS Muhammadiyah 2 Genteng.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div
                            v-for="(st, idx) in stepsList"
                            :key="idx"
                            class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative flex flex-col justify-between group hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                        >
                            <div>
                                <div class="h-12 w-12 rounded-2xl bg-brand-500 text-white font-black text-lg grid place-items-center shadow-lg shadow-brand-500/30 mb-5">
                                    {{ st.step }}
                                </div>
                                <h3 class="text-lg font-black text-slate-900 group-hover:text-brand-600 transition-colors">
                                    {{ st.title }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
                                    {{ st.desc }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= SECTION 4: SYARAT & NARAHUBUNG (SPLIT) ================= -->
                <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start" v-motion-slide-visible-bottom>
                    <!-- Left: Persyaratan Berkas -->
                    <div class="lg:col-span-7 bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-sm space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-brand-50 text-brand-600 grid place-items-center">
                                <FileText class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-slate-900">Persyaratan Administrasi</h3>
                                <p class="text-xs text-slate-400">Dokumen yang perlu dipersiapkan saat pendaftaran</p>
                            </div>
                        </div>

                        <ul class="space-y-3.5 pt-2">
                            <li
                                v-for="(req, idx) in requirementsList"
                                :key="idx"
                                class="flex items-start gap-3 text-sm text-slate-700"
                            >
                                <CheckCircle2 class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" />
                                <span class="leading-relaxed">{{ req }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Right: Posko PPDB & Kontak WhatsApp -->
                    <div class="lg:col-span-5 space-y-6">
                        <!-- WhatsApp Card -->
                        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl p-8 text-white shadow-xl shadow-emerald-600/25 space-y-6">
                            <div>
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-xs font-bold backdrop-blur-md mb-3">
                                    <MessageCircle class="w-3.5 h-3.5" />
                                    <span>Konsultasi Cepat</span>
                                </div>
                                <h3 class="text-2xl font-black">{{ ppdb?.hotline_name || 'Panitia PPDB STEMDA' }}</h3>
                                <p class="text-emerald-100 text-xs sm:text-sm mt-1 leading-relaxed">
                                    Butuh panduan pendaftaran atau ingin menanyakan informasi biaya & jurusan? Hubungi langsung via WhatsApp.
                                </p>
                            </div>

                            <a
                                :href="whatsappUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full py-4 rounded-2xl bg-white text-emerald-800 hover:bg-emerald-50 font-black text-sm grid place-items-center shadow-lg hover:scale-105 active:scale-95 transition-all duration-300"
                            >
                                <span class="flex items-center gap-2">
                                    <MessageCircle class="w-5 h-5 text-emerald-600" />
                                    <span>Chat WhatsApp: {{ ppdb?.whatsapp_number || '081236453325' }}</span>
                                </span>
                            </a>
                        </div>

                        <!-- Posko & Alamat -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-700 grid place-items-center shrink-0">
                                    <MapPin class="w-5 h-5 text-brand-600" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900">Sekretariat Posko PPDB</h4>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                        {{ ppdb?.address || 'JL Hasanudin 103, Genteng Wetan - Genteng, Banyuwangi' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 pt-3 border-t border-slate-100">
                                <div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-700 grid place-items-center shrink-0">
                                    <Clock class="w-5 h-5 text-indigo-600" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900">Jam Layanan Posko</h4>
                                    <p class="text-xs text-slate-500 mt-1">Senin - Sabtu: 07.30 - 14.00 WIB</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 pt-3 border-t border-slate-100">
                                <div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-700 grid place-items-center shrink-0">
                                    <Mail class="w-5 h-5 text-cyan-600" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900">Email Resmi</h4>
                                    <p class="text-xs text-slate-500 mt-1">{{ ppdb?.email || 'smkmudagenteng@gmail.com' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Fullscreen Poster Lightbox Modal -->
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
                    v-if="isPosterModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-xl p-4 sm:p-6"
                    @click.self="closePosterModal"
                >
                    <button
                        type="button"
                        @click="closePosterModal"
                        aria-label="Tutup poster"
                        class="absolute top-5 right-5 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-white/20 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                    >
                        <X class="w-6 h-6" />
                    </button>

                    <div class="relative max-w-4xl w-full max-h-[92vh] flex flex-col items-center justify-center" @click.stop>
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/15 max-h-[76vh] bg-black/60 flex items-center justify-center w-full">
                            <Transition name="poster-fade" mode="out-in">
                                <img
                                    :key="currentPoster"
                                    :src="currentPoster"
                                    :alt="`Poster PPDB Fullscreen - ${currentPosterIndex + 1}`"
                                    class="max-h-[76vh] w-auto max-w-full object-contain rounded-3xl select-none"
                                />
                            </Transition>

                            <!-- Navigation Arrows inside Fullscreen Modal -->
                            <div v-if="posterImagesList.length > 1" class="absolute inset-y-0 inset-x-3 flex items-center justify-between pointer-events-none z-10">
                                <button
                                    type="button"
                                    @click.stop="prevPoster"
                                    aria-label="Poster sebelumnya"
                                    class="pointer-events-auto h-12 w-12 rounded-full bg-black/60 hover:bg-brand-600 text-white grid place-items-center backdrop-blur-md border border-white/20 shadow-xl transition-all transform hover:scale-110 active:scale-95 focus:outline-none"
                                >
                                    <ChevronLeft class="w-6 h-6" />
                                </button>
                                <button
                                    type="button"
                                    @click.stop="nextPoster"
                                    aria-label="Poster selanjutnya"
                                    class="pointer-events-auto h-12 w-12 rounded-full bg-black/60 hover:bg-brand-600 text-white grid place-items-center backdrop-blur-md border border-white/20 shadow-xl transition-all transform hover:scale-110 active:scale-95 focus:outline-none"
                                >
                                    <ChevronRight class="w-6 h-6" />
                                </button>
                            </div>

                            <!-- Counter badge in fullscreen modal -->
                            <div v-if="posterImagesList.length > 1" class="absolute top-4 left-4 px-3 py-1 rounded-full bg-black/60 text-white font-bold text-xs backdrop-blur-md border border-white/20">
                                {{ currentPosterIndex + 1 }} / {{ posterImagesList.length }}
                            </div>
                        </div>

                        <!-- Dots indicator in fullscreen modal -->
                        <div v-if="posterImagesList.length > 1" class="mt-3 flex items-center justify-center gap-2">
                            <button
                                v-for="(_, idx) in posterImagesList"
                                :key="idx"
                                type="button"
                                @click="goToPoster(idx)"
                                :aria-label="`Pindah ke poster ${idx + 1}`"
                                class="h-2 rounded-full transition-all duration-300 focus:outline-none"
                                :class="currentPosterIndex === idx ? 'w-6 bg-brand-400' : 'w-2 bg-white/40 hover:bg-white'"
                            ></button>
                        </div>

                        <div class="mt-4 flex items-center justify-center gap-4">
                            <a
                                :href="currentPoster"
                                :download="`Poster-PPDB-STEMDA-${currentPosterIndex + 1}.jpg`"
                                target="_blank"
                                class="px-6 py-3 rounded-2xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-xl flex items-center gap-2 transition-all hover:scale-105 active:scale-95"
                            >
                                <Download class="w-4 h-4" />
                                <span>Unduh File Poster ({{ currentPosterIndex + 1 }})</span>
                            </a>
                            <a
                                :href="registrationLink"
                                target="_blank"
                                class="px-6 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 backdrop-blur-md flex items-center gap-2 transition-all hover:scale-105 active:scale-95"
                            >
                                <span>Daftar Online</span>
                                <ExternalLink class="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </MainLayout>
</template>

<style scoped>
.poster-fade-enter-active,
.poster-fade-leave-active {
    transition: opacity 0.4s ease;
}

.poster-fade-enter-from,
.poster-fade-leave-to {
    opacity: 0;
}
</style>
