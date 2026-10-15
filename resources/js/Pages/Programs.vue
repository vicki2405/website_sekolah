<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { ArrowRight } from 'lucide-vue-next';

defineProps({
    programs: Array,
});
</script>

<template>
    <Head title="Keahlian" />
    <MainLayout>
        <div class="py-24 bg-slate-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900">Keahlian</h1>
                    <p class="mt-3 text-slate-600">Pilih program keahlian untuk melihat detail.</p>
                </div>

                <div v-if="programs && programs.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="program in programs" :key="program.id" class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 flex flex-col">
                        <div class="w-28 h-28 rounded-2xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center p-5">
                            <img v-if="program.image" :src="program.image" :alt="program.name" class="w-full h-full object-contain" />
                            <div v-else class="text-brand-500 font-black text-3xl">
                                {{ program.name?.slice(0, 2) || 'K' }}
                            </div>
                        </div>

                        <h3 class="mt-6 text-2xl font-extrabold text-slate-900">{{ program.name }}</h3>
                        <p class="mt-3 text-slate-600 line-clamp-4 flex-1 whitespace-pre-line">{{ program.description }}</p>

                        <Link :href="route('keahlian.show', { slug: program.slug })" class="mt-6 inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-slate-50 text-slate-700 font-semibold text-sm hover:bg-brand-500 hover:text-white transition-all">
                            Lihat Detail
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>

                <div v-else class="bg-white rounded-3xl p-10 border border-slate-100 text-center">
                    <p class="text-slate-600">Belum ada data keahlian yang aktif.</p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
