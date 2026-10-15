<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import {
    Mail,
    Phone,
    GraduationCap,
    Users,
    Filter,
    Search,
    X,
    Sparkles,
    BookOpen,
    Briefcase,
    Building,
    CheckCircle2,
    ArrowUpRight,
    ExternalLink,
    ChevronRight,
    Award
} from 'lucide-vue-next';

const props = defineProps({
    teachers: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    orgFlat: { type: Array, default: () => [] },
    orgImage: { type: String, default: null },
});

const searchQuery = ref('');
const activeCategory = ref('Semua');
const selectedTeacher = ref(null);

// Filtering logic
const filteredTeachers = computed(() => {
    let list = props.teachers || [];

    if (activeCategory.value !== 'Semua') {
        list = list.filter(t => (t.category || 'Umum') === activeCategory.value);
    }

    if (searchQuery.value.trim() !== '') {
        const q = searchQuery.value.toLowerCase().trim();
        list = list.filter(t =>
            (t.name && t.name.toLowerCase().includes(q)) ||
            (t.subject && t.subject.toLowerCase().includes(q)) ||
            (t.position && t.position.toLowerCase().includes(q)) ||
            (t.bio && t.bio.toLowerCase().includes(q))
        );
    }

    return list;
});

// Category counts
const getCategoryCount = (categoryName) => {
    if (!props.teachers) return 0;
    if (categoryName === 'Semua') return props.teachers.length;
    return props.teachers.filter(t => (t.category || 'Umum') === categoryName).length;
};

// Modal helpers
const openTeacherModal = (teacher) => {
    selectedTeacher.value = teacher;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = 'hidden';
    }
};

const closeTeacherModal = () => {
    selectedTeacher.value = null;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
};

const getInitials = (name) => {
    if (!name) return 'SMK';
    const parts = name.replace(/[^a-zA-Z\s]/g, '').trim().split(/\s+/);
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.slice(0, 2).toUpperCase();
};

