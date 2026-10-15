<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import {
    Calendar,
    Clock,
    User,
    Share2,
    ChevronRight,
    ArrowLeft,
    ArrowRight,
    Check,
    Copy,
    MessageCircle,
    Maximize2,
    X,
    Sparkles,
    BookOpen,
    Newspaper,
    ExternalLink,
    Tag,
    Eye
} from 'lucide-vue-next';

const props = defineProps({
    article: {
        type: Object,
        required: true,
    },
    related: {
        type: Array,
        default: () => [],
    },
});

const isImageModalOpen = ref(false);
const copied = ref(false);
const scrollProgress = ref(0);

// Format date
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '';
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(date);
};

// Calculate read time
const readTime = computed(() => {
    if (!props.article?.content) return '2 Menit';
    const text = props.article.content.replace(/<[^>]*>/g, '');
    const wordCount = text.trim().split(/\s+/).length;
    const minutes = Math.ceil(wordCount / 200);
    return `${Math.max(1, minutes)} Menit Baca`;
});

// Current URL
const currentUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.href;
    }
    return '';
});

// Share Handlers
const shareToWhatsApp = () => {
    const text = encodeURIComponent(`${props.article.title}\n\nBaca selengkapnya di: ${currentUrl.value}`);
    window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
};

const shareToFacebook = () => {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl.value)}`, '_blank');
};

const shareToTwitter = () => {
    const text = encodeURIComponent(props.article.title);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${encodeURIComponent(currentUrl.value)}`, '_blank');
};

const copyLink = async () => {
    if (typeof navigator !== 'undefined' && navigator.clipboard) {
        try {
            await navigator.clipboard.writeText(currentUrl.value);
            copied.value = true;
            setTimeout(() => {
                copied.value = false;
            }, 2500);
        } catch (err) {
            console.error('Failed to copy: ', err);
        }
    }
};

// Scroll Progress Tracker
const updateScrollProgress = () => {
    if (typeof window !== 'undefined') {
        const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (totalHeight > 0) {
            scrollProgress.value = (window.scrollY / totalHeight) * 100;
        }
    }
};

// Modal helpers
const openImageModal = () => {
    isImageModalOpen.value = true;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = 'hidden';
    }
};

const closeImageModal = () => {
    isImageModalOpen.value = false;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
};

const handleKeyDown = (e) => {
    if (e.key === 'Escape' && isImageModalOpen.value) {
        closeImageModal();
    }
};

onMounted(() => {
    window.addEventListener('scroll', updateScrollProgress, { passive: true });
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener('scroll', updateScrollProgress);
    window.removeEventListener('keydown', handleKeyDown);
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
});
</script>

