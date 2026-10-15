<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BadgeCheck, BriefcaseBusiness, Factory, Wrench } from 'lucide-vue-next';

const features = [
    { title: 'Mikrokontroler', description: 'Pemrograman chip untuk sistem kontrol otomatis dan IoT.' },
    { title: 'Robotika Dasar', description: 'Perancangan dan perakitan robot industri sederhana.' },
    { title: 'Pneumatik & PLC', description: 'Sistem otomasi industri menggunakan kontrol udara dan PLC.' },
    { title: 'Troubleshooting Elektronika', description: 'Teknik perbaikan perangkat elektronik rumah tangga dan industri.' }
];

const highlightCards = [
    {
        icon: Factory,
        title: 'Kolaborasi Industri',
        description: 'Pembelajaran relevan dengan kebutuhan dunia kerja untuk TEI.',
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
</script>

<template>
    <Head title="Teknik Elektronika Industri (TEI)" />

    <MainLayout>
        <div class="relative py-24 bg-white overflow-hidden">
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-brand-50 rounded-full blur-3xl opacity-50"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="lg:flex lg:items-center lg:gap-16">
                    <div class="lg:w-1/2 mb-12 lg:mb-0">
                        <h2 class="text-brand-600 font-bold tracking-wider uppercase text-sm mb-4">Program Keahlian</h2>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 mb-8 leading-tight">
                            Teknik Elektronika <span class="text-brand-500">Industri</span>
                        </h1>
                        <p class="text-lg text-slate-600 mb-10 leading-relaxed">
                            Membekali siswa dengan kompetensi sistem kontrol otomatis, robotika, dan teknologi IoT yang sangat dibutuhkan di era industri 4.0.
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
                            <div class="absolute inset-0 bg-brand-500 rounded-[2.5rem] rotate-6 opacity-10"></div>
                            <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&q=80&w=800"
                                 alt="TEI"
                                 class="relative rounded-[2.5rem] shadow-2xl object-cover aspect-[4/3] w-full" />
                        </div>

                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                            <div
                                v-for="(card, idx) in highlightCards"
                                :key="`tei-highlight-${idx}`"
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
    </MainLayout>
</template>
