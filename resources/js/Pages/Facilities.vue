<script setup>
import { computed, ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Sparkles, ArrowRight, MonitorPlay, Wrench, ChefHat, BookOpen, Dribbble, BookHeart } from 'lucide-vue-next';

const props = defineProps({
    facilities: Array,
    categories: Array,
});

const iconMap = {
    'monitor-play': MonitorPlay,
    wrench: Wrench,
    'chef-hat': ChefHat,
    'book-open': BookOpen,
    dribbble: Dribbble,
    'book-heart': BookHeart,
    sparkles: Sparkles,
};

const activeCategory = ref('Semua');

const filteredFacilities = computed(() => {
    const items = props.facilities || [];
    if (!props.categories || props.categories.length === 0) return items;
    if (activeCategory.value === 'Semua') return items;
    return items.filter((f) => (f.category || 'Umum') === activeCategory.value);
});
</script>

<template>
    <Head title="Fasilitas Sekolah" />

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
                    <Sparkles class="h-4 w-4 text-brand-400" />
                    Sarana & Prasarana
                </div>

                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black tracking-tight text-white mb-6">
                    Fasilitas <span class="bg-gradient-to-r from-brand-400 to-indigo-400 bg-clip-text text-transparent">Sekolah</span>
                </h1>

                <p class="text-lg md:text-xl text-white/70 max-w-3xl mx-auto leading-relaxed">
                    Kami menyediakan fasilitas terbaik dan modern untuk menunjang kegiatan belajar mengajar serta pengembangan potensi diri siswa secara maksimal di era industri 4.0.
                </p>
            </div>
        </div>

        <!-- Facilities Grid -->
        <div class="py-24 bg-slate-50 min-h-screen relative -mt-10 rounded-t-[3rem] shadow-[0_-20px_40px_rgba(0,0,0,0.1)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

                <div v-if="filteredFacilities && filteredFacilities.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <Link v-for="(facility, index) in filteredFacilities" :key="facility.id || index"
                          :href="route('facilities.show', facility.id)"
                          v-motion
                          :initial="{ opacity: 0, y: 40 }"
                          :visible="{ opacity: 1, y: 0, transition: { duration: 600, delay: index * 100 } }"
                          class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-lg hover:shadow-2xl transition-all duration-500 group flex flex-col hover:-translate-y-2">
                        
                        <div class="h-64 overflow-hidden relative">
                            <img :src="facility.image" :alt="facility.title" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-500"></div>
                            
                            <div class="absolute bottom-6 left-6">
                                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white group-hover:bg-brand-500 group-hover:border-brand-400 transition-colors duration-300 shadow-lg">
                                    <component :is="iconMap[facility.icon] || Sparkles" class="w-7 h-7" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-8 flex-1 flex flex-col">
                            <h3 class="text-2xl font-bold text-slate-900 mb-4 group-hover:text-brand-600 transition-colors duration-300">{{ facility.title }}</h3>
                            <p class="text-slate-600 leading-relaxed mb-6 flex-1">{{ facility.description }}</p>
                            
                            <div class="pt-4 border-t border-slate-100 flex items-center text-brand-600 font-semibold text-sm group/btn w-max cursor-pointer">
                                Lihat Detail 
                                <ArrowRight class="w-4 h-4 ml-2 opacity-0 -translate-x-2 group-hover/btn:opacity-100 group-hover/btn:translate-x-0 transition-all duration-300" />
                            </div>
                        </div>
                    </Link>
                </div>
                <div v-else class="text-center py-24 bg-white rounded-[3rem] border border-slate-200 border-dashed shadow-sm" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { duration: 800 } }">
                    <div class="w-24 h-24 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <Sparkles class="w-12 h-12 text-slate-300" />
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Belum Ada Data</h3>
                    <p class="text-slate-500 text-lg max-w-md mx-auto">Data fasilitas untuk kategori ini belum tersedia. Silakan tambahkan melalui panel admin.</p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
