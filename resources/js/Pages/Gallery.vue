<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Camera, Filter, Maximize2, X, ChevronLeft, ChevronRight, Sparkles } from 'lucide-vue-next';

const props = defineProps({
    gallery: Array,
    categories: Array,
});

const activeCategory = ref('Semua');
const selectedIndex = ref(null);

const filteredGallery = computed(() => {
    const items = props.gallery || [];
    if (!props.categories || props.categories.length === 0) return items;
    if (activeCategory.value === 'Semua') return items;
    const target = activeCategory.value.trim().toLowerCase();
    return items.filter((item) => (item.category || 'Umum').trim().toLowerCase() === target);
});

const activePhoto = computed(() => {
    if (selectedIndex.value === null || !filteredGallery.value.length) return null;
    return filteredGallery.value[selectedIndex.value] || null;
});

const openLightbox = (index) => {
    selectedIndex.value = index;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = 'hidden';
    }
};

const closeLightbox = () => {
    selectedIndex.value = null;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
};

const prevPhoto = () => {
    if (selectedIndex.value === null || !filteredGallery.value.length) return;
    selectedIndex.value = (selectedIndex.value - 1 + filteredGallery.value.length) % filteredGallery.value.length;
};

const nextPhoto = () => {
    if (selectedIndex.value === null || !filteredGallery.value.length) return;
    selectedIndex.value = (selectedIndex.value + 1) % filteredGallery.value.length;
};

