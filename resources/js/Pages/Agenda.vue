<script setup>
import { computed } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    agendas: {
        type: Array,
        default: () => [],
    },
});

const formatter = new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});

const formatDateRange = (startDate, endDate) => {
    if (!startDate) return 'Tanggal belum ditentukan';

    const start = new Date(startDate);
    if (Number.isNaN(start.getTime())) return 'Tanggal belum valid';

    if (!endDate || startDate === endDate) {
        return formatter.format(start);
    }

    const end = new Date(endDate);
    if (Number.isNaN(end.getTime())) {
        return formatter.format(start);
    }

    return `${formatter.format(start)} - ${formatter.format(end)}`;
};

const agendas = computed(() =>
    props.agendas.map((agenda) => ({
        ...agenda,
        formattedDate: formatDateRange(agenda.start_date, agenda.end_date),
        categoryLabel: agenda.category || 'Agenda Sekolah',
        descriptionLabel: agenda.description || 'Informasi agenda akan segera diperbarui.',
        iconLabel: agenda.icon || '📅',
    })),
);
</script>

<template>
    <Head title="Agenda Sekolah" />

    <MainLayout>
        <div class="py-12 bg-slate-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-16">
                    <h2 class="text-brand-500 font-bold tracking-wider uppercase text-sm mb-3">Agenda</h2>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6">Agenda Kegiatan Sekolah</h1>
                    <div class="w-24 h-1.5 bg-brand-500 mx-auto rounded-full"></div>
                </div>

                <div v-if="agendas.length" class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div v-for="agenda in agendas" :key="agenda.id" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-2xl text-brand-600 transition-colors group-hover:bg-brand-500 group-hover:text-white">
                                    <span aria-hidden="true">{{ agenda.iconLabel }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-500 transition-colors group-hover:text-brand-600">{{ agenda.formattedDate }}</p>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ agenda.categoryLabel }}</p>
                                </div>
                            </div>

                            <h3 class="text-xl font-bold text-slate-900 mb-4 group-hover:text-brand-600 transition-colors">{{ agenda.title }}</h3>
                            <p class="mb-8 line-clamp-3 leading-relaxed text-slate-600">{{ agenda.descriptionLabel }}</p>

                            <div class="space-y-3 border-t border-slate-50 pt-6">
                                <div class="flex items-center gap-3 text-sm text-slate-500">
                                    <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Jadwal diambil langsung dari data admin
                                </div>
                                <div class="flex items-center gap-3 text-sm text-slate-500">
                                    <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Sinkron otomatis setelah update dari admin
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="rounded-3xl border border-dashed border-slate-200 bg-white px-6 py-14 text-center shadow-sm">
                    <p class="text-lg font-semibold text-slate-700">Belum ada agenda aktif</p>
                    <p class="mt-2 text-sm text-slate-500">Data agenda akan tampil di sini setelah ditambahkan dari panel admin.</p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
