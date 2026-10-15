<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useWindowScroll } from '@vueuse/core';
import { ArrowRight, ArrowUp, MapPin, Menu } from 'lucide-vue-next';

const isMenuOpen = ref(false);
const page = usePage();

const navigation = computed(() => ([
    { name: 'Home', href: route('home') },
    { name: 'Profil', href: route('about') },
    { name: 'Guru', href: route('teachers') },
    {
        name: 'Event',
        children: [
            { name: 'Agenda', href: route('event.agenda') },
            { name: 'Berita', href: route('news.index') },
        ]
    },
    { name: 'Prestasi', href: route('prestasi') },
    {
        name: 'Keahlian',
        children: [
            ...(page.props.keahlian_menu?.map(item => ({
                name: item.name,
                href: route('keahlian.show', { slug: item.slug })
            })) || [])
        ]
    },
    { name: 'Galeri', href: route('gallery') },
    {
        name: 'WAKA',
        children: page.props.waka_menu?.map(item => ({
            name: item.title,
            href: route('waka.show', { slug: item.slug })
        })) || []
    },
    { name: 'Fasilitas', href: route('facilities') },
    {
        name: 'TEFA',
        children: page.props.tefa_menu?.map(item => ({
            name: item.title,
            href: route('tefa.show', { slug: item.slug })
        })) || []
    },
    { name: 'Kontak', href: route('contact') },
    {
        name: 'Portal',
        children: page.props.system_info_menu?.map(item => ({
            name: item.name,
            href: item.url,
            external: !!item.url && item.url.startsWith('http'),
        })) || []
    },
]).filter(item => item.name === 'Portal' || item.name === 'TEFA' || !item.children || item.children.length > 0));

const activeDropdown = ref(null);
const mobileActiveDropdown = ref(null);

const toggleDropdown = (name) => {
    if (activeDropdown.value === name) {
        activeDropdown.value = null;
    } else {
        activeDropdown.value = name;
    }
};

const toggleMobileDropdown = (name) => {
    if (mobileActiveDropdown.value === name) {
        mobileActiveDropdown.value = null;
    } else {
        mobileActiveDropdown.value = name;
    }
};

const normalizeHref = (href) => {
    if (!href) return '';
    if (href.startsWith('http')) {
        try {
            const url = new URL(href);
            return url.pathname;
        } catch (e) {
            return href;
        }
    }
    return href;
};

const openMobileMenu = (sectionName = null) => {
    isMenuOpen.value = true;
    if (sectionName) {
        mobileActiveDropdown.value = sectionName;
    }
};