const handleKeyDown = (e) => {
    if (selectedIndex.value === null) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') prevPhoto();
    if (e.key === 'ArrowRight') nextPhoto();
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
</script>

<template>
    <Head title="Galeri Dokumentasi" />
    <MainLayout>
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-slate-950 pt-28 pb-32">
            <div class="absolute inset-0 opacity-30">
                <div class="absolute -top-48 -right-40 h-[38rem] w-[38rem] rounded-full blur-3xl bg-gradient-to-br from-brand-500 to-indigo-500 animate-pulse"></div>
                <div class="absolute -bottom-64 -left-40 h-[40rem] w-[40rem] rounded-full blur-3xl bg-gradient-to-tr from-emerald-500 to-brand-500 animate-pulse"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_10%,rgba(255,255,255,0.10),transparent_35%),radial-gradient(circle_at_80%_40%,rgba(59,130,246,0.18),transparent_40%),radial-gradient(circle_at_50%_100%,rgba(16,185,129,0.14),transparent_45%)]"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center" v-motion :initial="{ opacity: 0, y: 30 }" :enter="{ opacity: 1, y: 0, transition: { duration: 800 } }">
                <div class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-white/75 backdrop-blur-md mb-6 mx-auto">
                    <Camera class="h-4 w-4 text-brand-400" />
                    Dokumentasi Kegiatan
                </div>

                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black tracking-tight text-white mb-6">
                    Galeri <span class="bg-gradient-to-r from-brand-400 to-indigo-400 bg-clip-text text-transparent">Sekolah</span>
                </h1>

                <p class="text-lg md:text-xl text-white/70 max-w-3xl mx-auto leading-relaxed">
                    Merekam setiap momen berharga, prestasi, dan kebersamaan dalam lingkungan pendidikan SMKS Muhammadiyah 2 Genteng.
                </p>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="py-24 bg-slate-50 min-h-screen relative -mt-10 rounded-t-[3rem] shadow-[0_-20px_40px_rgba(0,0,0,0.1)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Categories -->
                <div v-if="categories && categories.length > 0" class="mb-12 flex flex-wrap justify-center gap-3" v-motion-slide-visible-bottom>
                    <button
                        @click="activeCategory = 'Semua'"
                        :class="[
                            'px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 shadow-sm',
                            activeCategory === 'Semua'
                                ? 'bg-brand-500 text-white shadow-brand-500/30 ring-2 ring-brand-500 ring-offset-2'
                                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
                        ]"
                    >
                        Semua
                    </button>
                    <button
                        v-for="cat in categories"
                        :key="cat.id"
                        @click="activeCategory = cat.name"
                        :class="[
                            'px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 shadow-sm',
                            activeCategory === cat.name
                                ? 'bg-brand-500 text-white shadow-brand-500/30 ring-2 ring-brand-500 ring-offset-2'
                                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
                        ]"
                    >
                        {{ cat.name }}
                    </button>
                </div>

                <!-- Photos Grid -->
                <div v-if="filteredGallery && filteredGallery.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        v-for="(item, index) in filteredGallery"
                        :key="item.id || index"
                        @click="openLightbox(index)"
                        v-motion
                        :initial="{ opacity: 0, scale: 0.95, y: 30 }"
                        :visible="{ opacity: 1, scale: 1, y: 0, transition: { duration: 500, delay: index * 60 } }"
                        class="group cursor-pointer overflow-hidden rounded-[2rem] bg-white shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-slate-100 flex flex-col"
                    >
                        <div class="relative h-72 w-full overflow-hidden bg-slate-100">
                            <img
                                :src="item.image"
                                :alt="item.title"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-between p-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/90 text-slate-900 backdrop-blur-md">
                                    <Sparkles class="w-3 h-3 text-brand-500" />
                                    {{ item.category || 'Galeri' }}
                                </span>
                                <div class="h-10 w-10 rounded-full bg-brand-500 text-white grid place-items-center shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                    <Maximize2 class="w-4 h-4" />
                                </div>
                            </div>
                        </div>

                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <h3 class="font-bold text-lg text-slate-900 leading-tight group-hover:text-brand-600 transition-colors">
                                {{ item.title }}
                            </h3>
                            <div class="w-10 h-1 bg-brand-500 rounded-full mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-24 bg-white rounded-[3rem] border border-slate-200 border-dashed shadow-sm" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { duration: 600 } }">
                    <div class="w-24 h-24 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <Filter class="w-12 h-12 text-slate-300" />
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Belum Ada Data</h3>
                    <p class="text-slate-500 text-base max-w-md mx-auto">
                        Data galeri untuk kategori ini belum tersedia. Silakan pilih kategori lain atau periksa kembali nanti.
                    </p>
                </div>
            </div>
        </div>

        <!-- Fullscreen Image Lightbox Modal -->
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
                    v-if="activePhoto"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-xl p-4 sm:p-6"
                    @click.self="closeLightbox"
                >
                    <!-- Close button -->
                    <button
                        type="button"
                        @click="closeLightbox"
                        aria-label="Tutup preview"
                        class="absolute top-5 right-5 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-white/20 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                    >
                        <X class="w-6 h-6" />
                    </button>

                    <!-- Navigation: Previous -->
                    <button
                        v-if="filteredGallery.length > 1"
                        type="button"
                        @click="prevPhoto"
                        aria-label="Foto sebelumnya"
                        class="absolute left-4 sm:left-6 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-brand-500 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                    >
                        <ChevronLeft class="w-6 h-6" />
                    </button>

                    <!-- Navigation: Next -->
                    <button
                        v-if="filteredGallery.length > 1"
                        type="button"
                        @click="nextPhoto"
                        aria-label="Foto selanjutnya"
                        class="absolute right-4 sm:right-6 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-brand-500 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                    >
                        <ChevronRight class="w-6 h-6" />
                    </button>

                    <!-- Image & Caption Container -->
                    <div class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center" @click.stop>
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/15 max-h-[75vh] bg-black/40 flex items-center justify-center">
                            <img
                                :src="activePhoto.image"
                                :alt="activePhoto.title"
                                class="max-h-[75vh] w-auto max-w-full object-contain rounded-3xl"
                            />
                        </div>

                        <!-- Details Bar -->
                        <div class="mt-4 w-full max-w-2xl px-4 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center text-white flex items-center justify-between gap-4">
                            <div class="text-left min-w-0 flex-1">
                                <span class="text-[11px] font-extrabold uppercase tracking-widest text-brand-300">
                                    {{ activePhoto.category || 'Dokumentasi' }}
                                </span>
                                <h4 class="text-sm sm:text-base font-bold truncate">{{ activePhoto.title }}</h4>
                            </div>
                            <div class="text-xs font-semibold text-slate-300 shrink-0 px-3 py-1 rounded-full bg-white/10">
                                {{ (selectedIndex || 0) + 1 }} / {{ filteredGallery.length }}
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </MainLayout>
</template>
