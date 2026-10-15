<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, EffectFade, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import {
    ArrowRight,
    Calendar,
    Clock,
    Newspaper,
    Building2,
    Factory,
    Cpu,
    Briefcase,
    Instagram,
    Facebook,
    GraduationCap,
    Sparkles,
    Quote,
    Star,
    ChevronLeft,
    ChevronRight,
    MonitorPlay
} from 'lucide-vue-next';

const props = defineProps({
    sliders: Array,
    achievements: Array,
    programs: Array,
    news: Array,
    agendas: Array,
    teachers: Array,
    gallery: Array,
    partners: Array,
    testimonials: Array,
    sections: Object,
});

const swiperModules = [Autoplay, EffectFade, Navigation, Pagination];

const colorMap = {
    'brand-500': 'from-brand-500 to-brand-600',
    'blue-500': 'from-blue-500 to-blue-600',
    'purple-500': 'from-purple-500 to-purple-600',
    'green-500': 'from-green-500 to-green-600',
    'red-500': 'from-red-500 to-red-600',
    'orange-500': 'from-orange-500 to-orange-600',
    'pink-500': 'from-pink-500 to-pink-600',
    'indigo-500': 'from-indigo-500 to-indigo-600',
};

const getGradientColor = (color) => {
    return colorMap[color] || colorMap['brand-500'];
};

const youtubeVideoId = 'zSII6IqSUfI';
const youtubeWatchUrl = `https://www.youtube.com/watch?v=${youtubeVideoId}`;
const youtubeEmbedUrl = `https://www.youtube-nocookie.com/embed/${youtubeVideoId}?rel=0&modestbranding=1&playsinline=1&controls=0&iv_load_policy=3&cc_load_policy=0&disablekb=1`;

const partnerItems = computed(() => {
    if (props.partners && props.partners.length > 0) return props.partners;

    return [
        { name: 'DUDIKA', category: 'Asosiasi Industri', icon: Building2 },
        { name: 'PT. Maju Jaya', category: 'Otomotif', icon: Factory },
        { name: 'Industri 4.0', category: 'Manufacturing', icon: Cpu },
        { name: 'Dinas Kominfo', category: 'Pemerintah', icon: Briefcase }
    ];
});

const partnersLoop = computed(() => partnerItems.value.length > 3);

const partnersAutoplay = computed(() => {
    if (!partnersLoop.value) return false;
    return { delay: 2200, disableOnInteraction: false };
});

const partnersCentered = computed(() => partnerItems.value.length <= 3);

const dindikHeroImageSrc = '/storage/images/dinas.png';
const dindikUrl = 'https://dindik.jatimprov.go.id/';
</script>