const { y: scrollY } = useWindowScroll();
const showScrollTop = computed(() => scrollY.value > 380);
const scrollToTop = () => {
    if (typeof window !== 'undefined') {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

const mobileBottomItems = computed(() => ([
    { type: 'menu', name: 'Menu', action: () => openMobileMenu(), icon: Menu },
    { type: 'link', name: 'Home', href: route('home') },
    { type: 'link', name: 'Profil', href: route('about') },
    { type: 'link', name: 'Berita', href: route('news.index') },
    { type: 'link', name: 'Kontak', href: route('contact') },
]));
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-800 font-sans selection:bg-brand-600 selection:text-white">
        <!-- Navigation -->
        <nav class="fixed w-full z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-slate-200">
            <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center gap-4 xl:gap-6">
                    <div class="flex items-center gap-3 shrink-0 mr-2 xl:mr-6">
                        <Link :href="route('home')" class="flex items-center gap-3 shrink-0 group">
                            <img
                                src="/storage/images/logo2.png"
                                alt="Logo"
                                class="h-11 w-11 sm:h-12 sm:w-12 md:h-13 md:w-13 object-contain shrink-0 group-hover:scale-105 transition-transform"
                                width="56"
                                height="56"
                            />
                            <div class="leading-none shrink-0">
                                <div class="text-2xl sm:text-2xl xl:text-3xl font-black tracking-tight whitespace-nowrap">
                                    <span class="text-brand-600">STEMDA</span> <span class="text-slate-900">GENTENG</span>
                                </div>
                                <div
                                    class="mt-1 text-[10px] sm:text-[11px] xl:text-xs font-bold tracking-wide text-red-600 leading-snug whitespace-nowrap"
                                    style="text-shadow:-1px -1px 0 #fff,1px -1px 0 #fff,-1px 1px 0 #fff,1px 1px 0 #fff;"
                                >
                                    Outstanding and Innovative Vocational School
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Desktop Menu with Modern Hover Pill & Springy Transitions -->
                    <div class="hidden lg:flex space-x-0.5 xl:space-x-1 items-center">
                        <template v-for="item in navigation" :key="item.name">
                            <div v-if="item.children" class="relative group" @mouseenter="activeDropdown = item.name" @mouseleave="activeDropdown = null">
                                <button
                                    class="px-2.5 xl:px-3 py-2 rounded-xl text-[13px] xl:text-sm font-semibold text-slate-700 hover:text-brand-600 hover:bg-slate-50 transition-all duration-200 flex items-center gap-1"
                                    :class="{ 'text-brand-600 bg-brand-50/60 font-bold': activeDropdown === item.name || (item.children && item.children.some(child => $page.url === normalizeHref(child.href))) }"
                                >
                                    {{ item.name }}
                                    <svg class="w-3.5 h-3.5 transition-transform duration-300 ease-out" :class="{ 'rotate-180 text-brand-600': activeDropdown === item.name }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <Transition
                                    enter-active-class="transition ease-out duration-250"
                                    enter-from-class="opacity-0 translate-y-2 scale-95"
                                    enter-to-class="opacity-100 translate-y-0 scale-100"
                                    leave-active-class="transition ease-in duration-150"
                                    leave-from-class="opacity-100 translate-y-0 scale-100"
                                    leave-to-class="opacity-0 translate-y-2 scale-95"
                                >
                                    <div v-show="activeDropdown === item.name" class="absolute left-0 group-last:left-auto group-last:right-0 mt-1.5 w-60 rounded-2xl bg-white/95 backdrop-blur-xl shadow-2xl shadow-slate-300/50 border border-slate-100 py-2.5 z-50 ring-1 ring-slate-900/5">
                                        <div v-if="!item.children || item.children.length === 0" class="px-4 py-3 text-xs font-semibold text-slate-400">
                                            Belum ada menu
                                        </div>
                                        <template v-for="child in item.children" :key="child.name">
                                            <a v-if="child.external" :href="child.href" target="_blank"
                                               class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:text-brand-600 hover:bg-brand-50/80 transition-all rounded-xl mx-1.5">
                                                {{ child.name }}
                                            </a>
                                            <Link v-else :href="child.href"
                                                  class="block px-4 py-2.5 text-xs font-bold text-slate-700 hover:text-brand-600 hover:bg-brand-50/80 transition-all rounded-xl mx-1.5"
                                                  :class="{ 'text-brand-700 bg-brand-50 font-extrabold': $page.url === normalizeHref(child.href) }">
                                                {{ child.name }}
                                            </Link>
                                        </template>
                                    </div>
                                </Transition>
                            </div>

                            <Link v-else :href="item.href"
                                  class="px-2.5 xl:px-3 py-2 rounded-xl text-[13px] xl:text-sm font-semibold text-slate-700 hover:text-brand-600 hover:bg-slate-50 transition-all duration-200"
                                  :class="{ 'text-brand-600 bg-brand-50/60 font-bold': $page.url === normalizeHref(item.href) }">
                                {{ item.name }}
                            </Link>
                        </template>

                        <div class="ml-4 flex items-center gap-4 border-l border-slate-200 pl-4">
                            <template v-if="$page.props.auth?.user">
                                <Link :href="route('dashboard')" class="text-sm font-medium text-slate-600 hover:text-brand-600">
                                    Dashboard
                                </Link>
                                <Link v-if="$page.props.auth?.isAdmin" :href="route('admin.dashboard')" class="text-sm font-medium text-slate-600 hover:text-brand-600">
                                    Admin
                                </Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-show="isMenuOpen" class="lg:hidden fixed inset-0 z-[70]" @click.self="isMenuOpen = false">
                    <div class="absolute inset-0 bg-slate-900/45 backdrop-blur-sm"></div>

                    <Transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="translate-y-6 opacity-0"
                        enter-to-class="translate-y-0 opacity-100"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="translate-y-0 opacity-100"
                        leave-to-class="translate-y-6 opacity-0"
                    >
                        <div v-show="isMenuOpen" class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl border-t border-slate-200 max-h-[85vh] overflow-y-auto">
                            <div class="px-4 pt-3 pb-2">
                                <div class="mx-auto h-1.5 w-10 rounded-full bg-slate-200"></div>
                                <div class="mt-3 flex items-center justify-between">
                                    <div class="text-base font-semibold text-slate-900">Menu</div>
                                    <button type="button" aria-label="Tutup menu" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2" @click="isMenuOpen = false">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="px-4 pb-6 space-y-1">
                                <template v-for="item in navigation" :key="item.name">
                                    <div v-if="item.children" class="space-y-1">
                                        <button
                                            type="button"
                                            @click="toggleMobileDropdown(item.name)"
                                            class="w-full flex justify-between items-center px-3 py-2.5 rounded-2xl text-base font-semibold text-slate-800 hover:text-brand-600 hover:bg-slate-50 transition-colors"
                                        >
                                            {{ item.name }}
                                            <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': mobileActiveDropdown === item.name }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div v-show="mobileActiveDropdown === item.name" class="pl-3 space-y-1 bg-slate-50 rounded-2xl py-2">
                                            <div v-if="!item.children || item.children.length === 0" class="px-3 py-2 text-sm font-medium text-slate-400">
                                                Belum ada menu
                                            </div>
                                            <template v-for="child in item.children" :key="child.name">
                                                <a
                                                    v-if="child.external"
                                                    :href="child.href"
                                                    target="_blank"
                                                    class="block px-3 py-2.5 text-sm font-semibold text-slate-700 hover:text-brand-600 transition-colors"
                                                    @click="isMenuOpen = false"
                                                >
                                                    {{ child.name }}
                                                </a>
                                                <Link
                                                    v-else
                                                    :href="child.href"
                                                    class="block px-3 py-2.5 text-sm font-semibold text-slate-700 hover:text-brand-600 transition-colors rounded-xl"
                                                    :class="{ 'text-brand-700 bg-brand-50': $page.url === normalizeHref(child.href) }"
                                                    @click="isMenuOpen = false"
                                                >
                                                    {{ child.name }}
                                                </Link>
                                            </template>
                                        </div>
                                    </div>
                                    <Link
                                        v-else
                                        :href="item.href"
                                        class="block px-3 py-2.5 rounded-2xl text-base font-semibold text-slate-800 hover:text-brand-600 hover:bg-slate-50 transition-colors"
                                        :class="{ 'text-brand-700 bg-brand-50': $page.url === normalizeHref(item.href) }"
                                        @click="isMenuOpen = false"
                                    >
                                        {{ item.name }}
                                    </Link>
                                </template>

                                <div class="pt-4 mt-4 border-t border-slate-100 flex flex-col gap-2">
                                    <Link v-if="$page.props.auth?.user" :href="route('dashboard')" class="block px-3 py-2 text-base font-semibold text-slate-800 hover:text-brand-600" @click="isMenuOpen = false">
                                        Dashboard
                                    </Link>
                                    <Link v-if="$page.props.auth?.isAdmin" :href="route('admin.dashboard')" class="block px-3 py-2 text-base font-semibold text-slate-800 hover:text-brand-600" @click="isMenuOpen = false">
                                        Admin
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>

        <!-- Main Content -->
        <main class="pt-20 pb-32 lg:pb-0">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-300 pt-16 pb-36 lg:pb-16 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-12">
                <div class="md:col-span-4">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="font-bold text-2xl tracking-tight text-white">
                            SMKS <span class="text-brand-400">Muhammadiyah 2 Genteng</span>
                        </span>
                    </div>
                    <p class="text-slate-400 mb-8 max-w-sm leading-relaxed">
                        Mewujudkan SMK yang unggul dalam prestasi, berkarakter mulia, dan kompeten di bidang industri sesuai dengan tuntutan zaman.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-brand-500 transition-colors text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-brand-500 transition-colors text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.266.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.058-1.69-.072-4.949-.072zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-white mb-6">Tautan Cepat</h3>
                    <ul class="space-y-4">
                        <li><Link :href="route('home')" class="hover:text-brand-400 transition-colors">Beranda</Link></li>
                        <li><Link :href="route('about')" class="hover:text-brand-400 transition-colors">Profil Sekolah</Link></li>
                        <li><Link :href="route('news.index')" class="hover:text-brand-400 transition-colors">Berita Terkini</Link></li>
                        <li><Link :href="route('gallery')" class="hover:text-brand-400 transition-colors">Galeri Foto</Link></li>
                    </ul>
                </div>

                <div class="md:col-span-3">
                    <h3 class="text-lg font-semibold text-white mb-6">Hubungi Kami</h3>
                    <ul class="space-y-4 text-slate-400">
                        <li class="flex items-start gap-4">
                            <span class="text-brand-400">📍</span>
                            <span class="text-sm leading-relaxed">Jl. Hasanudin 103</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <span class="text-brand-400">📞</span>
                            <span class="text-sm">(0333) 846292</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <span class="text-brand-400">✉️</span>
                            <span class="text-sm">smkm2genteng@gmail.com</span>
                        </li>
                    </ul>
                </div>

                <div class="md:col-span-3">
                    <h3 class="text-lg font-semibold text-white mb-6">Lokasi Kami</h3>
                    <div class="relative rounded-2xl overflow-hidden h-48 w-full shadow-2xl group border border-slate-700/50 hover:border-brand-500/50 transition-colors duration-500 bg-slate-800">
                        <div class="absolute inset-0 border-2 border-transparent group-hover:border-brand-500/20 rounded-2xl z-10 pointer-events-none transition-colors duration-500"></div>
                        <iframe
                            src="https://maps.google.com/maps?q=-8.365732410182279,114.16757366848586&t=&z=18&ie=UTF8&iwloc=&output=embed"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="w-full h-full transition-all duration-700"
                        ></iframe>
                    </div>
                    <a href="https://www.google.com/maps?q=-8.365732410182279,114.16757366848586" target="_blank" class="mt-4 flex items-center justify-center w-full py-3 px-4 bg-slate-800 hover:bg-brand-500 text-slate-300 hover:text-white rounded-xl transition-all duration-300 text-sm font-medium group shadow-lg hover:shadow-brand-500/25">
                        <MapPin class="w-4 h-4 mr-2" />
                        Buka di Google Maps
                        <ArrowRight class="w-4 h-4 ml-2 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" />
                    </a>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 pt-8 border-t border-slate-800 text-center text-slate-500 text-sm">
                &copy; {{ new Date().getFullYear() }} SMKS Muhammadiyah 2 Genteng. Terakreditasi A.
            </div>
        </footer>

        <div class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/75 backdrop-blur-md">
            <div class="mx-auto max-w-screen-sm px-3 pb-[calc(env(safe-area-inset-bottom)+0.75rem)] pt-3">
                <div class="relative rounded-[1.75rem] bg-white/90 ring-1 ring-slate-200 shadow-[0_-10px_30px_-20px_rgba(15,23,42,0.55)] px-2 py-2.5">
                    <div class="pointer-events-none absolute inset-y-0 left-0 w-7 rounded-[1.75rem] bg-gradient-to-r from-white/95 to-white/0"></div>
                    <div class="pointer-events-none absolute inset-y-0 right-0 w-7 rounded-[1.75rem] bg-gradient-to-l from-white/95 to-white/0"></div>
                    <div class="flex items-center gap-2 overflow-x-auto scroll-smooth snap-x snap-mandatory px-1 [&::-webkit-scrollbar]:hidden" style="scrollbar-width:none;-ms-overflow-style:none;">
                    <template v-for="item in mobileBottomItems" :key="item.type + '-' + item.name">
                        <Link
                            v-if="item.type === 'link'"
                            :href="item.href"
                            class="snap-start flex-none rounded-2xl px-4 py-3 text-[15px] font-semibold tracking-tight transition-colors"
                            :class="($page.url === normalizeHref(item.href)) ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50'"
                            @click="isMenuOpen = false"
                        >
                            {{ item.name }}
                        </Link>
                        <button
                            v-else
                            type="button"
                            class="snap-start flex-none rounded-2xl px-4 py-3 text-[15px] font-semibold tracking-tight transition-colors flex items-center gap-2"
                            :class="(item.type === 'menu' && isMenuOpen) ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50'"
                            @click="item.action"
                        >
                            <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
                            <span class="leading-none">{{ item.name }}</span>
                        </button>
                    </template>
                </div>
                </div>
            </div>
        </div>

        <!-- Floating Scroll-To-Top Button -->
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 translate-y-4 scale-75"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-4 scale-75"
        >
            <button
                v-show="showScrollTop"
                @click="scrollToTop"
                type="button"
                aria-label="Kembali ke atas"
                class="fixed bottom-24 lg:bottom-8 right-5 lg:right-8 z-40 p-3.5 rounded-2xl bg-brand-600 hover:bg-brand-500 text-white shadow-xl shadow-brand-600/35 ring-1 ring-white/30 backdrop-blur-md transition-all duration-300 hover:scale-110 active:scale-95 group focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-2"
            >
                <ArrowUp class="h-5 w-5 transition-transform duration-300 group-hover:-translate-y-0.5" />
            </button>
        </Transition>
    </div>
</template>
