<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Images, Maximize2, X, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed, ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    facility: Object,
});

function normalizeItem(item) {
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
}

const galleryImages = computed(() => {
    const items = Array.isArray(props.facility?.gallery) ? props.facility.gallery : [];
    return items.map(normalizeItem).filter(Boolean);
});

const selectedLightboxIndex = ref(null);

const activeLightboxPhoto = computed(() => {
    if (selectedLightboxIndex.value === null || !galleryImages.value.length) return null;
    return galleryImages.value[selectedLightboxIndex.value] || null;
});

const openLightbox = (index) => {
    selectedLightboxIndex.value = index % galleryImages.value.length;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = 'hidden';
    }
};

const closeLightbox = () => {
    selectedLightboxIndex.value = null;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
};

const prevLightboxPhoto = () => {
    if (selectedLightboxIndex.value === null || !galleryImages.value.length) return;
    selectedLightboxIndex.value = (selectedLightboxIndex.value - 1 + galleryImages.value.length) % galleryImages.value.length;
};

const nextLightboxPhoto = () => {
    if (selectedLightboxIndex.value === null || !galleryImages.value.length) return;
    selectedLightboxIndex.value = (selectedLightboxIndex.value + 1) % galleryImages.value.length;
};

const handleKeyDown = (e) => {
    if (selectedLightboxIndex.value === null) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') prevLightboxPhoto();
    if (e.key === 'ArrowRight') nextLightboxPhoto();
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

const hasGallery = computed(() => galleryImages.value.length > 0);
const shouldAutoScroll = computed(() => galleryImages.value.length >= 3);
const carouselItems = computed(() => {
    const items = galleryImages.value.map((img, idx) => ({
        ...img,
        originalIndex: idx,
        trackKey: `base-${idx}`,
    }));
    if (!shouldAutoScroll.value) return items;
    return [
        ...items,
        ...galleryImages.value.map((img, idx) => ({
            ...img,
            originalIndex: idx,
            trackKey: `clone-${idx}`,
        })),
    ];
});
const carouselStyle = computed(() => {
    if (!shouldAutoScroll.value) return {};
    return {
        '--gallery-duration': `${Math.max(galleryImages.value.length * 5, 18)}s`,
    };
});
</script>

<template>
    <Head :title="facility.title + ' - Fasilitas'" />

    <MainLayout>
        <div class="relative bg-slate-900 py-24 sm:py-32 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-gradient-to-b from-brand-500/20 to-transparent rounded-full blur-3xl opacity-50 transform rotate-12"></div>
                <div class="absolute -bottom-1/2 -left-1/2 w-full h-full bg-gradient-to-t from-blue-600/20 to-transparent rounded-full blur-3xl opacity-50 transform -rotate-12"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <Link :href="route('facilities')" class="inline-flex items-center gap-2 text-white/60 hover:text-white transition-colors mb-8 text-sm font-medium">
                    <ArrowLeft class="w-4 h-4" />
                    Kembali ke Fasilitas
                </Link>
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-4 tracking-tight font-display">
                        {{ facility.title }}
                    </h1>
                    <p v-if="facility.description" class="text-lg text-white/70 max-w-3xl mx-auto leading-relaxed">
                        {{ facility.description }}
                    </p>
                </div>
            </div>
        </div>

        <div class="py-16 bg-slate-50 min-h-screen">
            <div v-if="hasGallery" class="mx-4 sm:mx-6 lg:mx-8">
                <div class="overflow-hidden rounded-[2rem] border border-slate-100 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-4 py-4 sm:px-6 sm:py-5 md:px-8 md:py-6">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-500 text-white">
                                <Images class="h-4.5 w-4.5" />
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-lg font-black text-slate-900 sm:text-xl md:text-2xl">Galeri Foto</h2>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-4 sm:px-6 sm:py-6 md:px-8 md:py-8">
                        <div v-if="shouldAutoScroll" class="hidden md:block gallery-mask">
                            <div class="gallery-track flex w-max gap-4 md:gap-5" :style="carouselStyle">
                                <article
                                    v-for="(img, idx) in carouselItems"
                                    :key="img.trackKey + '-' + idx"
                                    @click="openLightbox(img.originalIndex ?? idx)"
                                    class="group w-[78vw] max-w-[28rem] shrink-0 cursor-pointer overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white sm:w-[24rem] md:w-[30rem] lg:w-[34rem]"
                                >
                                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                                        <img :src="img.url" :alt="img.caption || 'Foto ' + (idx + 1)" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.05]" />
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-slate-950/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                                        <div class="absolute right-3 top-3 grid h-10 w-10 place-items-center rounded-full border border-white/25 bg-white/20 text-white opacity-0 scale-90 backdrop-blur transition-all duration-300 group-hover:scale-100 group-hover:opacity-100">
                                            <Maximize2 class="h-5 w-5" />
                                        </div>
                                    </div>
                                    <div class="min-h-[4.5rem] border-t border-slate-100 px-4 py-3.5">
                                        <p class="line-clamp-2 text-sm font-medium leading-relaxed text-slate-700">
                                            {{ img.caption || `Foto ${((idx % galleryImages.length) || 0) + 1}` }}
                                        </p>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <div
                            :class="shouldAutoScroll
                                ? 'flex gap-4 overflow-x-auto px-1 pb-2 md:hidden snap-x snap-mandatory touch-pan-x [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden'
                                : 'grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 md:gap-5'"
                        >
                            <article
                                v-for="(img, idx) in galleryImages"
                                :key="'static-' + idx"
                                @click="openLightbox(idx)"
                                :class="shouldAutoScroll
                                    ? 'group w-[78vw] max-w-[28rem] shrink-0 snap-start cursor-pointer overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white sm:w-[24rem] md:w-[28rem]'
                                    : 'group cursor-pointer overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white'"
                            >
                                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                                    <img :src="img.url" :alt="img.caption || 'Foto ' + (idx + 1)" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.05]" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-slate-950/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                                    <div class="absolute right-3 top-3 grid h-10 w-10 place-items-center rounded-full border border-white/25 bg-white/20 text-white opacity-0 scale-90 backdrop-blur transition-all duration-300 group-hover:scale-100 group-hover:opacity-100">
                                        <Maximize2 class="h-5 w-5" />
                                    </div>
                                </div>
                                <div class="min-h-[4.5rem] border-t border-slate-100 px-4 py-3.5">
                                    <p class="text-sm font-medium leading-relaxed text-slate-700">
                                        {{ img.caption || `Foto ${idx + 1}` }}
                                    </p>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!hasGallery" class="text-center py-20">
                <p class="text-slate-400">Belum ada galeri foto untuk fasilitas ini.</p>
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
                    v-if="activeLightboxPhoto"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-xl p-4 sm:p-6"
                    @click.self="closeLightbox"
                >
                    <button
                        type="button"
                        @click="closeLightbox"
                        aria-label="Tutup preview"
                        class="absolute top-5 right-5 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-white/20 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                    >
                        <X class="w-6 h-6" />
                    </button>

                    <button
                        v-if="galleryImages.length > 1"
                        type="button"
                        @click="prevLightboxPhoto"
                        aria-label="Foto sebelumnya"
                        class="absolute left-4 sm:left-6 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-brand-500 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                    >
                        <ChevronLeft class="w-6 h-6" />
                    </button>

                    <button
                        v-if="galleryImages.length > 1"
                        type="button"
                        @click="nextLightboxPhoto"
                        aria-label="Foto selanjutnya"
                        class="absolute right-4 sm:right-6 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-brand-500 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                    >
                        <ChevronRight class="w-6 h-6" />
                    </button>

                    <div class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center" @click.stop>
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/15 max-h-[75vh] bg-black/40 flex items-center justify-center">
                            <img
                                :src="activeLightboxPhoto.url"
                                :alt="activeLightboxPhoto.caption || facility.title"
                                class="max-h-[75vh] w-auto max-w-full object-contain rounded-3xl"
                            />
                        </div>

                        <div class="mt-4 w-full max-w-2xl px-4 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center text-white flex items-center justify-between gap-4">
                            <div class="text-left min-w-0 flex-1">
                                <span class="text-[11px] font-extrabold uppercase tracking-widest text-brand-300">
                                    {{ facility.title }}
                                </span>
                                <h4 class="text-sm sm:text-base font-bold truncate">{{ activeLightboxPhoto.caption || 'Foto Dokumentasi Fasilitas' }}</h4>
                            </div>
                            <div class="text-xs font-semibold text-slate-300 shrink-0 px-3 py-1 rounded-full bg-white/10">
                                {{ (selectedLightboxIndex || 0) + 1 }} / {{ galleryImages.length }}
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </MainLayout>
</template>

<style scoped>
.gallery-mask {
    overflow: hidden;
    mask-image: linear-gradient(to right, transparent 0, black 6%, black 94%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, transparent 0, black 6%, black 94%, transparent 100%);
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