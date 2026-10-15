<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, Navigation, EffectCoverflow } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';
import 'swiper/css/effect-coverflow';

defineProps({
    tefa: Object,
});

const modules = [Autoplay, Pagination, Navigation, EffectCoverflow];
</script>

<template>
    <Head :title="tefa.title" />

    <MainLayout>
        <div class="relative bg-slate-900 py-20 sm:py-28 overflow-hidden">
            <div
                v-if="tefa.background_image"
                class="absolute inset-0 bg-center bg-cover scale-105"
                :style="{ backgroundImage: `url(${tefa.background_image})` }"
            ></div>
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 bg-slate-950/35"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/45 via-slate-900/20 to-slate-950/40"></div>
                <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-gradient-to-b from-brand-500/20 to-transparent rounded-full blur-3xl opacity-50 transform rotate-12"></div>
                <div class="absolute -bottom-1/2 -left-1/2 w-full h-full bg-gradient-to-t from-blue-600/20 to-transparent rounded-full blur-3xl opacity-50 transform -rotate-12"></div>
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTAgMGg0MHY0MEgwVjB6bTIwIDIwdjIwaDIwVjIwaC0yMHptMC0yMHYyMGgyMFYwaC0yMHoiIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyIgZmlsbC1ydWxlPSJldmVub2RkIi8+PC9zdmc+')] opacity-20"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10 animate__animated animate__fadeInUp">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-4 tracking-tight font-display">
                    {{ tefa.title }}
                </h1>
                <p class="text-white/70 max-w-2xl mx-auto text-base sm:text-lg leading-relaxed">
                    {{ tefa.hero_subtitle || 'Teaching Factory • Dokumentasi dan informasi kegiatan' }}
                </p>
            </div>
        </div>

        <div class="py-16 bg-slate-50 min-h-screen">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <div class="rounded-[2rem] border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <div class="text-sm font-bold text-slate-800">Konten</div>
                        <div class="text-xs text-slate-500 mt-1">Informasi TEFA</div>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div v-if="tefa.content" class="prose prose-slate prose-lg max-w-none prose-headings:font-display prose-headings:font-bold prose-a:text-brand-600 hover:prose-a:text-brand-500 prose-img:rounded-2xl prose-img:shadow-sm" v-html="tefa.content"></div>
                        <div v-else class="text-slate-400">
                            Belum ada konten yang ditambahkan.
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <div class="text-sm font-bold text-slate-800">Galeri TEFA</div>
                        <div class="text-xs text-slate-500 mt-1">Gambar berjalan otomatis</div>
                    </div>

                    <div class="p-6">
                        <div v-if="tefa.gallery && tefa.gallery.length > 0" class="overflow-hidden">
                            <Swiper
                                :modules="modules"
                                :effect="'coverflow'"
                                :grabCursor="true"
                                :centeredSlides="true"
                                :slidesPerView="'auto'"
                                :coverflowEffect="{
                                    rotate: 30,
                                    stretch: 0,
                                    depth: 120,
                                    modifier: 1,
                                    slideShadows: true,
                                }"
                                :autoplay="{
                                    delay: 2200,
                                    disableOnInteraction: false,
                                    pauseOnMouseEnter: true
                                }"
                                :pagination="{ clickable: true, dynamicBullets: true }"
                                :navigation="true"
                                :loop="true"
                                class="w-full !pb-12"
                            >
                                <SwiperSlide v-for="(img, i) in tefa.gallery" :key="i" class="w-[180px] sm:w-[220px]">
                                    <div class="group rounded-2xl overflow-hidden aspect-[4/3] shadow-md border-2 border-white bg-white relative">
                                        <img :src="img" alt="TEFA" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                        <div class="absolute inset-0 bg-brand-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </div>
                                </SwiperSlide>
                            </Swiper>
                        </div>
                        <div v-else class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-slate-400">
                            <div class="text-sm font-bold">Belum ada gambar</div>
                            <div class="text-xs mt-1">Tambahkan gambar dari menu admin TEFA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
