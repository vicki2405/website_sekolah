<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Eye, EyeOff, Trash2, ArrowUpRight } from 'lucide-vue-next';

defineProps({
    items: Array,
});

const destroyItem = (item) => {
    if (!confirm(`Hapus pesan dari "${item.name}"?`)) return;
    router.delete(route('admin.contacts.destroy', item.id), { preserveScroll: true });
};
</script>

<template>
    <Head title="Admin • Kontak" />

    <AdminLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-white">Kontak</h1>
                <p class="mt-1 text-sm text-white/65">Pesan masuk dari form kontak.</p>
            </div>

            <div class="rounded-[2rem] border border-white/10 bg-white/[0.06] backdrop-blur-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-widest text-white/50 border-b border-white/10">
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Pengirim</th>
                                <th class="px-6 py-4">Subjek</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4 w-[160px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            <tr v-for="item in items" :key="item.id" class="hover:bg-white/[0.04] transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold border"
                                        :class="item.is_read ? 'bg-white/5 border-white/10 text-white/60' : 'bg-brand-500/15 border-brand-500/25 text-brand-100'"
                                    >
                                        <component :is="item.is_read ? Eye : EyeOff" class="h-3.5 w-3.5" />
                                        {{ item.is_read ? 'Dibaca' : 'Baru' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-white/90">{{ item.name }}</div>
                                    <div class="text-xs text-white/55 mt-1">{{ item.email }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-white/80">{{ item.subject }}</td>
                                <td class="px-6 py-4 text-sm text-white/70">
                                    {{ item.created_at ? new Date(item.created_at).toLocaleString('id-ID') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <Link
                                            :href="route('admin.contacts.show', item.id)"
                                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2 bg-white/10 hover:bg-white/15 border border-white/10 transition-all text-sm font-semibold text-white/85"
                                        >
                                            Detail
                                            <ArrowUpRight class="h-4 w-4" />
                                        </Link>
                                        <button
                                            type="button"
                                            @click="destroyItem(item)"
                                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-rose-500/15 hover:bg-rose-500/20 border border-rose-500/20 transition-all"
                                        >
                                            <Trash2 class="h-4 w-4 text-rose-200" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!items || items.length === 0">
                                <td colspan="5" class="px-6 py-10 text-center text-white/60">
                                    Belum ada pesan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

