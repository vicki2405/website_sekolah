<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { Plus, Pencil, Trash2, Eye, EyeOff, Upload } from 'lucide-vue-next';

const props = defineProps({
    items: Array,
});

const isOpen = ref(false);
const editing = ref(null);

const form = useForm({
    name: '',
    slug: '',
    description: '',
    icon: '',
    image: '',
    image_file: null,
    duration: 3,
    order: 0,
    is_active: true,
});

const modalTitle = computed(() => (editing.value ? 'Edit Program' : 'Tambah Program'));

const slugify = (value) =>
    (value || '')
        .toString()
        .trim()
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');

watch(
    () => form.name,
    (val) => {
        if (editing.value) return;
        if (!form.slug) form.slug = slugify(val);
    }
);

const openCreate = () => {
    editing.value = null;
    form.reset();
    form.clearErrors();
    form.duration = 3;
    form.order = 0;
    form.is_active = true;
    isOpen.value = true;
};

const openEdit = (item) => {
    editing.value = item;
    form.clearErrors();
    form.name = item.name ?? '';
    form.slug = item.slug ?? '';
    form.description = item.description ?? '';
    form.icon = item.icon ?? '';
    form.image = item.image ?? '';
    form.image_file = null;
    form.duration = item.duration ?? 3;
    form.order = item.order ?? 0;
    form.is_active = !!item.is_active;
    isOpen.value = true;
};

const closeModal = () => {
    isOpen.value = false;
};

const onPickFile = (e) => {
    const file = e.target.files?.[0] ?? null;
    form.image_file = file;
};

const submit = () => {
    if (editing.value) {
        form.put(route('admin.programs.update', editing.value.id), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                closeModal();
                form.reset();
            },
        });
        return;
    }

    form.post(route('admin.programs.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            closeModal();
            form.reset();
        },
    });
};

const destroyItem = (item) => {
    if (!confirm(`Hapus program "${item.name}"?`)) return;
    router.delete(route('admin.programs.destroy', item.id), { preserveScroll: true });
};
</script>

<template>
    <Head title="Admin • Program" />

    <AdminLayout>
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-white">Program</h1>
                    <p class="mt-1 text-sm text-white/65">Kelola jurusan/keahlian beserta gambar dan deskripsinya.</p>
                </div>
                <button
                    type="button"
                    @click="openCreate"
                    class="inline-flex items-center gap-2 rounded-2xl px-5 py-3 bg-brand-500 hover:bg-brand-600 text-white font-bold shadow-[0_22px_70px_-30px_rgba(39,149,245,0.9)] transition-all"
                >
                    <Plus class="h-5 w-5" />
                    Tambah
                </button>
            </div>

            <div class="rounded-[2rem] border border-white/10 bg-white/[0.06] backdrop-blur-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-widest text-white/50 border-b border-white/10">
                                <th class="px-6 py-4">Program</th>
                                <th class="px-6 py-4">Slug</th>
                                <th class="px-6 py-4">Order</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 w-[140px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            <tr v-for="item in items" :key="item.id" class="hover:bg-white/[0.04] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-2xl overflow-hidden border border-white/10 bg-black/30">
                                            <img v-if="item.image" :src="item.image" :alt="item.name" class="h-full w-full object-cover" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-white/90">{{ item.name }}</div>
                                            <div class="text-xs text-white/55 line-clamp-1 mt-1">{{ item.description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-white/70">{{ item.slug }}</td>
                                <td class="px-6 py-4 text-sm text-white/70">{{ item.order }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold border"
                                        :class="item.is_active ? 'bg-emerald-500/15 border-emerald-500/25 text-emerald-200' : 'bg-white/5 border-white/10 text-white/60'"
                                    >
                                        <component :is="item.is_active ? Eye : EyeOff" class="h-3.5 w-3.5" />
                                        {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            @click="openEdit(item)"
                                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-white/8 hover:bg-white/12 border border-white/10 transition-all"
                                        >
                                            <Pencil class="h-4 w-4 text-white/80" />
                                        </button>
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
                                    Belum ada data.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Modal :show="isOpen" maxWidth="2xl" @close="closeModal">
            <div class="p-6 sm:p-8 bg-white">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">{{ modalTitle }}</h2>
                        <p class="text-sm text-slate-500 mt-1">Data program akan dipakai di halaman keahlian dan beranda.</p>
                    </div>
                    <button type="button" class="h-10 w-10 rounded-xl bg-slate-100 hover:bg-slate-200 transition-colors" @click="closeModal">✕</button>
                </div>

                <form class="mt-6 grid grid-cols-1 gap-5" @submit.prevent="submit">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <InputLabel for="name" value="Nama Program" />
                            <TextInput id="name" v-model="form.name" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="slug" value="Slug" />
                            <TextInput id="slug" v-model="form.slug" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.slug" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="description" value="Deskripsi" />
                        <textarea id="description" v-model="form.description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <InputLabel for="icon" value="Icon (opsional)" />
                            <TextInput id="icon" v-model="form.icon" class="mt-1 block w-full" placeholder="car / cpu / chef-hat" />
                            <InputError class="mt-2" :message="form.errors.icon" />
                        </div>
                        <div>
                            <InputLabel for="duration" value="Durasi (tahun)" />
                            <TextInput id="duration" type="number" v-model="form.duration" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.duration" />
                        </div>
                        <div>
                            <InputLabel for="order" value="Urutan" />
                            <TextInput id="order" type="number" v-model="form.order" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.order" />
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <input type="checkbox" v-model="form.is_active" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" />
                            Aktifkan
                        </label>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">Gambar Program</div>
                                <div class="text-xs text-slate-500 mt-1">Upload file atau isi URL gambar.</div>
                            </div>
                            <label class="inline-flex items-center gap-2 rounded-xl px-4 py-2 bg-white border border-slate-200 hover:border-brand-300 hover:bg-brand-50 transition-all cursor-pointer text-sm font-semibold text-slate-700">
                                <Upload class="h-4 w-4" />
                                Upload
                                <input type="file" accept="image/*" class="hidden" @change="onPickFile" />
                            </label>
                        </div>

                        <div class="mt-4">
                            <TextInput v-model="form.image" class="block w-full" placeholder="https://... atau /storage/..." />
                            <InputError class="mt-2" :message="form.errors.image || form.errors.image_file" />
                        </div>

                        <div class="mt-4 flex items-center gap-4">
                            <div class="h-16 w-28 rounded-xl overflow-hidden border border-slate-200 bg-white">
                                <img v-if="form.image" :src="form.image" alt="preview" class="h-full w-full object-cover" />
                            </div>
                            <div class="text-xs text-slate-500">
                                Maksimal 5MB. Format JPG/PNG/WebP.
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 flex items-center justify-end gap-3">
                        <button type="button" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold" @click="closeModal">
                            Batal
                        </button>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Simpan
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AdminLayout>
</template>

