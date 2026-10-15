<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BadgeCheck, BriefcaseBusiness, Factory, Wrench, Users, Target, Award, Zap, Shield, Cpu, Settings, Sparkles } from 'lucide-vue-next';

const features = [
    { title: 'Mesin Otomotif', description: 'Mempelajari perawatan dan perbaikan mesin bensin dan diesel.' },
    { title: 'Sistem Kelistrikan', description: 'Pemahaman mendalam tentang sistem kelistrikan pada kendaraan modern.' },
    { title: 'Chassis & Pemindah Tenaga', description: 'Teknik perbaikan sistem rem, kemudi, suspensi, dan transmisi.' },
    { title: 'Engine Management System', description: 'Diagnosa mesin berbasis komputer dan sistem injeksi elektronik.' }
];

const highlightCards = [
    {
        icon: Factory,
        title: 'Kolaborasi Industri',
        description: 'Pembelajaran relevan dengan kebutuhan dunia kerja untuk TKR.',
    },
    {
        icon: Wrench,
        title: 'Praktik Intensif',
        description: 'Fokus pada keterampilan praktik dan problem-solving berbasis kasus nyata.',
    },
    {
        icon: BadgeCheck,
        title: 'Kompetensi & Sertifikasi',
        description: 'Penguatan kompetensi melalui proyek, portofolio, dan uji kemampuan.',
    },
    {
        icon: BriefcaseBusiness,
        title: 'Peluang Karier',
        description: 'Meningkatkan kesiapan kerja, magang, dan karier setelah lulus.',
    },
];

const industrialClassFeatures = [
    {
        icon: Users,
        title: 'Mentor Industri',
        description: 'Pembimbing langsung dari praktisi otomotif dengan pengalaman 10+ tahun di industri.',
        color: 'from-blue-500 to-cyan-400',
        stat: '15+ Mentor'
    },
    {
        icon: Target,
        title: 'Kurikulum Industri',
        description: 'Materi pembelajaran disusun berdasarkan kebutuhan aktual perusahaan otomotif terkemuka.',
        color: 'from-purple-500 to-pink-500',
        stat: '100% Relevan'
    },
    {
        icon: Award,
        title: 'Sertifikasi Industri',
        description: 'Sertifikat kompetensi yang diakui oleh asosiasi otomotif nasional dan internasional.',
        color: 'from-amber-500 to-orange-500',
        stat: '5+ Sertifikasi'
    },
    {
        icon: Zap,
        title: 'Teknologi Terkini',
        description: 'Akses ke peralatan diagnostik dan perangkat lunak otomotif generasi terbaru.',
        color: 'from-emerald-500 to-teal-400',
        stat: 'Tech Ready'
    },
    {
        icon: Shield,
        title: 'Jaminan Kualitas',
        description: 'Standar operasional prosedur sesuai dengan ISO 9001:2015 untuk pelatihan otomotif.',
        color: 'from-slate-700 to-slate-900',
        stat: 'ISO Certified'
    },
    {
        icon: Cpu,
        title: 'Smart Workshop',
        description: 'Bengkel cerdas dengan sistem IoT untuk monitoring dan analisis performa kendaraan.',
        color: 'from-violet-600 to-indigo-600',
        stat: 'IoT Enabled'
    },
];

