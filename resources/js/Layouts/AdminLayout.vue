<template>
    <div class="min-h-screen bg-[#bbdffc] flex">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 w-72 bg-transparent backdrop-blur-xl border-r border-white/20 z-50 transition-all duration-300 transform lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            style="background: linear-gradient(180deg, rgba(255, 255, 255, 0.92) 0%, rgba(241, 245, 249, 0.90) 55%, rgba(230, 242, 255, 0.86) 100%);"
        >
            <div class="flex flex-col h-full">
                <!-- Logo Area -->
                <div class="p-8 flex items-center gap-3">
                    <div class="h-10 w-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                        <LayoutDashboard class="text-white h-6 w-6" />
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">Admin Panel</span>
                </div>

                <!-- Navigation -->
                <div class="flex-1 overflow-y-auto px-4 py-4 custom-scrollbar">
                    <nav class="space-y-1.5">
                        <template v-for="(group, idx) in menuGroups" :key="idx">
                            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 mt-6">
                                {{ group.title }}
                            </p>

                            <Link
                                v-for="item in group.items"
                                :key="item.name"
                                :href="item.route"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                                :class="route().current(item.active) ? 'bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-lg shadow-blue-400/30' : 'text-slate-700 hover:bg-white/40 hover:text-slate-900'"
                            >
                                <component
                                    :is="item.icon"
                                    class="h-5 w-5"
                                    :class="route().current(item.active) ? 'text-white' : 'text-slate-600 group-hover:text-blue-600'"
                                />
                                <span class="font-medium text-sm">{{ item.name }}</span>
                            </Link>
                        </template>
                    </nav>
                </div>

                <!-- Footer / Logout -->
                <div class="p-6 border-t border-white/20">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-white/30 text-slate-600 font-bold text-sm hover:bg-red-50 hover:text-red-600 transition-all duration-300 group"
                    >
                        <LogOut class="h-4 w-4 text-slate-400 group-hover:text-red-500 transition-colors" />
                        Logout System
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Overlay -->
        <div
            v-if="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden"
        ></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-72">
            <!-- Header -->
            <header class="h-20 bg-[#bbdffc]/80 backdrop-blur-md sticky top-0 z-30 px-4 sm:px-8 flex items-center justify-between border-b border-white/20">
                <div class="flex items-center gap-4">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 rounded-lg bg-white/40 text-slate-600 hover:bg-white/60 transition-colors"
                    >
                        <Menu class="h-6 w-6" />
                    </button>
                    <div>
                        <h1 class="text-sm font-bold text-slate-900 tracking-tight">{{ title }}</h1>
                        <div class="flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                            <span>Pages</span>
                            <ChevronRight class="h-2.5 w-2.5" />
                            <span class="text-blue-600">{{ breadcrumb }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-5">
                    <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-emerald-50 rounded-full border border-emerald-100">
                        <div class="h-2 w-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">System Online</span>
                    </div>

                    <div class="h-10 w-10 rounded-full bg-white/40 border-2 border-white shadow-sm overflow-hidden shrink-0">
                        <img :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&background=3b82f6&color=fff`" alt="Avatar">
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 p-4 sm:p-8">
                <slot />
            </main>

            <!-- Footer -->
            <footer class="p-8 text-center text-xs font-medium text-slate-400">
                &copy; {{ new Date().getFullYear() }} {{ $page.props.appName || 'Laravel' }} Admin Panel. Built with Precision.
            </footer>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
    LayoutDashboard,
    Home,
    User,
    Newspaper,
    BookOpen,
    Users,
    Camera,
    Sparkles,
    Link as LinkIcon,
    Award,
    Calendar,
    Mail,
    LogOut,
    Menu,
    ChevronRight
} from 'lucide-vue-next'

defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    },
    breadcrumb: {
        type: String,
        default: 'Overview'
    }
})

const sidebarOpen = ref(false)

const menuGroups = [
    {
        title: 'Main Dashboard',
        items: [
            { name: 'Dashboard', icon: LayoutDashboard, route: route('admin.dashboard'), active: 'admin.dashboard' },
        ]
    },
    {
        title: 'Content & Media',
        items: [
            { name: 'Home', icon: Home, route: route('admin.home.index'), active: 'admin.home.*' },
            { name: 'Profile', icon: User, route: route('admin.profile.index'), active: 'admin.profile.*' },
            { name: 'News', icon: Newspaper, route: route('admin.news.index'), active: 'admin.news.*' },
            { name: 'Keahlian', icon: BookOpen, route: route('admin.programs.index'), active: 'admin.programs.*' },
            { name: 'WAKA', icon: Users, route: route('admin.waka.index'), active: 'admin.waka.*' },
            { name: 'Teachers', icon: Users, route: route('admin.teachers.index'), active: 'admin.teachers.*' },
            { name: 'Gallery', icon: Camera, route: route('admin.gallery.index'), active: 'admin.gallery.*' },
            { name: 'Fasilitas', icon: Sparkles, route: route('admin.facilities.index'), active: 'admin.facilities.*' },
            { name: 'Portal', icon: LinkIcon, route: route('admin.system-information.index'), active: 'admin.system-information.*' },
        ]
    },
    {
        title: 'Events & More',
        items: [
            { name: 'Prestasi', icon: Award, route: route('admin.prestasi.index'), active: 'admin.prestasi.*' },
            { name: 'Agendas', icon: Calendar, route: route('admin.agendas.index'), active: 'admin.agendas.*' },
            { name: 'Contacts', icon: Mail, route: route('admin.contacts.index'), active: 'admin.contacts.*' },
        ]
    }
]
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>