<template>
    <Head :title="article.title + ' - SMK Muhammadiyah 2 Genteng'">
        <meta name="description" :content="article.excerpt || article.title" />
    </Head>

    <MainLayout>
        <!-- Top Reading Progress Indicator -->
        <div class="fixed top-0 left-0 right-0 h-1.5 z-50 bg-slate-200/40">
            <div
                class="h-full bg-gradient-to-r from-brand-500 via-cyan-500 to-indigo-600 transition-all duration-150"
                :style="{ width: `${scrollProgress}%` }"
            ></div>
        </div>

        <div class="bg-slate-50/70 min-h-screen pt-28 pb-20">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6 flex-wrap">
                    <Link :href="route('home')" class="hover:text-brand-600 transition-colors">Beranda</Link>
                    <ChevronRight class="w-3.5 h-3.5 text-slate-400" />
                    <Link :href="route('news.index')" class="hover:text-brand-600 transition-colors">Berita & Informasi</Link>
                    <ChevronRight class="w-3.5 h-3.5 text-slate-400" />
                    <span class="text-brand-600 font-bold truncate max-w-[200px] sm:max-w-xs">{{ article.category || 'Berita' }}</span>
                </nav>

                <!-- Article Main Card Container -->
                <main class="bg-white rounded-[2.5rem] p-6 sm:p-10 md:p-14 border border-slate-200/80 shadow-sm">

                    <!-- Header Category & Meta -->
                    <div class="mb-6">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider bg-brand-50 text-brand-700 border border-brand-200">
                                <Sparkles class="w-3.5 h-3.5 text-brand-600" />
                                {{ article.category || 'Berita Terkini' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                                <Clock class="w-3.5 h-3.5" />
                                {{ readTime }}
                            </span>
                        </div>

                        <!-- Article Title -->
                        <h1 class="text-2xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight sm:leading-tight mb-6">
                            {{ article.title }}
                        </h1>

                        <!-- Author & Date Profile Bar -->
                        <div class="flex flex-wrap items-center justify-between gap-4 py-4 border-y border-slate-100 text-xs sm:text-sm">
                            <div class="flex items-center gap-3">
                                <div class="h-11 w-11 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white grid place-items-center font-black text-sm shadow-md shadow-brand-500/20">
                                    SMK
                                </div>
                                <div>
                                    <div class="font-extrabold text-slate-900">Humas SMK Muda Genteng</div>
                                    <div class="text-xs text-slate-400 flex items-center gap-1.5 mt-0.5">
                                        <Calendar class="w-3.5 h-3.5 text-slate-400" />
                                        <span>{{ formatDate(article.published_at) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Share Buttons on Top -->
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="shareToWhatsApp"
                                    title="Bagikan ke WhatsApp"
                                    class="h-9 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-500 text-emerald-700 hover:text-white font-bold text-xs flex items-center gap-1.5 transition-colors"
                                >
                                    <MessageCircle class="w-4 h-4" />
                                    <span class="hidden sm:inline">WhatsApp</span>
                                </button>
                                <button
                                    type="button"
                                    @click="copyLink"
                                    title="Salin Link"
                                    class="h-9 px-3 rounded-xl bg-slate-100 hover:bg-brand-500 text-slate-700 hover:text-white font-bold text-xs flex items-center gap-1.5 transition-colors"
                                >
                                    <Check v-if="copied" class="w-4 h-4 text-emerald-500" />
                                    <Copy v-else class="w-4 h-4" />
                                    <span class="hidden sm:inline">{{ copied ? 'Tersalin' : 'Salin' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image Showcase -->
                    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-100 bg-slate-100 mb-10 group cursor-pointer" @click="openImageModal">
                        <img
                            :src="article.image || '/storage/images/logo2.png'"
                            :alt="article.title"
                            class="w-full max-h-[520px] object-cover transition-transform duration-700 group-hover:scale-105"
                            loading="eager"
                            fetchpriority="high"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-between p-6">
                            <span class="text-white font-bold text-xs sm:text-sm">Klik untuk Perbesar Gambar</span>
                            <div class="h-9 w-9 rounded-xl bg-brand-500 text-white grid place-items-center shadow-lg">
                                <Maximize2 class="w-4 h-4" />
                            </div>
                        </div>
                    </div>

                    <!-- Lead Excerpt (if present) -->
                    <div v-if="article.excerpt" class="mb-8 p-6 rounded-2xl bg-brand-50/50 border-l-4 border-brand-500 text-slate-700 text-base sm:text-lg font-medium leading-relaxed">
                        {{ article.excerpt }}
                    </div>

                    <!-- Article Body Content -->
                    <div
                        class="prose prose-lg prose-slate max-w-none prose-headings:font-black prose-headings:tracking-tight prose-headings:text-slate-900 prose-p:text-slate-700 prose-p:leading-relaxed prose-p:text-base sm:prose-p:text-lg prose-a:text-brand-600 hover:prose-a:text-brand-700 prose-img:rounded-3xl prose-img:shadow-md prose-strong:text-slate-900 prose-li:text-slate-700"
                        v-html="article.content || '<p class=\'text-slate-500 italic\'>Konten artikel sedang dalam proses pembaruan.</p>'"
                    ></div>

                    <!-- Bottom Social Sharing Bar -->
                    <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-brand-50 text-brand-600 grid place-items-center shrink-0">
                                <Share2 class="w-5 h-5" />
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-slate-900">Bagikan Berita Ini</h4>
                                <p class="text-xs text-slate-400">Sebarkan informasi prestasi & kegiatan positif ke teman Anda</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 flex-wrap justify-center">
                            <button
                                type="button"
                                @click="shareToWhatsApp"
                                class="px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-500 text-emerald-700 hover:text-white font-bold text-xs flex items-center gap-2 transition-all shadow-sm hover:scale-105 active:scale-95"
                            >
                                <MessageCircle class="w-4 h-4" />
                                <span>WhatsApp</span>
                            </button>
                            <button
                                type="button"
                                @click="shareToFacebook"
                                class="px-4 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white font-bold text-xs flex items-center gap-2 transition-all shadow-sm hover:scale-105 active:scale-95"
                            >
                                <ExternalLink class="w-4 h-4" />
                                <span>Facebook</span>
                            </button>
                            <button
                                type="button"
                                @click="shareToTwitter"
                                class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-900 text-slate-700 hover:text-white font-bold text-xs flex items-center gap-2 transition-all shadow-sm hover:scale-105 active:scale-95"
                            >
                                <span>Twitter / X</span>
                            </button>
                            <button
                                type="button"
                                @click="copyLink"
                                class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-brand-500 text-slate-700 hover:text-white font-bold text-xs flex items-center gap-2 transition-all shadow-sm hover:scale-105 active:scale-95"
                            >
                                <Check v-if="copied" class="w-4 h-4 text-emerald-500" />
                                <Copy v-else class="w-4 h-4" />
                                <span>{{ copied ? 'Tautan Disalin!' : 'Salin Link' }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Navigation Footer -->
                    <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <Link
                            :href="route('news.index')"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors"
                        >
                            <ArrowLeft class="w-4 h-4" />
                            <span>Kembali ke Semua Berita</span>
                        </Link>
                    </div>
                </main>

                <!-- ================= RELATED ARTICLES SECTION ================= -->
                <section v-if="related && related.length > 0" class="mt-16">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-600 block mb-1">Rekomendasi</span>
                            <h3 class="text-2xl sm:text-3xl font-black text-slate-900">Artikel Terkait Lainnya</h3>
                        </div>
                        <Link :href="route('news.index')" class="text-xs sm:text-sm font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                            <span>Lihat Semua</span>
                            <ChevronRight class="w-4 h-4" />
                        </Link>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="rel in related"
                            :key="rel.id"
                            class="group bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between"
                        >
                            <div>
                                <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                                    <img
                                        :src="rel.image || '/storage/images/logo2.png'"
                                        :alt="rel.title"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    />
                                    <span
                                        v-if="rel.category"
                                        class="absolute top-3 left-3 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/90 text-brand-600 backdrop-blur-md shadow-sm"
                                    >
                                        {{ rel.category }}
                                    </span>
                                </div>

                                <div class="p-6">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400 mb-2 font-semibold">
                                        <Calendar class="w-3.5 h-3.5 text-brand-500" />
                                        <span>{{ formatDate(rel.published_at) }}</span>
                                    </div>

                                    <h4 class="font-extrabold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-2 leading-snug text-base">
                                        <Link :href="route('news.show', rel.slug)">{{ rel.title }}</Link>
                                    </h4>
                                </div>
                            </div>

                            <div class="p-6 pt-0">
                                <Link
                                    :href="route('news.show', rel.slug)"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 hover:text-brand-700"
                                >
                                    <span>Baca Berita</span>
                                    <ArrowRight class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>

        <!-- Fullscreen Image Modal Lightbox -->
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
                    v-if="isImageModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-xl p-4 sm:p-6"
                    @click.self="closeImageModal"
                >
                    <button
                        type="button"
                        @click="closeImageModal"
                        aria-label="Tutup gambar"
                        class="absolute top-5 right-5 z-20 h-12 w-12 rounded-full bg-white/10 hover:bg-white/20 text-white grid place-items-center backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                    >
                        <X class="w-6 h-6" />
                    </button>

                    <div class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center" @click.stop>
                        <img
                            :src="article.image || '/storage/images/logo2.png'"
                            :alt="article.title"
                            class="max-h-[80vh] w-auto max-w-full object-contain rounded-3xl shadow-2xl border border-white/15"
                        />
                        <p class="text-white text-xs sm:text-sm font-semibold mt-4 text-center max-w-2xl px-4 line-clamp-2">
                            {{ article.title }}
                        </p>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </MainLayout>
</template>