const handleKeyDown = (e) => {
    if (e.key === 'Escape' && selectedTeacher.value) {
        closeTeacherModal();
    }
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
    <Head title="Guru & Tenaga Kependidikan" />

    <MainLayout>
        <!-- Hero Section -->
        <div class="relative bg-slate-950 pt-28 pb-32 overflow-hidden">
            <!-- Ambient Glow -->
            <div class="absolute inset-0 pointer-events-none opacity-30">
                <div class="absolute -top-48 -right-40 h-[38rem] w-[38rem] rounded-full blur-3xl bg-gradient-to-br from-brand-500 to-indigo-500 animate-pulse"></div>
                <div class="absolute -bottom-64 -left-40 h-[40rem] w-[40rem] rounded-full blur-3xl bg-gradient-to-tr from-cyan-500 to-brand-500 animate-pulse"></div>
                <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:24px_24px]"></div>
            </div>

            <div
                class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10"
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :enter="{ opacity: 1, y: 0, transition: { duration: 700 } }"
            >
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs sm:text-sm font-black uppercase tracking-widest text-white backdrop-blur-xl mb-6 mx-auto shadow-2xl">
                    <GraduationCap class="h-4 w-4 text-cyan-400" />
                    <span>Tenaga Pendidik & Kependidikan</span>
                </div>

                <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-white tracking-tight mb-6 leading-tight">
                    Guru & <span class="bg-gradient-to-r from-cyan-400 via-brand-400 to-indigo-300 bg-clip-text text-transparent">Staf Ahli</span>
                </h1>

                <p class="text-base sm:text-lg md:text-xl text-slate-300 max-w-3xl mx-auto font-normal leading-relaxed mb-10">
                    Membimbing dengan dedikasi, menginspirasi dengan karya. Mengenal para pendidik profesional yang siap mengantarkan siswa meraih kompetensi terbaik.
                </p>

                <!-- Quick Stats Ribbon in Hero -->
                <div class="inline-flex flex-wrap items-center justify-center gap-4 sm:gap-8 px-6 py-4 rounded-3xl bg-white/[0.07] border border-white/15 backdrop-blur-xl text-white shadow-2xl">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-2xl bg-brand-500/20 border border-brand-400/30 grid place-items-center text-cyan-300">
                            <Users class="w-5 h-5" />
                        </div>
                        <div class="text-left">
                            <div class="text-xl font-black">{{ teachers?.length || 0 }}</div>
                            <div class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Total Pendidik</div>
                        </div>
                    </div>

                    <div class="hidden sm:block h-8 w-px bg-white/15"></div>

                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-2xl bg-indigo-500/20 border border-indigo-400/30 grid place-items-center text-indigo-300">
                            <BookOpen class="w-5 h-5" />
                        </div>
                        <div class="text-left">
                            <div class="text-xl font-black">{{ categories?.length || 0 }}</div>
                            <div class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Bidang / Kategori</div>
                        </div>
                    </div>

                    <div class="hidden sm:block h-8 w-px bg-white/15"></div>

                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 grid place-items-center text-emerald-300">
                            <CheckCircle2 class="w-5 h-5" />
                        </div>
                        <div class="text-left">
                            <div class="text-xl font-black">100%</div>
                            <div class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Kompeten & Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="py-16 md:py-24 bg-slate-50 relative -mt-10 rounded-t-[3rem] shadow-[0_-20px_40px_rgba(0,0,0,0.08)] min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- ================= STRUKTUR ORGANISASI SEKOLAH ================= -->
                <section v-if="(orgFlat && orgFlat.length > 0) || orgImage" class="mb-20" v-motion-slide-visible-bottom>
                    <div class="text-center max-w-3xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest bg-brand-50 text-brand-700 border border-brand-200 mb-3">
                            <Building class="w-3.5 h-3.5" />
                            <span>Bagan Kepemimpinan</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                            Struktur Organisasi Sekolah
                        </h2>
                        <p class="text-slate-500 text-sm sm:text-base mt-2">
                            Tata kelola kepemimpinan dan penugasan pejabat struktural di lingkungan sekolah.
                        </p>
                    </div>

                    <!-- Bagan Struktur Image if available -->
                    <div v-if="orgImage" class="mb-12 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-base sm:text-lg font-black text-slate-900">Bagan Visual Struktur Organisasi</h3>
                                <p class="text-xs text-slate-500">Klik tombol untuk membuka bagan dalam resolusi penuh</p>
                            </div>
                            <a :href="orgImage" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-brand-50 text-brand-700 hover:bg-brand-100 transition-colors shrink-0">
                                <ExternalLink class="w-3.5 h-3.5" />
                                <span>Buka Penuh</span>
                            </a>
                        </div>
                        <div class="rounded-2xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center p-2 sm:p-4">
                            <img :src="orgImage" alt="Bagan Struktur Organisasi" class="max-h-[600px] w-auto max-w-full object-contain mx-auto rounded-xl hover:scale-[1.01] transition-transform duration-300" loading="lazy" />
                        </div>
                    </div>

                    <!-- Visual Hierarchy Grid -->
                    <div v-if="orgFlat && orgFlat.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="row in orgFlat"
                            :key="row.node.id"
                            class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between group"
                        >
                            <div>
                                <div class="flex items-center justify-between gap-3 mb-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700">
                                        <Briefcase class="w-3.5 h-3.5 text-brand-600" />
                                        Unit Jabatan
                                    </span>
                                    <span v-if="row.depth !== undefined" class="text-[11px] font-bold text-slate-400">
                                        Level 0{{ (row.depth || 0) + 1 }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-black text-slate-900 group-hover:text-brand-600 transition-colors leading-snug">
                                    {{ row.node.title }}
                                </h3>
                            </div>

                            <div class="mt-5 pt-4 border-t border-slate-100">
                                <div v-if="row.node.teachers && row.node.teachers.length > 0" class="space-y-2.5">
                                    <div
                                        v-for="t in row.node.teachers"
                                        :key="t.id"
                                        @click="openTeacherModal(t)"
                                        class="flex items-center gap-3 p-2.5 rounded-2xl bg-slate-50 hover:bg-brand-50 border border-slate-100 hover:border-brand-200 transition-all cursor-pointer group/teacher"
                                    >
                                        <div class="h-10 w-10 rounded-xl overflow-hidden bg-brand-100 shrink-0 flex items-center justify-center font-bold text-brand-700 text-xs">
                                            <img
                                                v-if="t.photo"
                                                :src="t.photo"
                                                :alt="t.name"
                                                class="h-full w-full object-cover"
                                                @error="(e) => { e.target.classList.add('hidden'); if (e.target.nextElementSibling) e.target.nextElementSibling.classList.remove('hidden'); }"
                                            />
                                            <span :class="{ 'hidden': t.photo }">{{ getInitials(t.name) }}</span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs sm:text-sm font-bold text-slate-900 truncate group-hover/teacher:text-brand-700">
                                                {{ t.name }}
                                            </div>
                                            <div class="text-[11px] text-slate-500 font-medium truncate">
                                                {{ (t.pivot && t.pivot.label) || t.position || 'Pejabat Struktural' }}
                                            </div>
                                        </div>
                                        <ArrowUpRight class="w-4 h-4 text-slate-400 group-hover/teacher:text-brand-600 transition-transform group-hover/teacher:translate-x-0.5 group-hover/teacher:-translate-y-0.5" />
                                    </div>
                                </div>
                                <div v-else class="text-xs text-slate-400 italic py-1">
                                    Belum ada pejabat yang ditugaskan.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= DIREKTORI GURU & STAFF ================= -->
                <section>
                    <div class="text-center max-w-3xl mx-auto mb-10" v-motion-slide-visible-bottom>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest bg-cyan-50 text-cyan-800 border border-cyan-200 mb-3">
                            <Users class="w-3.5 h-3.5" />
                            <span>Direktori Lengkap</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                            Daftar Guru & Staf Pengajar
                        </h2>
                        <p class="text-slate-500 text-sm sm:text-base mt-2">
                            Gunakan pencarian atau filter kategori untuk menemukan pengajar dan staf bidang terkait.
                        </p>
                    </div>

                    <!-- Search Bar & Category Filters Toolbar -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 mb-10" v-motion-slide-visible-bottom>
                        <div class="flex flex-col md:flex-row items-center gap-4 justify-between">
                            <!-- Real-time Search Input -->
                            <div class="relative w-full md:max-w-md">
                                <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Cari nama guru, mata pelajaran, posisi..."
                                    class="w-full pl-12 pr-10 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all placeholder:text-slate-400"
                                />
                                <button
                                    v-if="searchQuery"
                                    @click="searchQuery = ''"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 h-7 w-7 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-600 grid place-items-center text-xs transition-colors"
                                >
                                    <X class="w-4 h-4" />
                                </button>
                            </div>

                            <!-- Total Results Badge -->
                            <div class="text-xs sm:text-sm font-bold text-slate-500 shrink-0">
                                Menampilkan <span class="text-brand-600 font-extrabold">{{ filteredTeachers.length }}</span> dari {{ teachers?.length || 0 }} Guru
                            </div>
                        </div>

                        <!-- Category Filter Tabs -->
                        <div v-if="categories && categories.length > 0" class="mt-6 pt-6 border-t border-slate-100 flex flex-wrap gap-2.5">
                            <button
                                @click="activeCategory = 'Semua'"
                                :class="[
                                    'px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-300 flex items-center gap-2',
                                    activeCategory === 'Semua'
                                        ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30'
                                        : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
                                ]"
                            >
                                <span>Semua Kategori</span>
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-black" :class="activeCategory === 'Semua' ? 'bg-white/20 text-white' : 'bg-white text-slate-600'">
                                    {{ getCategoryCount('Semua') }}
                                </span>
                            </button>

                            <button
                                v-for="cat in categories"
                                :key="cat.id"
                                @click="activeCategory = cat.name"
                                :class="[
                                    'px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all duration-300 flex items-center gap-2',
                                    activeCategory === cat.name
                                        ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30'
                                        : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
                                ]"
                            >
                                <span>{{ cat.name }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-black" :class="activeCategory === cat.name ? 'bg-white/20 text-white' : 'bg-white text-slate-600'">
                                    {{ getCategoryCount(cat.name) }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Teacher Cards Grid -->
                    <div
                        v-if="filteredTeachers && filteredTeachers.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8"
                    >
                        <div
                            v-for="(teacher, index) in filteredTeachers"
                            :key="teacher.id || index"
                            @click="openTeacherModal(teacher)"
                            v-motion
                            :initial="{ opacity: 0, y: 24, scale: 0.96 }"
                            :visible="{ opacity: 1, y: 0, scale: 1, transition: { duration: 500, delay: (index % 4) * 60 } }"
                            class="group bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col cursor-pointer relative"
                        >
                            <!-- Photo Container -->
                            <div class="relative aspect-[3/4] overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                                <img
                                    v-if="teacher.photo"
                                    :src="teacher.photo"
                                    :alt="teacher.name"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                    loading="lazy"
                                    @error="(e) => { e.target.classList.add('hidden'); if (e.target.nextElementSibling) e.target.nextElementSibling.classList.remove('hidden'); }"
                                />
                                <div :class="{ 'hidden': teacher.photo }" class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-brand-50 to-indigo-100 text-brand-600 p-6 text-center">
                                    <div class="w-20 h-20 rounded-full bg-brand-200/60 flex items-center justify-center font-black text-2xl mb-2 text-brand-700 shadow-inner">
                                        {{ getInitials(teacher.name) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-500">Foto Profil</span>
                                </div>

                                <!-- Hover Quick Contact Floating Toolbar -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center p-6">
                                    <div class="flex items-center gap-2.5" @click.stop>
                                        <a
                                            v-if="teacher.email"
                                            :href="'mailto:' + teacher.email"
                                            aria-label="Kirim Email"
                                            class="h-11 w-11 rounded-2xl bg-white/20 hover:bg-brand-500 text-white backdrop-blur-md border border-white/25 grid place-items-center shadow-lg transition-all duration-300 hover:scale-110 active:scale-95"
                                        >
                                            <Mail class="w-5 h-5" />
                                        </a>
                                        <a
                                            v-if="teacher.phone"
                                            :href="'tel:' + teacher.phone"
                                            aria-label="Hubungi Telepon"
                                            class="h-11 w-11 rounded-2xl bg-white/20 hover:bg-brand-500 text-white backdrop-blur-md border border-white/25 grid place-items-center shadow-lg transition-all duration-300 hover:scale-110 active:scale-95"
                                        >
                                            <Phone class="w-5 h-5" />
                                        </a>
                                        <button
                                            type="button"
                                            @click="openTeacherModal(teacher)"
                                            aria-label="Lihat Profil Lengkap"
                                            class="px-4 h-11 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs backdrop-blur-md shadow-lg flex items-center gap-1.5 transition-all duration-300 hover:scale-105 active:scale-95"
                                        >
                                            <span>Detail</span>
                                            <ArrowUpRight class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Info Details -->
                            <div class="p-6 flex-1 flex flex-col justify-between bg-white">
                                <div>
                                    <!-- Role / Category Pill -->
                                    <div class="mb-2.5 flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[11px] font-extrabold uppercase tracking-wider bg-brand-50 text-brand-700 border border-brand-100 group-hover:bg-brand-500 group-hover:text-white transition-colors duration-300">
                                            <Sparkles class="w-3 h-3" />
                                            {{ teacher.position || teacher.category || 'Tenaga Pendidik' }}
                                        </span>
                                    </div>

                                    <!-- Teacher Name -->
                                    <h4 class="font-black text-base sm:text-lg text-slate-900 group-hover:text-brand-600 transition-colors leading-snug">
                                        {{ teacher.name }}
                                    </h4>

                                    <!-- Subject / Mapel -->
                                    <p v-if="teacher.subject && teacher.subject !== teacher.position" class="text-xs text-slate-500 font-medium mt-2 line-clamp-1 flex items-center gap-1.5">
                                        <BookOpen class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                                        <span>{{ teacher.subject }}</span>
                                    </p>
                                </div>

                                <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs text-brand-600 font-bold group-hover:text-brand-700">
                                    <span class="font-extrabold tracking-wide">Lihat Profil Lengkap</span>
                                    <div class="h-7 w-7 rounded-xl bg-brand-50 group-hover:bg-brand-500 group-hover:text-white grid place-items-center transition-all duration-300">
                                        <ChevronRight class="w-4 h-4 transition-transform group-hover:translate-x-0.5" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else
                        class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200 shadow-sm"
                        v-motion
                        :initial="{ opacity: 0 }"
                        :enter="{ opacity: 1, transition: { duration: 500 } }"
                    >
                        <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <Search class="w-10 h-10" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Guru Tidak Ditemukan</h3>
                        <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">
                            Tidak ada hasil yang sesuai dengan kata kunci pencarian atau filter kategori saat ini.
                        </p>
                        <button
                            type="button"
                            @click="searchQuery = ''; activeCategory = 'Semua'"
                            class="px-6 py-2.5 rounded-2xl bg-brand-50 text-brand-700 font-bold text-sm hover:bg-brand-100 transition-colors inline-flex items-center gap-2"
                        >
                            <span>Reset Pencarian</span>
                        </button>
                    </div>
                </section>
            </div>
        </div>

        <!-- ================= TEACHER DETAIL MODAL POPUP ================= -->
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
                    v-if="selectedTeacher"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/80 backdrop-blur-xl p-4 sm:p-6"
                    @click.self="closeTeacherModal"
                >
                    <div
                        class="relative max-w-2xl w-full bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden"
                        @click.stop
                    >
                        <!-- Modal Close Button -->
                        <button
                            type="button"
                            @click="closeTeacherModal"
                            aria-label="Tutup detail"
                            class="absolute top-5 right-5 z-20 h-11 w-11 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white grid place-items-center backdrop-blur-md transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none"
                        >
                            <X class="w-5 h-5" />
                        </button>

                        <div class="flex flex-col sm:flex-row">
                            <!-- Left: Teacher Image -->
                            <div class="sm:w-5/12 bg-gradient-to-br from-slate-100 to-slate-200 relative aspect-[3/4] sm:aspect-auto">
                                <img
                                    v-if="selectedTeacher.photo"
                                    :src="selectedTeacher.photo"
                                    :alt="selectedTeacher.name"
                                    class="w-full h-full object-cover"
                                    @error="(e) => { e.target.classList.add('hidden'); if (e.target.nextElementSibling) e.target.nextElementSibling.classList.remove('hidden'); }"
                                />
                                <div :class="{ 'hidden': selectedTeacher.photo }" class="w-full h-full flex flex-col items-center justify-center p-8 bg-gradient-to-br from-brand-50 to-indigo-100 text-brand-600">
                                    <div class="w-24 h-24 rounded-3xl bg-brand-200/80 flex items-center justify-center font-black text-3xl text-brand-700 shadow-inner mb-3">
                                        {{ getInitials(selectedTeacher.name) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-500">Foto Guru</span>
                                </div>
                            </div>

                            <!-- Right: Detailed Info -->
                            <div class="sm:w-7/12 p-6 sm:p-8 flex flex-col justify-between">
                                <div>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 mb-3">
                                        <Sparkles class="w-3.5 h-3.5" />
                                        {{ selectedTeacher.category || 'Tenaga Pendidik' }}
                                    </span>

                                    <h3 class="text-2xl font-black text-slate-900 leading-tight">
                                        {{ selectedTeacher.name }}
                                    </h3>

                                    <p class="text-brand-600 font-bold text-sm mt-1">
                                        {{ selectedTeacher.position || selectedTeacher.subject || 'Staf Pengajar' }}
                                    </p>

                                    <!-- Key Attributes -->
                                    <div class="mt-6 space-y-3">
                                        <div v-if="selectedTeacher.subject" class="flex items-start gap-3 text-sm">
                                            <BookOpen class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" />
                                            <div>
                                                <span class="text-slate-400 text-xs block">Mata Pelajaran</span>
                                                <span class="font-bold text-slate-800">{{ selectedTeacher.subject }}</span>
                                            </div>
                                        </div>

                                        <div v-if="selectedTeacher.bio" class="flex items-start gap-3 text-sm">
                                            <Award class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" />
                                            <div>
                                                <span class="text-slate-400 text-xs block">Biodata / Profil</span>
                                                <p class="text-slate-700 text-sm leading-relaxed mt-0.5">{{ selectedTeacher.bio }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Actions in Modal -->
                                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap gap-3">
                                    <a
                                        v-if="selectedTeacher.email"
                                        :href="'mailto:' + selectedTeacher.email"
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-xs transition-colors"
                                    >
                                        <Mail class="w-4 h-4" />
                                        <span>Kirim Email</span>
                                    </a>

                                    <a
                                        v-if="selectedTeacher.phone"
                                        :href="'tel:' + selectedTeacher.phone"
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs transition-colors"
                                    >
                                        <Phone class="w-4 h-4" />
                                        <span>Hubungi Telepon</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </MainLayout>
</template>