const partnerCompanies = [
    { name: 'Toyota', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Toyota_carlogo.svg/800px-Toyota_carlogo.svg.png' },
    { name: 'Honda', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7b/Honda_Logo.svg/800px-Honda_Logo.svg.png' },
    { name: 'Mitsubishi', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/Mitsubishi_logo.svg/800px-Mitsubishi_logo.svg.png' },
    { name: 'Suzuki', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Suzuki_logo_2.svg/800px-Suzuki_logo_2.svg.png' },
    { name: 'Nissan', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2d/Nissan_logo.png/800px-Nissan_logo.png' },
    { name: 'BMW', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/BMW.svg/800px-BMW.svg.png' },
];
</script>

<template>
    <Head title="Teknik Kendaraan Ringan (TKR)" />

    <MainLayout>
        <div class="relative py-24 bg-white overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-brand-50 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-96 h-96 bg-brand-50 rounded-full blur-3xl opacity-50"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="lg:flex lg:items-center lg:gap-16">
                    <div class="lg:w-1/2 mb-12 lg:mb-0">
                        <h2 class="text-brand-600 font-bold tracking-wider uppercase text-sm mb-4">Program Keahlian</h2>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 mb-8 leading-tight">
                            Teknik Kendaraan <span class="text-brand-500">Ringan</span>
                        </h1>
                        <p class="text-lg text-slate-600 mb-10 leading-relaxed">
                            Mencetak tenaga ahli di bidang otomotif yang kompeten dalam melakukan perawatan, perbaikan, dan diagnosa kendaraan ringan sesuai standar industri global.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                            <div v-for="(feature, index) in features" :key="index" class="flex gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="shrink-0 w-10 h-10 bg-brand-500 rounded-xl flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-1">{{ feature.title }}</h4>
                                    <p class="text-sm text-slate-500">{{ feature.description }}</p>
                                </div>
                            </div>
                        </div>

                        <button class="px-8 py-4 bg-brand-500 text-white font-bold rounded-2xl shadow-xl shadow-brand-500/30 hover:bg-brand-600 transition-all">
                            Daftar Sekarang
                        </button>
                    </div>

                    <div class="lg:w-1/2">
                        <div class="relative">
                            <div class="absolute inset-0 bg-brand-500 rounded-[2.5rem] rotate-3 opacity-10"></div>
                            <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&q=80&w=800"
                                 alt="TKR"
                                 class="relative rounded-[2.5rem] shadow-2xl object-cover aspect-[4/3] w-full" />
                        </div>

                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                            <div
                                v-for="(card, idx) in highlightCards"
                                :key="`tkr-highlight-${idx}`"
                                class="group rounded-2xl bg-slate-50 border border-slate-100 p-5 hover:bg-white hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300"
                            >
                                <div class="flex items-start gap-3">
                                    <div class="h-11 w-11 rounded-2xl bg-brand-500/10 text-brand-600 grid place-items-center group-hover:bg-brand-500 group-hover:text-white transition-colors">
                                        <component :is="card.icon" class="h-5 w-5" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-slate-900 font-extrabold leading-tight">{{ card.title }}</p>
                                        <p class="mt-1 text-sm text-slate-600 leading-relaxed">
                                            {{ card.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Kelas Industri Modern -->
        <div class="relative py-24 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 overflow-hidden group/section hover:shadow-2xl transition-all duration-700">
            <!-- Animated Background Elements with Parallax -->
            <div class="absolute inset-0 overflow-hidden parallax-container">
                <!-- Large Gradient Orbs -->
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl animate-pulse parallax-layer" data-depth="0.1" style="animation-duration: 8s;"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse parallax-layer" data-depth="0.15" style="animation-duration: 10s; animation-delay: 1s;"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-64 bg-gradient-to-r from-transparent via-blue-500/5 to-transparent animate-pulse parallax-layer" data-depth="0.05" style="animation-duration: 12s;"></div>

                <!-- Floating Elements with Parallax -->
                <div class="absolute top-1/4 left-1/4 w-4 h-4 bg-cyan-400/30 rounded-full animate-float parallax-layer" data-depth="0.3" style="animation-duration: 3s;"></div>
                <div class="absolute top-1/3 right-1/3 w-3 h-3 bg-blue-400/30 rounded-full animate-float parallax-layer" data-depth="0.4" style="animation-duration: 4s; animation-delay: 0.5s;"></div>
                <div class="absolute bottom-1/4 left-1/3 w-2 h-2 bg-purple-400/30 rounded-full animate-float parallax-layer" data-depth="0.35" style="animation-duration: 5s; animation-delay: 1s;"></div>

                <!-- Additional Floating Elements -->
                <div class="absolute top-3/4 right-1/4 w-2 h-2 bg-emerald-400/20 rounded-full animate-float parallax-layer" data-depth="0.25" style="animation-duration: 6s; animation-delay: 1.5s;"></div>
                <div class="absolute bottom-1/3 right-1/4 w-3 h-3 bg-amber-400/20 rounded-full animate-float parallax-layer" data-depth="0.2" style="animation-duration: 7s; animation-delay: 2s;"></div>

                <!-- Animated Grid Lines -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-blue-400 to-transparent animate-line"></div>
                    <div class="absolute top-1/3 left-0 w-full h-px bg-gradient-to-r from-transparent via-purple-400 to-transparent animate-line" style="animation-delay: 0.3s;"></div>
                    <div class="absolute top-2/3 left-0 w-full h-px bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-line" style="animation-delay: 0.6s;"></div>
                </div>
            </div>

            <!-- Grid Pattern Overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:40px_40px] opacity-20"></div>

            <!-- Animated Border -->
            <div class="absolute inset-0 rounded-lg border-2 border-transparent animate-border" style="
                background: linear-gradient(90deg, #3b82f6, #06b6d4, #3b82f6);
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
            "></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <!-- Header Section -->
                <div class="text-center mb-16 relative">
                    <!-- Floating Badge -->
                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-full border border-blue-500/30 mb-6 animate-float shadow-lg">
                        <Sparkles class="h-4 w-4 text-blue-300 animate-spin" style="animation-duration: 3s;" />
                        <span class="text-sm font-semibold text-blue-200">KELAS INDUSTRI</span>
                    </div>

                    <!-- Animated Title -->
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        <span class="gradient-text">Kelas Industri</span> TKR
                    </h2>

                    <!-- Description with shimmer effect -->
                    <div class="relative inline-block">
                        <p class="text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed relative z-10">
                            Program khusus yang dirancang untuk mencetak tenaga ahli otomotif siap kerja dengan standar industri global.
                            Kolaborasi langsung dengan perusahaan otomotif terkemuka.
                        </p>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-cyan-500/5 to-purple-500/5 rounded-xl animate-shimmer"></div>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="absolute top-0 left-1/4 w-2 h-2 bg-cyan-400 rounded-full opacity-50 animate-pulse" style="animation-duration: 2s;"></div>
                    <div class="absolute top-10 right-1/4 w-3 h-3 bg-blue-400 rounded-full opacity-30 animate-pulse" style="animation-duration: 3s; animation-delay: 0.5s;"></div>
                    <div class="absolute bottom-0 left-1/3 w-1 h-1 bg-purple-400 rounded-full opacity-40 animate-pulse" style="animation-duration: 4s;"></div>
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-20">
                    <div
                        v-for="(feature, index) in industrialClassFeatures"
                        :key="index"
                        class="group relative bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-sm rounded-2xl p-6 border border-slate-700/50 hover:border-slate-600/70 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 glow-on-hover hover:z-10"
                        :style="`--hover-color: ${feature.color.split(' ')[1] || '#3b82f6'}`"
                    >
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-br opacity-0 group-hover:opacity-20 transition-opacity duration-500 rounded-2xl" :class="feature.color"></div>

                        <!-- Icon Container -->
                        <div class="relative mb-5">
                            <div class="absolute inset-0 bg-gradient-to-br blur-xl opacity-30 rounded-2xl" :class="feature.color"></div>
                            <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-br flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300" :class="feature.color">
                                <component :is="feature.icon" class="h-7 w-7 text-white group-hover:animate-pulse" />
                            </div>
                        </div>

                        <!-- Content -->
                        <h3 class="text-xl font-bold text-white mb-3 group-hover:translate-x-1 transition-transform duration-300">{{ feature.title }}</h3>
                        <p class="text-slate-300 mb-4 leading-relaxed group-hover:text-slate-200 transition-colors duration-300">{{ feature.description }}</p>

                        <!-- Stat Badge -->
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-800/60 rounded-full border border-slate-700/50 group-hover:scale-105 group-hover:border-blue-500/30 transition-all duration-300">
                            <span class="text-sm font-semibold text-blue-300 group-hover:text-cyan-300 transition-colors">{{ feature.stat }}</span>
                        </div>

                        <!-- Animated Decorative Line -->
                        <div class="absolute bottom-0 left-6 right-6 h-px bg-gradient-to-r from-transparent via-slate-600/50 to-transparent group-hover:via-blue-400/50 transition-all duration-500">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-cyan-400 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                    </div>
                </div>

                <!-- Partner Companies Section -->
                <div class="bg-gradient-to-b from-slate-800/40 to-transparent rounded-3xl p-8 border border-slate-700/30 backdrop-blur-sm group/partner-section hover:border-slate-600/50 transition-all duration-500">
                    <!-- Section Header with Animation -->
                    <div class="text-center mb-10 relative">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-px bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-50 group-hover/partner-section:opacity-100 transition-opacity duration-500"></div>

                        <h3 class="text-2xl font-bold text-white mb-3 group-hover/partner-section:gradient-text transition-all duration-500">
                            Bekerja Sama dengan
                        </h3>

                        <div class="relative inline-block">
                            <p class="text-slate-300 group-hover/partner-section:text-slate-200 transition-colors duration-300">
                                Perusahaan otomotif terkemuka yang mendukung program Kelas Industri TKR
                            </p>
                            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-16 h-px bg-gradient-to-r from-blue-500 to-cyan-400 opacity-0 group-hover/partner-section:opacity-100 transition-opacity duration-500"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                        <div
                            v-for="(company, index) in partnerCompanies"
                            :key="index"
                            class="group relative"
                        >
                            <div class="aspect-square bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 flex items-center justify-center p-6 transition-all duration-300 group-hover:border-slate-600 group-hover:shadow-xl group-hover:shadow-blue-500/5 group-hover:scale-105">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-cyan-400/20 rounded-full blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <img
                                        :src="company.logo"
                                        :alt="company.name"
                                        class="relative max-w-full max-h-12 object-contain opacity-80 group-hover:opacity-100 transition-all duration-300 group-hover:drop-shadow-lg"
                                    />
                                </div>
                            </div>
                            <p class="text-center mt-3 text-sm font-medium text-slate-400 group-hover:text-slate-300 transition-colors group-hover:font-semibold">{{ company.name }}</p>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="text-center mt-12">
                        <button class="group relative px-12 py-5 bg-gradient-to-r from-blue-600 via-cyan-500 to-blue-600 text-white font-bold rounded-2xl shadow-2xl shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-500 hover:-translate-y-1 overflow-hidden glow-on-hover">
                            <!-- Animated Background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-cyan-400 opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>

                            <!-- Shimmer Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>

                            <!-- Content -->
                            <span class="relative flex items-center justify-center gap-3">
                                <Settings class="h-6 w-6 group-hover:animate-spin" style="animation-duration: 2s;" />
                                <span class="text-lg font-extrabold tracking-wide">Daftar Kelas Industri Sekarang</span>
                                <svg class="h-5 w-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </span>

                            <!-- Pulsing Ring -->
                            <div class="absolute inset-0 border-2 border-blue-400/30 rounded-2xl group-hover:border-blue-300/50 transition-all duration-500 group-hover:scale-105"></div>
                        </button>

                        <!-- Countdown Badge -->
                        <div class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-slate-800/60 rounded-full border border-slate-700/50">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <p class="text-sm font-medium text-slate-300">
                                <span class="font-bold text-cyan-300">Batch berikutnya</span> dimulai 15 Juli 2026
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center group">
                        <div class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-cyan-300 bg-clip-text text-transparent mb-2 group-hover:scale-110 transition-transform duration-300">95%</div>
                        <p class="text-slate-300 font-medium group-hover:text-blue-300 transition-colors">Penyerapan Kerja</p>
                        <div class="mt-2 h-1 w-16 mx-auto bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full group-hover:w-20 transition-all duration-300"></div>
                    </div>
                    <div class="text-center group">
                        <div class="text-4xl font-bold bg-gradient-to-r from-purple-400 to-pink-300 bg-clip-text text-transparent mb-2 group-hover:scale-110 transition-transform duration-300">50+</div>
                        <p class="text-slate-300 font-medium group-hover:text-purple-300 transition-colors">Proyek Industri</p>
                        <div class="mt-2 h-1 w-16 mx-auto bg-gradient-to-r from-purple-500 to-pink-400 rounded-full group-hover:w-20 transition-all duration-300"></div>
                    </div>
                    <div class="text-center group">
                        <div class="text-4xl font-bold bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent mb-2 group-hover:scale-110 transition-transform duration-300">1000+</div>
                        <p class="text-slate-300 font-medium group-hover:text-emerald-300 transition-colors">Alumni Sukses</p>
                        <div class="mt-2 h-1 w-16 mx-auto bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full group-hover:w-20 transition-all duration-300"></div>
                    </div>
                    <div class="text-center group">
                        <div class="text-4xl font-bold bg-gradient-to-r from-amber-400 to-orange-300 bg-clip-text text-transparent mb-2 group-hover:scale-110 transition-transform duration-300">10+</div>
                        <p class="text-slate-300 font-medium group-hover:text-amber-300 transition-colors">Tahun Pengalaman</p>
                        <div class="mt-2 h-1 w-16 mx-auto bg-gradient-to-r from-amber-500 to-orange-400 rounded-full group-hover:w-20 transition-all duration-300"></div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script>
export default {
    mounted() {
        this.initParallax();
        this.initScrollAnimations();
    },
    methods: {
        initParallax() {
            const parallaxContainer = this.$el.querySelector('.parallax-container');
            if (!parallaxContainer) return;

            const handleMouseMove = (e) => {
                const { clientX, clientY } = e;
                const { width, height, left, top } = parallaxContainer.getBoundingClientRect();

                const x = (clientX - left) / width;
                const y = (clientY - top) / height;

                const layers = parallaxContainer.querySelectorAll('.parallax-layer');
                layers.forEach(layer => {
                    const depth = parseFloat(layer.dataset.depth) || 0.1;
                    const xOffset = (x - 0.5) * depth * 100;
                    const yOffset = (y - 0.5) * depth * 100;

                    layer.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
                });
            };

            parallaxContainer.addEventListener('mousemove', handleMouseMove);

            // Cleanup
            this.$once('hook:beforeDestroy', () => {
                parallaxContainer.removeEventListener('mousemove', handleMouseMove);
            });
        },
        initScrollAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in-view');
                    }
                });
            }, observerOptions);

            // Observe feature cards
            const featureCards = this.$el.querySelectorAll('.glow-on-hover');
            featureCards.forEach(card => observer.observe(card));

            // Cleanup
            this.$once('hook:beforeDestroy', () => {
                featureCards.forEach(card => observer.unobserve(card));
            });
        }
    }
}
</script>

<style scoped>
/* Hardware Accelerated Animations */
@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

@keyframes float {
    0%, 100% {
        transform: translate3d(0, 0, 0);
    }
    50% {
        transform: translate3d(0, -10px, 0);
    }
}

@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

@keyframes line {
    0% {
        transform: translate3d(-100%, 0, 0);
    }
    100% {
        transform: translate3d(100%, 0, 0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 30px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale3d(0.8, 0.8, 1);
    }
    to {
        opacity: 1;
        transform: scale3d(1, 1, 1);
    }
}

/* Optimized Animation Classes */
.animate-float {
    animation: float 3s ease-in-out infinite;
    will-change: transform;
    backface-visibility: hidden;
}

.animate-shimmer {
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
    will-change: background-position;
}

.animate-line {
    animation: line 15s linear infinite;
    will-change: transform;
}

.animate-in-view {
    animation: fadeInUp 0.8s cubic-bezier(0.215, 0.61, 0.355, 1) forwards;
    opacity: 0;
}

/* Performance Optimized Transitions */
.parallax-layer {
    will-change: transform;
    transform: translate3d(0, 0, 0);
    backface-visibility: hidden;
}

.glow-on-hover {
    transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: box-shadow, transform;
}

.glow-on-hover:hover {
    box-shadow: 0 0 30px rgba(59, 130, 246, 0.6);
    transform: translate3d(0, -2px, 0);
}

/* Hardware Accelerated Hover Effects */
.hover-lift {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
}

.hover-lift:hover {
    transform: translate3d(0, -4px, 0);
}

.hover-scale {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
}

.hover-scale:hover {
    transform: scale3d(1.05, 1.05, 1);
}

/* Custom scrollbar for modern browsers */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: rgba(15, 23, 42, 0.5);
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #3b82f6, #06b6d4);
    border-radius: 5px;
    will-change: background;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #2563eb, #0891b2);
}

/* Modern gradient text with hardware acceleration */
.gradient-text {
    background: linear-gradient(90deg, #3b82f6, #06b6d4, #8b5cf6);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    background-size: 200% auto;
    animation: gradient 3s ease infinite;
    will-change: background-position;
}

/* Smooth Image Loading */
.image-loading {
    opacity: 0;
    transform: scale3d(0.95, 0.95, 1);
    transition: opacity 0.5s ease, transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.image-loaded {
    opacity: 1;
    transform: scale3d(1, 1, 1);
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .animate-float,
    .animate-shimmer,
    .animate-line,
    .gradient-text,
    .hover-lift,
    .hover-scale {
        animation: none;
        transition: none;
    }

    .glow-on-hover:hover {
        box-shadow: none;
        transform: none;
    }
}

/* Touch Device Optimizations */
@media (hover: none) and (pointer: coarse) {
    .glow-on-hover:hover {
        box-shadow: none;
        transform: none;
    }

    .hover-lift:hover,
    .hover-scale:hover {
        transform: none;
    }
}
</style>
