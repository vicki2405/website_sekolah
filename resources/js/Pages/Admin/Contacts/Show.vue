<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Trash2, Mail, Phone, User, Calendar } from 'lucide-vue-next';

const props = defineProps({
    item: Object,
});

const destroyItem = () => {
    if (!confirm(`Hapus pesan dari "${props.item.name}"?`)) return;
    router.delete(route('admin.contacts.destroy', props.item.id));
};
</script>

<template>
    <Head :title="`Admin • Kontak • ${item.name}`" />

    <AdminLayout>
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <Link :href="route('admin.contacts.index')" class="inline-flex items-center gap-2 text-sm font-semibold text-white/70 hover:text-white">
                        <ArrowLeft class="h-4 w-4" />
                        Kembali
                    </Link>
                    <h1 class="mt-3 text-2xl font-black tracking-tight text-white">Detail Pesan</h1>
                    <p class="mt-1 text-sm text-white/65">Pesan dari form kontak.</p>
                </div>
                <button
                    type="button"
                    @click="destroyItem"
                    class="inline-flex items-center gap-2 rounded-2xl px-5 py-3 bg-rose-500/20 hover:bg-rose-500/25 border border-rose-500/25 text-rose-100 font-bold transition-all"
                >
                    <Trash2 class="h-5 w-5" />
                    Hapus
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6">
                <div class="rounded-[2rem] border border-white/10 bg-white/[0.06] backdrop-blur-xl overflow-hidden">
                    <div class="p-7 sm:p-8">
                        <div class="text-sm font-semibold text-white/60">Subjek</div>
                        <div class="mt-2 text-xl font-black text-white">{{ item.subject }}</div>

                        <div class="mt-6 h-px bg-white/10"></div>

                        <div class="mt-6 text-sm font-semibold text-white/60">Pesan</div>
                        <div class="mt-3 whitespace-pre-wrap leading-relaxed text-white/85">
                            {{ item.message }}
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-white/10 bg-white/[0.06] backdrop-blur-xl overflow-hidden">
                    <div class="p-7">
                        <div class="text-sm font-black text-white">Pengirim</div>

                        <div class="mt-5 space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-2xl bg-white/8 border border-white/10 grid place-items-center">
                                    <User class="h-5 w-5 text-white/70" />
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-white/90">{{ item.name }}</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-2xl bg-white/8 border border-white/10 grid place-items-center">
                                    <Mail class="h-5 w-5 text-white/70" />
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-white/90 break-all">{{ item.email }}</div>
                                </div>
                            </div>
                            <div v-if="item.phone" class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-2xl bg-white/8 border border-white/10 grid place-items-center">
                                    <Phone class="h-5 w-5 text-white/70" />
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-white/90">{{ item.phone }}</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-2xl bg-white/8 border border-white/10 grid place-items-center">
                                    <Calendar class="h-5 w-5 text-white/70" />
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-white/90">
                                        {{ item.created_at ? new Date(item.created_at).toLocaleString('id-ID') : '-' }}
                                    </div>
                                    <div class="text-xs text-white/55 mt-1">
                                        {{ item.is_read ? 'Sudah dibaca' : 'Belum dibaca' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

