<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import {
    Newspaper,
    Calendar,
    ArrowRight,
    Search,
    X,
    Sparkles,
    Filter,
    ChevronRight,
    TrendingUp,
    Clock,
    Flame
} from 'lucide-vue-next';

const props = defineProps({
    news: Object,
    categories: {
        type: Array,
        default: () => [],
    },
    currentCategory: {
        type: String,
        default: 'Semua',
    },
    searchQuery: {
        type: String,
        default: '',
    },
    featured: {
        type: Object,
        default: null,
    },
});

const searchInput = ref(props.searchQuery || '');

const handleSearch = () => {
    router.get(
        route('news.index'),
        {
            category: props.currentCategory !== 'Semua' ? props.currentCategory : undefined,
            q: searchInput.value.trim() ? searchInput.value.trim() : undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const clearSearch = () => {
    searchInput.value = '';
    handleSearch();
};

const filterCategory = (cat) => {
    router.get(
        route('news.index'),
        {
            category: cat !== 'Semua' ? cat : undefined,
            q: searchInput.value.trim() ? searchInput.value.trim() : undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '';
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    }).format(date);
};
</script>

<template>
    <Head title="Berita & Informasi Terkini" />

    <MainLayout>
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-slate-950 pt-28 pb-32">
            <!-- Ambient Glow -->
            <div class="absolute inset-0 opacity-30 pointer-events-none">
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
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs sm:text-sm font-black uppercase tracking-widest text-white backdrop-blur-xl mb-6 mx-auto shadow-2xl">
                    <Newspaper class="h-4 w-4 text-cyan-400" />
                    <span>Kabar & Informasi Terkini</span>
                </div>

                <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight text-white mb-6 leading-tight">
                    Portal Berita & <span class="bg-gradient-to-r from-cyan-400 via-brand-400 to-indigo-300 bg-clip-text text-transparent">Artikel</span>
                </h1>

                <p class="text-base sm:text-lg md:text-xl text-slate-300 max-w-3xl mx-auto font-normal leading-relaxed mb-10">
                    Ikuti perkembangan terbaru, liputan prestasi siswa, inovasi kejuruan, dan kabar resmi civitas akademika SMKS Muhammadiyah 2 Genteng.
                </p>

                <!-- Search Box in Hero -->
                <div class="max-w-2xl mx-auto">
                    <form @submit.prevent="handleSearch" class="relative">
                        <Search class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                        <input
                            v-model="searchInput"
                            type="text"
                            placeholder="Cari judul berita, liputan prestasi, atau topik..."
                            class="w-full pl-14 pr-28 py-4 rounded-3xl bg-white/10 border border-white/20 text-white placeholder:text-slate-400 backdrop-blur-xl text-sm sm:text-base font-medium focus:outline-none focus:ring-2 focus:ring-brand-400 focus:bg-white/20 transition-all shadow-2xl"
                        />
                        <button
                            v-if="searchInput"
                            type="button"
                            @click="clearSearch"
                            class="absolute right-24 top-1/2 -translate-y-1/2 h-7 w-7 rounded-full bg-white/20 hover:bg-white/30 text-white grid place-items-center text-xs transition-colors"
                        >
                            <X class="w-4 h-4" />
                        </button>
                        <button
                            type="submit"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 px-5 py-2.5 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs sm:text-sm transition-all shadow-lg hover:scale-105 active:scale-95"
                        >
                            Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="py-16 md:py-24 bg-slate-50 min-h-screen relative -mt-10 rounded-t-[3rem] shadow-[0_-20px_40px_rgba(0,0,0,0.08)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

                <!-- Category Filters Navigation -->
                <div v-if="categories && categories.length > 0" class="flex flex-wrap items-center justify-center gap-2.5" v-motion-slide-visible-bottom>
                    <button
                        @click="filterCategory('Semua')"
                        :class="[
                            'px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-300 flex items-center gap-2 shadow-sm',
                            currentCategory === 'Semua'
                                ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30 ring-2 ring-brand-600 ring-offset-2'
                                : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200'
                        ]"
                    >
                        <span>Semua Berita</span>
                    </button>
                    <button
                        v-for="cat in categories"
                        :key="cat"
                        @click="filterCategory(cat)"
                        :class="[
                            'px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-300 flex items-center gap-2 shadow-sm',
                            currentCategory === cat
                                ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30 ring-2 ring-brand-600 ring-offset-2'
                                : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200'
                        ]"
                    >
                        <span>{{ cat }}</span>
                    </button>
                </div>

                <!-- ================= FEATURED HEADLINE BANNER (IF AVAILABLE) ================= -->
                <section v-if="featured && currentCategory === 'Semua' && !searchQuery" v-motion-slide-visible-bottom>
                    <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 md:p-10 border border-slate-200/80 shadow-lg hover:shadow-2xl transition-all duration-500 group">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <!-- Image Left -->
                            <div class="lg:col-span-7 relative rounded-3xl overflow-hidden bg-slate-100 aspect-[16/10] sm:aspect-[16/9]">
                                <img
                                    :src="featured.image || '/storage/images/logo2.png'"
                                    :alt="featured.title"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                />
                                <div class="absolute top-4 left-4 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-red-600 text-white shadow-lg shadow-red-600/40">
                                        <Flame class="w-3.5 h-3.5" />
                                        Berita Utama
                                    </span>
                                </div>
                            </div>

                            <!-- Content Right -->
                            <div class="lg:col-span-5 flex flex-col justify-between space-y-4">
                                <div>
                                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-3">
                                        <span v-if="featured.category" class="px-3 py-1 rounded-full bg-brand-50 text-brand-700 font-bold text-xs">
                                            {{ featured.category }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <Calendar class="w-3.5 h-3.5 text-brand-500" />
                                            {{ formatDate(featured.published_at) }}
                                        </span>
                                    </div>

                                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-brand-600 transition-colors leading-snug">
                                        <Link :href="route('news.show', featured.slug)">{{ featured.title }}</Link>
                                    </h2>

                                    <p class="text-slate-600 text-sm sm:text-base line-clamp-3 leading-relaxed mt-3">
                                        {{ featured.excerpt || 'Klik tombol di bawah untuk membaca liputan lengkap artikel berita terkini dari SMKS Muhammadiyah 2 Genteng.' }}
                                    </p>
                                </div>

                                <div class="pt-2">
                                    <Link
                                        :href="route('news.show', featured.slug)"
                                        class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-lg shadow-brand-600/25 transition-all hover:scale-105 active:scale-95"
                                    >
                                        <span>Baca Berita Utama</span>
                                        <ArrowRight class="w-4 h-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= NEWS GRID ================= -->
                <section>
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                                {{ currentCategory === 'Semua' ? 'Daftar Berita Terkini' : 'Kategori: ' + currentCategory }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Menampilkan artikel dan informasi resmi sekolah.
                            </p>
                        </div>
                    </div>

                    <!-- Cards Grid -->
                    <div
                        v-if="news && news.data && news.data.length > 0"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="(item, index) in news.data"
                            :key="item.id"
                            v-motion
                            :initial="{ opacity: 0, y: 24 }"
                            :visible="{ opacity: 1, y: 0, transition: { duration: 500, delay: (index % 3) * 80 } }"
                            class="group flex flex-col overflow-hidden rounded-[2rem] bg-white border border-slate-200/80 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 justify-between"
                        >
                            <div>
                                <!-- Image Container -->
                                <div class="relative h-56 w-full overflow-hidden bg-slate-100">
                                    <img
                                        :src="item.image || '/storage/images/logo2.png'"
                                        :alt="item.title"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                        loading="lazy"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <span
                                        v-if="item.category"
                                        class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-black tracking-wider uppercase bg-white/90 text-brand-600 backdrop-blur-md shadow-sm"
                                    >
                                        {{ item.category }}
                                    </span>
                                </div>

                                <!-- Body Content -->
                                <div class="p-6 sm:p-7">
                                    <div v-if="item.published_at" class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 mb-3">
                                        <Calendar class="w-3.5 h-3.5 text-brand-500" />
                                        <span>{{ formatDate(item.published_at) }}</span>
                                    </div>

                                    <h3 class="text-lg sm:text-xl font-black text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-2 leading-snug mb-3">
                                        <Link :href="route('news.show', item.slug)">{{ item.title }}</Link>
                                    </h3>

                                    <p class="text-slate-500 text-xs sm:text-sm line-clamp-3 leading-relaxed">
                                        {{ item.excerpt || 'Klik untuk membaca liputan dan informasi lengkap mengenai artikel berita ini.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Footer Card Action -->
                            <div class="p-6 pt-0 border-t border-slate-100 mt-4 flex items-center justify-between text-xs sm:text-sm font-bold text-brand-600">
                                <Link
                                    :href="route('news.show', item.slug)"
                                    class="inline-flex items-center gap-2 hover:text-brand-700 transition-colors"
                                >
                                    <span>Baca Selengkapnya</span>
                                    <ArrowRight class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else
                        class="text-center py-24 bg-white rounded-[3rem] border border-slate-200 border-dashed shadow-sm"
                        v-motion
                        :initial="{ opacity: 0 }"
                        :enter="{ opacity: 1, transition: { duration: 600 } }"
                    >
                        <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <Search class="w-10 h-10" />
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Berita Tidak Ditemukan</h3>
                        <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">
                            Tidak ada artikel yang cocok dengan kata kunci "{{ searchInput }}" atau filter kategori saat ini.
                        </p>
                        <button
                            type="button"
                            @click="clearSearch(); filterCategory('Semua')"
                            class="px-6 py-2.5 rounded-2xl bg-brand-50 text-brand-700 font-bold text-sm hover:bg-brand-100 transition-colors"
                        >
                            Reset Filter & Pencarian
                        </button>
                    </div>

                    <!-- Modern Pagination Controls -->
                    <div v-if="news && news.links && news.links.length > 3" class="mt-16 flex flex-wrap justify-center items-center gap-2">
                        <template v-for="(link, i) in news.links" :key="i">
                            <span
                                v-if="!link.url"
                                class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-300 border border-slate-100 bg-white cursor-not-allowed select-none"
                                v-html="link.label"
                            />
                            <Link
                                v-else
                                :href="link.url"
                                :class="[
                                    'px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 border',
                                    link.active
                                        ? 'bg-brand-600 text-white border-brand-600 shadow-md shadow-brand-600/25 ring-2 ring-brand-600 ring-offset-1'
                                        : 'bg-white text-slate-700 border-slate-200 hover:bg-brand-50 hover:text-brand-600 hover:border-brand-300'
                                ]"
                                preserve-scroll
                                preserve-state
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </section>

            </div>
        </div>
    </MainLayout>
</template>