<template>
    <Head title="Beranda" />

    <MainLayout>
        <!-- 1. Ultra-Modern Cinematic Hero Slider Section -->
        <div class="relative bg-slate-950 h-[560px] sm:h-[580px] lg:h-[640px] overflow-hidden group/slider">
            <!-- Global Ambient Mesh Lighting -->
            <div class="absolute inset-0 pointer-events-none opacity-35 z-0">
                <div class="absolute -top-32 -left-32 w-[30rem] h-[30rem] rounded-full blur-3xl bg-gradient-to-br from-brand-600/40 to-indigo-600/25"></div>
                <div class="absolute top-1/3 -right-32 w-[28rem] h-[28rem] rounded-full blur-3xl bg-gradient-to-tr from-cyan-500/25 to-brand-500/30"></div>
                <div class="absolute -bottom-32 left-1/3 w-[26rem] h-[26rem] rounded-full blur-3xl bg-gradient-to-tr from-emerald-500/20 to-brand-600/25"></div>
                <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:24px_24px]"></div>
            </div>

            <swiper
                :modules="swiperModules"
                :slides-per-view="1"
                :effect="'fade'"
                :loop="true"
                :autoplay="{
                    delay: 5500,
                    disableOnInteraction: false,
                }"
                :navigation="{
                    prevEl: '.swiper-button-prev-custom',
                    nextEl: '.swiper-button-next-custom',
                }"
                :pagination="{
                    clickable: true,
                    el: '.swiper-pagination-custom',
                    bulletClass: 'slider-custom-bullet',
                    bulletActiveClass: 'slider-custom-bullet-active',
                }"
                class="h-full w-full relative z-10"
            >
                <swiper-slide
                    v-for="(slider, index) in (sliders && sliders.length > 0 ? sliders : [{ title: 'Selamat Datang di SMKS Muhammadiyah 2 Genteng', subtitle: 'Sekolah vokasi unggulan yang mencetak generasi kompeten, berakhlak mulia, dan siap kerja di era industri 4.0.', image: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1600', button_text: 'Lihat Profil', button_link: '/profil' }])"
                    :key="index"
                    class="group/slide h-full"
                >
                    <div class="relative h-full w-full flex items-center justify-center px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 pb-24 sm:pb-24">
                        <!-- Background Image (Centered on building for mobile, bottom for desktop) -->
                        <div class="absolute inset-0 overflow-hidden bg-slate-950">
                            <img
                                :src="slider.image"
                                :alt="slider.title"
                                class="w-full h-full object-cover object-[center_68%] md:object-bottom transition-transform duration-1000 group-[.swiper-slide-active]/slide:scale-105"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/35 to-black/30 md:from-slate-950/75 md:via-slate-950/25 md:to-black/20"></div>
                        </div>

                        <!-- Main Slide Content -->
                        <div class="relative z-10 text-center max-w-4xl mx-auto flex flex-col items-center">
                            <!-- Top Pill Badge -->
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/[0.10] hover:bg-white/[0.15] border border-white/20 text-white text-[11px] font-black uppercase tracking-widest backdrop-blur-xl shadow-xl mb-3 sm:mb-6 transition-all duration-700 delay-100 translate-y-4 opacity-0 group-[.swiper-slide-active]/slide:translate-y-0 group-[.swiper-slide-active]/slide:opacity-100">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                                </span>
                                <Sparkles class="w-3 h-3 text-amber-300" />
                                <span>SMK Pusat Keunggulan • Terakreditasi A</span>
                            </div>

                            <!-- Main Title -->
                            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mb-3 sm:mb-6 tracking-tight leading-[1.15] transition-all duration-700 delay-300 translate-y-6 opacity-0 group-[.swiper-slide-active]/slide:translate-y-0 group-[.swiper-slide-active]/slide:opacity-100 drop-shadow-[0_8px_20px_rgba(0,0,0,0.8)]">
                                {{ slider.title }}
                            </h1>

                            <!-- Subtitle Description -->
                            <p class="text-xs sm:text-base md:text-lg text-slate-200 mb-5 sm:mb-8 max-w-2xl mx-auto font-normal leading-relaxed transition-all duration-700 delay-500 translate-y-6 opacity-0 group-[.swiper-slide-active]/slide:translate-y-0 group-[.swiper-slide-active]/slide:opacity-95 drop-shadow">
                                {{ slider.subtitle }}
                            </p>

                            <!-- Action Buttons Row -->
                            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4 transition-all duration-700 delay-700 translate-y-8 opacity-0 group-[.swiper-slide-active]/slide:translate-y-0 group-[.swiper-slide-active]/slide:opacity-100">
                                <Link
                                    :href="slider.button_link || route('about')"
                                    class="inline-flex items-center gap-2.5 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 rounded-2xl bg-gradient-to-r from-brand-500 via-brand-600 to-blue-600 text-white font-black text-xs sm:text-base shadow-xl shadow-brand-600/35 hover:shadow-brand-500/50 hover:scale-105 active:scale-95 transition-all duration-300 group/btn"
                                >
                                    <span>{{ slider.button_text || 'Jelajahi Profil' }}</span>
                                    <ArrowRight class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" />
                                </Link>

                                <Link
                                    :href="route('contact')"
                                    class="inline-flex items-center gap-2 px-5 sm:px-7 py-3 sm:py-4 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-base border border-white/20 backdrop-blur-xl hover:scale-105 active:scale-95 transition-all duration-300"
                                >
                                    <span>Info Pendaftaran PPDB</span>
                                </Link>
                            </div>

                            <!-- Quick Badges Ribbon (Hidden on mobile, visible on tablet/desktop) -->
                            <div class="hidden sm:flex mt-10 sm:mt-12 pt-6 border-t border-white/10 flex-wrap items-center justify-center gap-3 sm:gap-6 text-xs sm:text-sm font-semibold text-slate-300 transition-all duration-700 delay-900 translate-y-6 opacity-0 group-[.swiper-slide-active]/slide:translate-y-0 group-[.swiper-slide-active]/slide:opacity-80">
                                <div class="flex items-center gap-2">
                                    <span class="text-brand-400 font-black">✓</span>
                                    <span>5 Program Keahlian Vokasi</span>
                                </div>
                                <span class="hidden sm:inline text-white/20">•</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-brand-400 font-black">✓</span>
                                    <span>Kurikulum Standar Industri</span>
                                </div>
                                <span class="hidden sm:inline text-white/20">•</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-brand-400 font-black">✓</span>
                                    <span>Fasilitas Praktik Modern</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </swiper-slide>
            </swiper>

            <!-- Navigation Buttons -->
            <button
                class="swiper-button-prev-custom absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 lg:w-14 lg:h-14 rounded-2xl bg-slate-900/60 hover:bg-brand-600 border border-white/15 text-white flex items-center justify-center backdrop-blur-xl shadow-2xl transition-all duration-300 opacity-0 group-hover/slider:opacity-100 -translate-x-4 group-hover/slider:translate-x-0 hover:scale-110 active:scale-95 focus:outline-none"
                aria-label="Slide sebelumnya"
            >
                <ChevronLeft class="w-6 h-6" />
            </button>
            <button
                class="swiper-button-next-custom absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 lg:w-14 lg:h-14 rounded-2xl bg-slate-900/60 hover:bg-brand-600 border border-white/15 text-white flex items-center justify-center backdrop-blur-xl shadow-2xl transition-all duration-300 opacity-0 group-hover/slider:opacity-100 translate-x-4 group-hover/slider:translate-x-0 hover:scale-110 active:scale-95 focus:outline-none"
                aria-label="Slide selanjutnya"
            >
                <ChevronRight class="w-6 h-6" />
            </button>

            <!-- Custom Segmented Pill Pagination -->
            <div class="swiper-pagination-custom absolute bottom-8 sm:bottom-12 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2.5 px-4 py-2 rounded-full bg-slate-900/70 backdrop-blur-xl border border-white/10 shadow-2xl"></div>
        </div>

        <!-- 2. Stats Section (Position Ke-2) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-20 mb-24">
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12 hover:shadow-brand-500/10 transition-shadow duration-500"
                 v-motion
                 :initial="{ opacity: 0, y: 50 }"
                 :enter="{ opacity: 1, y: 0, transition: { duration: 800, delay: 200 } }">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center group" v-motion :initial="{ opacity: 0, scale: 0.8 }" :enter="{ opacity: 1, scale: 1, transition: { duration: 500, delay: 300 } }">
                        <div class="text-4xl md:text-5xl font-black text-brand-500 mb-2 group-hover:scale-110 transition-transform duration-300">5</div>
                        <div class="text-sm font-bold text-slate-500 uppercase tracking-widest">Jurusan Unggulan</div>
                    </div>
                    <div class="text-center group" v-motion :initial="{ opacity: 0, scale: 0.8 }" :enter="{ opacity: 1, scale: 1, transition: { duration: 500, delay: 400 } }">
                        <div class="text-4xl md:text-5xl font-black text-brand-500 mb-2 group-hover:scale-110 transition-transform duration-300">50+</div>
                        <div class="text-sm font-bold text-slate-500 uppercase tracking-widest">Mitra Industri</div>
                    </div>
                    <div class="text-center group" v-motion :initial="{ opacity: 0, scale: 0.8 }" :enter="{ opacity: 1, scale: 1, transition: { duration: 500, delay: 500 } }">
                        <div class="text-4xl md:text-5xl font-black text-brand-500 mb-2 group-hover:scale-110 transition-transform duration-300">1200+</div>
                        <div class="text-sm font-bold text-slate-500 uppercase tracking-widest">Siswa Aktif</div>
                    </div>
                    <div class="text-center group" v-motion :initial="{ opacity: 0, scale: 0.8 }" :enter="{ opacity: 1, scale: 1, transition: { duration: 500, delay: 600 } }">
                        <div class="text-4xl md:text-5xl font-black text-brand-500 mb-2 group-hover:scale-110 transition-transform duration-300">95%</div>
                        <div class="text-sm font-bold text-slate-500 uppercase tracking-widest">Lulusan Bekerja</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Video Profil Section (Position Ke-3) -->
        <div class="py-16 md:py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                    <div class="lg:col-span-5" v-motion-slide-visible-bottom>
                        <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-600 mb-6">
                            <MonitorPlay class="h-4 w-4 text-brand-500" />
                            Video Profil
                        </div>
                        <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">
                            Kenali Sekolah Kami lewat <span class="text-brand-600">Video Profil</span>
                        </h2>
                        <p class="mt-5 text-lg text-slate-600 leading-relaxed max-w-xl">
                            Cuplikan suasana belajar, fasilitas, kegiatan siswa, hingga semangat kolaborasi di SMKS Muhammadiyah 2 Genteng.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a :href="youtubeWatchUrl" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-brand-500 text-white font-bold text-sm hover:bg-brand-600 shadow-lg hover:shadow-brand-500/30 hover:scale-105 active:scale-95 transition-all">
                                Tonton di YouTube
                                <ArrowRight class="w-4 h-4" />
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-7" v-motion :initial="{ opacity: 0, y: 30 }" :visible="{ opacity: 1, y: 0, transition: { duration: 650 } }">
                        <div class="relative overflow-hidden rounded-[2.5rem] border border-slate-200 bg-slate-950 shadow-2xl shadow-slate-200/50 group">
                            <div class="absolute inset-0 opacity-35">
                                <div class="absolute -top-24 -right-28 h-72 w-72 rounded-full blur-3xl bg-gradient-to-br from-brand-500 to-indigo-500"></div>
                                <div class="absolute -bottom-28 -left-24 h-80 w-80 rounded-full blur-3xl bg-gradient-to-tr from-emerald-500 to-brand-500"></div>
                            </div>
                            <div class="relative pt-[56.25%]">
                                <iframe
                                    class="absolute inset-0 h-full w-full"
                                    :src="youtubeEmbedUrl"
                                    title="Video Profil SMKS Muhammadiyah 2 Genteng"
                                    frameborder="0"
                                    loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Dinas Pendidikan Jatim Section (Position Ke-4) -->
        <section class="py-12 md:py-16 bg-brand-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center"
                    v-motion
                    :initial="{ opacity: 0, y: 18 }"
                    :visible="{ opacity: 1, y: 0, transition: { duration: 650 } }"
                >
                    <div class="lg:col-span-5">
                        <div class="inline-flex items-center gap-2 rounded-2xl border border-white/25 bg-white/10 px-4 py-2 text-sm text-white/90">
                            <Sparkles class="h-4 w-4 text-white" />
                            Link Resmi
                        </div>

                        <h3 class="mt-5 text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-white">
                            Dinas Pendidikan Provinsi Jawa Timur
                        </h3>
                        <p class="mt-3 text-white/85 leading-relaxed max-w-xl">
                            Akses portal resmi Dindik Jatim untuk informasi, layanan, dan pengumuman pendidikan.
                        </p>

                        <div class="mt-6 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-2xl border border-white/25 bg-white/10 px-3 py-2 text-sm font-semibold text-white/90">
                                <Building2 class="h-4 w-4 text-white/80" />
                                Pemerintahan
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-2xl border border-white/25 bg-white/10 px-3 py-2 text-sm font-semibold text-white/90">
                                <GraduationCap class="h-4 w-4 text-white/80" />
                                Pendidikan
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-2xl border border-white/25 bg-white/10 px-3 py-2 text-sm font-semibold text-white/90">
                                <Newspaper class="h-4 w-4 text-white/80" />
                                Informasi
                            </span>
                        </div>

                        <div class="mt-8 flex flex-wrap items-center gap-3">
                            <a
                                :href="dindikUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-white text-brand-700 font-bold text-sm hover:bg-white/90 shadow-lg hover:shadow-white/20 hover:scale-105 active:scale-95 transition-all"
                            >
                                Buka Website
                                <ArrowRight class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                            </a>
                            <p class="text-sm font-semibold text-white/85 select-all">
                                dindik.jatimprov.go.id
                            </p>
                        </div>
                    </div>

                    <div class="lg:col-span-7">
                        <div class="relative aspect-[16/10] w-full overflow-hidden rounded-[2rem] ring-1 ring-white/20 shadow-2xl shadow-slate-900/20 group">
                            <img
                                :src="dindikHeroImageSrc"
                                alt="Tampilan website Dinas Pendidikan Provinsi Jawa Timur"
                                class="absolute inset-0 h-full w-full object-cover group-hover:scale-105 transition-transform duration-700"
                                loading="lazy"
                                width="1600"
                                height="1000"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Programs Section (Position Ke-5) -->
        <div id="programs" v-if="programs && programs.length > 0" class="py-14 md:py-16 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10 md:mb-12" v-motion-slide-visible-bottom>
                    <h2 class="text-brand-600 font-bold tracking-widest uppercase text-sm mb-4">{{ sections?.programs?.title || 'Pilihan Jurusan' }}</h2>
                    <h3 class="text-4xl md:text-5xl font-extrabold text-slate-900">{{ sections?.programs?.subtitle || 'Program Keahlian SMKS' }}</h3>
                    <div class="w-20 h-1.5 bg-brand-500 mx-auto mt-5 rounded-full"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-5 md:gap-6">
                    <div v-for="(prog, index) in programs" :key="prog.id ?? prog.slug ?? prog.name"
                         v-motion
                         :initial="{ opacity: 0, y: 30 }"
                         :visible="{ opacity: 1, y: 0, transition: { duration: 500, delay: index * 80 } }"
                         class="relative overflow-hidden bg-gradient-to-br from-brand-500 to-brand-700 rounded-3xl p-5 sm:p-6 shadow-lg hover:shadow-2xl hover:shadow-brand-500/40 transition-all duration-500 group text-center flex flex-col items-center justify-between border border-brand-400/40 hover:-translate-y-2">

                        <!-- Decorative glow -->
                        <div class="absolute -top-14 -right-14 w-28 h-28 bg-white/10 rounded-full blur-2xl group-hover:bg-white/25 transition-colors duration-500"></div>

                        <div class="flex flex-col items-center w-full">
                            <!-- Logo Box -->
                            <div class="relative z-10 w-20 h-20 sm:w-22 sm:h-22 mb-4 rounded-2xl bg-white/15 border border-white/25 flex items-center justify-center p-3 backdrop-blur-md group-hover:bg-white/25 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-inner">
                                <img :src="prog.image" :alt="prog.name" class="w-full h-full object-contain drop-shadow-md" @error="(e) => { e.target.style.display='none'; e.target.nextElementSibling.style.display='flex'; }">
                                <div class="hidden w-full h-full items-center justify-center text-white font-black text-2xl drop-shadow-sm">
                                    {{ prog.name?.slice(0, 3) || 'P' }}
                                </div>
                            </div>

                            <!-- Program Title -->
                            <h4 class="relative z-10 text-sm sm:text-base font-black text-white mb-4 transition-colors leading-snug min-h-[42px] flex items-center justify-center text-center">
                                {{ prog.name }}
                            </h4>
                        </div>

                        <!-- Action Button -->
                        <Link :href="route('keahlian.show', { slug: prog.slug })" class="relative z-10 w-full py-2.5 px-3.5 rounded-xl bg-white/15 hover:bg-white text-white hover:text-brand-700 font-extrabold text-xs sm:text-sm border border-white/25 hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-300 flex items-center justify-center gap-1.5 group/btn">
                            <span>Lihat Detail</span>
                            <ArrowRight class="w-3.5 h-3.5 opacity-80 group-hover/btn:opacity-100 group-hover/btn:translate-x-1 transition-all duration-300" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Berita Terkini Section (Position Ke-6) -->
        <div class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16" v-motion-slide-visible-bottom>
                    <h2 class="text-brand-600 font-bold tracking-widest uppercase text-sm mb-4">Informasi Terbaru</h2>
                    <h3 class="text-4xl md:text-5xl font-extrabold text-slate-900">Berita & Pengumuman</h3>
                    <div class="w-20 h-1.5 bg-brand-500 mx-auto mt-6 rounded-full"></div>
                </div>

                <div v-if="news && news.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <Link v-for="(item, index) in news" :key="index" :href="route('news.show', item.slug)"
                          v-motion
                          :initial="{ opacity: 0, y: 50 }"
                          :visible="{ opacity: 1, y: 0, transition: { duration: 500, delay: index * 150 } }"
                          class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group border border-slate-100 hover:border-brand-200">
                        <div class="relative h-56 overflow-hidden bg-slate-200">
                            <img v-if="item.image" :src="item.image" :alt="item.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" decoding="async" width="800" height="400" @error="(e) => { e.target.style.display='none'; if(e.target.nextElementSibling) e.target.nextElementSibling.style.display='flex'; }">
                            <div :class="{ 'hidden': item.image }" class="w-full h-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center">
                                <Newspaper class="w-16 h-16 text-white opacity-80" />
                            </div>
                            <div class="absolute top-4 right-4 bg-brand-500/90 backdrop-blur-md text-white px-4 py-2 rounded-full text-xs font-bold shadow-md">
                                {{ new Date(item.published_at).toLocaleDateString('id-ID') }}
                            </div>
                        </div>
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-brand-600 transition-colors line-clamp-2">{{ item.title }}</h4>
                            <p class="text-slate-600 text-sm mb-4 line-clamp-3">{{ item.excerpt }}</p>
                            <div class="flex items-center text-brand-600 font-bold group-hover:gap-2 transition-all">
                                Baca Selengkapnya <ArrowRight class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" />
                            </div>
                        </div>
                    </Link>
                </div>
                <div v-else class="text-center text-slate-500 py-12" v-motion-fade>
                    <p class="text-lg">Belum ada berita terbaru</p>
                </div>

                <div class="text-center mt-12" v-motion-fade>
                    <Link :href="route('news.index')" class="px-8 py-4 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl transition-all inline-block shadow-lg hover:shadow-brand-500/30 hover:scale-105 active:scale-95">
                        Lihat Semua Berita
                    </Link>
                </div>
            </div>
        </div>

        <!-- 7. Agenda Terkini Section (Position Ke-7) -->
        <div class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16" v-motion-slide-visible-bottom>
                    <h2 class="text-brand-600 font-bold tracking-widest uppercase text-sm mb-4">Jadwal</h2>
                    <h3 class="text-4xl md:text-5xl font-extrabold text-slate-900">Agenda Terkini</h3>
                    <div class="w-20 h-1.5 bg-brand-500 mx-auto mt-6 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Agenda Cards from Database -->
                    <div v-if="agendas && agendas.length > 0" v-for="(agenda, index) in agendas" :key="agenda.id"
                         v-motion
                         :initial="{ opacity: 0, scale: 0.95 }"
                         :visible="{ opacity: 1, scale: 1, transition: { duration: 500, delay: index * 150 } }"
                         class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 group border border-slate-100 hover:-translate-y-2.5">
                        <div :class="['bg-gradient-to-r p-8', getGradientColor(agenda.color)]">
                            <div class="text-white">
                                <div class="text-sm font-bold opacity-90 mb-2 flex items-center">
                                    <Calendar class="w-4 h-4 mr-2 inline-block" /> AGENDA
                                </div>
                                <div class="text-3xl font-black mb-2">{{ new Date(agenda.start_date).getDate() }}</div>
                                <div class="text-lg font-bold opacity-90">{{ new Date(agenda.start_date).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' }) }}</div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-brand-600 transition-colors">{{ agenda.title }}</h4>
                            <p class="text-slate-600 text-sm mb-4">{{ agenda.description }}</p>
                            <div class="flex items-center text-slate-500 text-sm">
                                <Clock class="w-4 h-4 mr-2 text-brand-500" />
                                {{ new Date(agenda.start_date).toLocaleDateString('id-ID') }}
                                <span v-if="agenda.end_date && agenda.end_date !== agenda.start_date"> - {{ new Date(agenda.end_date).toLocaleDateString('id-ID') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Fallback jika tidak ada agenda -->
                    <div v-else class="col-span-3 text-center py-12 text-slate-500" v-motion-fade>
                        <p class="text-lg">Belum ada agenda yang dijadwalkan</p>
                    </div>
                </div>

                <div class="text-center mt-12" v-motion-fade>
                    <Link :href="route('event.agenda')" class="px-8 py-4 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl transition-all inline-block shadow-lg hover:shadow-brand-500/30 hover:scale-105 active:scale-95">
                        Lihat Semua Agenda
                    </Link>
                </div>
            </div>
        </div>

        <!-- 8. Mitra & Partner Section (Position Ke-8) -->
        <div class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16" v-motion-slide-visible-bottom>
                    <h2 class="text-brand-600 font-bold tracking-widest uppercase text-sm mb-4">{{ sections?.partners?.title || 'Kepercayaan' }}</h2>
                    <h3 class="text-4xl md:text-5xl font-extrabold text-slate-900">{{ sections?.partners?.subtitle || 'Mitra & Kolaborasi Strategis' }}</h3>
                    <div class="w-20 h-1.5 bg-brand-500 mx-auto mt-6 rounded-full"></div>
                </div>

                <!-- Logo Partners Grid -->
                <swiper
                    :modules="swiperModules"
                    :slides-per-view="'auto'"
                    :space-between="32"
                    :loop="partnersLoop"
                    :autoplay="partnersAutoplay"
                    :centered-slides="partnersCentered"
                    :center-insufficient-slides="true"
                    :watch-overflow="true"
                    class="w-full"
                >
                    <swiper-slide v-for="(partner, index) in partnerItems" :key="partner.id || partner.name" class="!w-[18rem] sm:!w-[22rem] md:!w-[28rem] lg:!w-[32rem]">
                        <div
                            v-motion
                            :initial="{ opacity: 0, y: 16 }"
                            :visible="{ opacity: 1, y: 0, transition: { delay: index * 60 } }"
                            class="rounded-3xl p-4 sm:p-5 h-56 sm:h-64 md:h-80 lg:h-96 flex items-center justify-center group transition-all duration-500 hover:scale-105"
                        >
                            <div v-if="partner.logo" class="h-full w-full flex items-center justify-center">
                                <img :src="partner.logo" :alt="partner.name" class="w-full h-full max-w-none object-contain group-hover:scale-105 group-hover:drop-shadow-2xl transition-all duration-500">
                            </div>
                            <div v-else class="text-center">
                                <component :is="partner.icon || Building2" class="w-12 h-12 md:w-14 md:h-14 mx-auto mb-3 text-slate-400 group-hover:text-brand-500 group-hover:scale-110 transition-all duration-300" />
                                <p class="text-sm md:text-base font-extrabold text-slate-800 line-clamp-1">{{ partner.name }}</p>
                                <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1">{{ partner.category }}</p>
                            </div>
                        </div>
                    </swiper-slide>
                </swiper>
            </div>
        </div>

        <!-- 9. CTA Section (Position Ke-9) -->
        <div class="py-24 bg-brand-500 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 0 L100 100 L100 0 Z" fill="white"></path>
                </svg>
            </div>
            <div class="max-w-4xl mx-auto px-4 relative z-10 text-center" v-motion-slide-visible-bottom>
                <h2 class="text-4xl md:text-5xl font-black text-white mb-8">Wujudkan Impianmu Bersama Kami</h2>
                <p class="text-xl text-brand-100 mb-12 font-medium opacity-90">Daftarkan dirimu sekarang dan bersiaplah menjadi tenaga kerja profesional yang siap bersaing di era industri 4.0.</p>
                <Link :href="route('contact')" class="inline-block px-12 py-5 bg-white text-brand-600 font-bold rounded-2xl text-xl shadow-2xl hover:shadow-white/20 hover:scale-105 active:scale-95 transition-all duration-300">
                    Hubungi Pendaftaran
                </Link>
            </div>
        </div>

        <!-- 10. Testimoni Alumni Section (Position Ke-10) -->
        <div class="py-24 bg-slate-50 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 rounded-full bg-brand-200/20 blur-3xl animate-pulse-subtle"></div>
            <div class="absolute bottom-0 left-0 -ml-32 -mb-32 w-96 h-96 rounded-full bg-blue-200/20 blur-3xl animate-float"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16" v-motion-slide-visible-bottom>
                    <h2 class="text-brand-600 font-bold tracking-widest uppercase text-sm mb-4">{{ sections?.testimonials?.title || 'Kisah Sukses' }}</h2>
                    <h3 class="text-4xl md:text-5xl font-extrabold text-slate-900">{{ sections?.testimonials?.subtitle || 'Testimoni Alumni' }}</h3>
                    <div class="w-20 h-1.5 bg-brand-500 mx-auto mt-6 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="(testi, index) in (testimonials && testimonials.length > 0 ? testimonials : [
                        { name: 'Budi Santoso', message: 'Pendidikan praktis dan fasilitas lengkap yang saya dapatkan sangat membantu ketika terjun ke dunia kerja.', graduation_year: '2018', company: 'PT. Toyota Astra', position: 'Kepala Mekanik' },
                        { name: 'Andi Pratama', message: 'Masa sekolah tidak hanya mengajarkan teori, tapi juga soft skill dan kedisiplinan industri.', graduation_year: '2019', company: 'Samsung Ind.', position: 'IoT Engineer' },
                        { name: 'Siti Aminah', message: 'Berkat bimbingan guru dan kurikulum yang up-to-date, saya bisa langsung diterima bekerja di studio animasi besar.', graduation_year: '2021', company: 'MD Pictures', position: '3D Animator' }
                    ])" :key="testi.id || testi.name"
                         v-motion
                         :initial="{ opacity: 0, y: 40 }"
                         :visible="{ opacity: 1, y: 0, transition: { duration: 500, delay: index * 100 } }"
                         class="bg-white rounded-[2rem] p-8 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 group relative">

                        <Quote class="absolute top-8 right-8 w-12 h-12 text-brand-100 group-hover:text-brand-200 transition-colors duration-300" />

                        <div class="flex gap-1 mb-6">
                            <Star v-for="i in 5" :key="i" class="w-5 h-5 fill-amber-400 text-amber-400" />
                        </div>

                        <p class="text-slate-600 italic mb-8 leading-relaxed relative z-10">
                            "{{ testi.message }}"
                        </p>

                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full bg-brand-100 overflow-hidden border-2 border-white shadow-md flex items-center justify-center">
                                <img v-if="testi.photo" :src="testi.photo" :alt="testi.name" class="w-full h-full object-cover" @error="(e) => { e.target.style.display='none'; if(e.target.nextElementSibling) e.target.nextElementSibling.style.display='flex'; }">
                                <div :class="{ 'hidden': testi.photo }" class="h-full w-full flex items-center justify-center bg-slate-100 text-slate-400">
                                    <Star class="w-6 h-6" />
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">{{ testi.name }}</h4>
                                <p class="text-sm text-brand-600 font-medium">{{ testi.position }}, {{ testi.company }}</p>
                                <p class="text-xs text-slate-400">Alumni {{ testi.graduation_year }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 11. Sosial Media Section (Position Ke-11) -->
        <section class="py-20 bg-white overflow-hidden">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="text-center mb-12"
                    v-motion
                    :initial="{ opacity: 0, y: 18 }"
                    :visible="{ opacity: 1, y: 0, transition: { duration: 650 } }"
                >
                    <h3 class="text-4xl md:text-5xl font-extrabold text-slate-900">Sosial Media</h3>
                    <p class="mt-4 text-slate-600 max-w-2xl mx-auto">
                        Ikuti update kegiatan, informasi, dan konten terbaru dari SMKS Muhammadiyah 2 Genteng.
                    </p>
                    <div class="w-20 h-1.5 bg-brand-500 mx-auto mt-6 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a
                        href="https://www.instagram.com/smkmuh2genteng.1976?igsh=aXljbWp4bGhmMWdu"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm hover:shadow-xl hover:border-brand-200 transition-all hover:-translate-y-2 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2"
                        v-motion
                        :initial="{ opacity: 0, y: 16, scale: 0.98 }"
                        :visible="{ opacity: 1, y: 0, scale: 1, transition: { duration: 650, delay: 0 } }"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="inline-flex items-center rounded-full bg-rose-50 text-rose-700 px-3 py-1 text-xs font-bold tracking-wide">
                                    IG
                                </div>
                                <div class="mt-4 text-xl font-extrabold text-slate-900">Instagram</div>
                                <div class="mt-2 text-sm text-slate-600 break-words">@smkmuh2genteng.1976</div>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-700 group-hover:bg-rose-100 transition-colors">
                                <Instagram aria-hidden="true" class="h-6 w-6 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6" />
                            </div>
                        </div>
                        <div class="mt-6 text-sm font-semibold text-brand-700 group-hover:text-brand-600 transition-colors flex items-center gap-1">
                            <span>Kunjungi Profil</span>
                            <ArrowRight class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                        </div>
                    </a>

                    <a
                        href="https://www.tiktok.com/@smkmuhammadiyah2genteng?_r=1&_t=ZS-96qJeq5RMTo"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm hover:shadow-xl hover:border-brand-200 transition-all hover:-translate-y-2 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2"
                        v-motion
                        :initial="{ opacity: 0, y: 16, scale: 0.98 }"
                        :visible="{ opacity: 1, y: 0, scale: 1, transition: { duration: 650, delay: 120 } }"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="inline-flex items-center rounded-full bg-slate-100 text-slate-800 px-3 py-1 text-xs font-bold tracking-wide">
                                    TT
                                </div>
                                <div class="mt-4 text-xl font-extrabold text-slate-900">TikTok</div>
                                <div class="mt-2 text-sm text-slate-600 break-words">@smkmuhammadiyah2genteng</div>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-900 group-hover:bg-slate-200 transition-colors">
                                <svg aria-hidden="true" viewBox="0 0 48 48" class="h-7 w-7 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                                    <path fill="currentColor" d="M34.1 6c.6 5 3.2 8.9 8.1 11.5v5.5c-3.1.1-6-1-8.1-2.3v10.8c0 8-6.2 14.5-14 14.5-7.5 0-13.6-6.5-13.6-14.5C6.5 23 12.2 16.5 20 16v5.8c-4.4.5-7.6 4.4-6.9 8.9.6 3.6 3.7 6.3 7.3 6.3 4 0 7.3-3.3 7.3-7.4V6h6.4Z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-6 text-sm font-semibold text-brand-700 group-hover:text-brand-600 transition-colors flex items-center gap-1">
                            <span>Kunjungi Profil</span>
                            <ArrowRight class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                        </div>
                    </a>

                    <a
                        href="https://www.facebook.com/share/17nT7GLwsk/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm hover:shadow-xl hover:border-brand-200 transition-all hover:-translate-y-2 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2"
                        v-motion
                        :initial="{ opacity: 0, y: 16, scale: 0.98 }"
                        :visible="{ opacity: 1, y: 0, scale: 1, transition: { duration: 650, delay: 240 } }"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-3 py-1 text-xs font-bold tracking-wide">
                                    FB
                                </div>
                                <div class="mt-4 text-xl font-extrabold text-slate-900">Facebook</div>
                                <div class="mt-2 text-sm text-slate-600 break-words">SMKS Muhammadiyah 2 Genteng</div>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 group-hover:bg-blue-100 transition-colors">
                                <Facebook aria-hidden="true" class="h-6 w-6 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6" />
                            </div>
                        </div>
                        <div class="mt-6 text-sm font-semibold text-brand-700 group-hover:text-brand-600 transition-colors flex items-center gap-1">
                            <span>Kunjungi Halaman</span>
                            <ArrowRight class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<style scoped>
:deep(.slider-custom-bullet) {
    width: 8px;
    height: 8px;
    border-radius: 9999px;
    background-color: rgba(255, 255, 255, 0.35);
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-block;
}

:deep(.slider-custom-bullet:hover) {
    background-color: rgba(255, 255, 255, 0.7);
}

:deep(.slider-custom-bullet-active) {
    width: 28px;
    height: 8px;
    border-radius: 9999px;
    background: linear-gradient(90deg, #0284c7, #38bdf8);
    box-shadow: 0 0 12px rgba(56, 189, 248, 0.6);
}
</style>
