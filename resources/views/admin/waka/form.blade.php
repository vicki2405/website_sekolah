@extends('admin.layout')

@section('title', $waka->exists ? 'Edit WAKA' : 'Tambah WAKA')
@section('breadcrumb', $waka->exists ? 'WAKA / Edit' : 'WAKA / Create')
@section('page-title', $waka->exists ? 'Edit WAKA' : 'Tambah WAKA')

@php
    $existingGalleryItems = [];

    if ($waka->exists) {
        $oldExistingUrls = old('existing_gallery_images');
        $oldExistingCaptions = old('existing_gallery_captions');

        if (is_array($oldExistingUrls)) {
            foreach ($oldExistingUrls as $index => $url) {
                $normalizedUrl = trim((string) $url);
                if ($normalizedUrl === '') continue;
                $existingGalleryItems[] = [
                    'url' => $normalizedUrl,
                    'caption' => trim((string) ($oldExistingCaptions[$index] ?? '')),
                ];
            }
        } else {
            foreach (\App\Models\Waka::normalizeGallery($waka->gallery ?? []) as $item) {
                $normalizedUrl = trim((string) ($item['url'] ?? ''));
                if ($normalizedUrl === '') continue;
                $existingGalleryItems[] = [
                    'url' => $normalizedUrl,
                    'caption' => trim((string) ($item['caption'] ?? '')),
                ];
            }
        }
    }
@endphp

@section('content')
<div class="animate__animated animate__fadeInUp">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.waka.index') }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-primary-600 hover:border-primary-100 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $waka->exists ? 'Edit Halaman WAKA' : 'Tambah Halaman WAKA' }}</h2>
                <p class="text-slate-500 text-sm">Atur konten untuk sub-menu WAKA.</p>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-primary-600"></i>
                    Form WAKA
                </h3>
            </div>

            <div class="p-8">
                <form action="{{ $waka->exists ? route('admin.waka.update', $waka) : route('admin.waka.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="waka-form">
                    @csrf
                    @if($waka->exists)
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Judul Menu</label>
                            <input id="waka-title" type="text" name="title" value="{{ old('title', $waka->title) }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Contoh: Waka Kurikulum">
                            @error('title')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Slug / URL</label>
                            <input id="waka-slug" type="text" name="slug" value="{{ old('slug', $waka->slug) }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="contoh: waka-kurikulum">
                            @error('slug')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-[28px] border border-slate-200 bg-slate-50/70 p-6 space-y-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-slate-800">Foto Profil WAKA</div>
                                <div class="text-xs text-slate-500 mt-1">Upload foto WAKA yang akan tampil di sebelah kiri halaman.</div>
                            </div>
                            @if($waka->exists && $waka->image)
                                <div class="h-14 w-14 rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm flex items-center justify-center">
                                    <img src="{{ str_starts_with($waka->image, 'http') ? $waka->image : asset(ltrim($waka->image, '/')) }}" alt="Foto" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                    <div class="h-full w-full items-center justify-center text-slate-400 hidden"><i data-lucide="user" class="h-6 w-6"></i></div>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Upload Foto</label>
                            <input type="file" name="image_file" accept="image/*" class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                            @error('image_file')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Foto</label>
                            <input type="text" name="photo_name" value="{{ old('photo_name', $waka->photo_name) }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Contoh: Drs. H. Suharyono, M.Pd.">
                            @error('photo_name')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Deskripsi Singkat</label>
                        <textarea name="description" id="waka-description" class="hidden">{{ old('description', $waka->description) }}</textarea>
                        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                            <div data-quill-textarea-id="waka-description" data-placeholder="Masukkan deskripsi singkat yang tampil di sebelah foto WAKA..." class="bg-white min-h-[150px]"></div>
                        </div>
                        @error('description')
                            <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Konten Lengkap (Opsional)</label>
                        <textarea name="content" id="waka-content" class="hidden">{{ old('content', $waka->content) }}</textarea>
                        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                            <div data-quill-textarea-id="waka-content" data-placeholder="Tulis konten lengkap di sini..." data-upload-url="{{ route('admin.news.editor-images.store') }}" class="bg-white min-h-[300px]"></div>
                        </div>
                        @error('content')
                            <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="rounded-[28px] border border-slate-200 bg-slate-50/70 p-6 space-y-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-slate-800">Galeri Foto Kegiatan</div>
                                <div class="text-xs text-slate-500 mt-1">Upload foto-foto kegiatan. Setiap gambar bisa diberi caption singkat.</div>
                            </div>
                        </div>

                        @if(count($existingGalleryItems) > 0)
                            <div class="rounded-2xl border border-slate-200 bg-white p-3">
                                <p class="px-1 pb-3 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Gambar Tersimpan</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($existingGalleryItems as $index => $item)
                                        <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 relative group">
                                            <div class="aspect-[4/3] bg-slate-100 flex items-center justify-center overflow-hidden">
                                                <img src="{{ str_starts_with($item['url'], 'http') ? $item['url'] : asset(ltrim($item['url'], '/')) }}" class="w-full h-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                                <div class="h-full w-full items-center justify-center text-slate-300 hidden"><i data-lucide="image" class="h-8 w-8"></i></div>
                                            </div>
                                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                                <label class="cursor-pointer flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-white/90 backdrop-blur-sm border border-slate-200 text-red-600 text-xs font-bold shadow-sm hover:bg-red-50 transition-all">
                                                    <input type="checkbox" name="remove_gallery[]" value="{{ $item['url'] }}" class="rounded border-slate-300 text-red-500 focus:ring-red-500 w-4 h-4">
                                                    Hapus
                                                </label>
                                            </div>
                                            <div class="space-y-2 p-4 bg-white">
                                                <input type="hidden" name="existing_gallery_images[]" value="{{ $item['url'] }}">
                                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-[0.18em]">Caption Gambar {{ $index + 1 }}</label>
                                                <input
                                                    type="text"
                                                    name="existing_gallery_captions[]"
                                                    value="{{ $item['caption'] }}"
                                                    maxlength="255"
                                                    placeholder="Contoh: Dokumentasi kegiatan rapat"
                                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 text-sm"
                                                >
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Tambah Foto Baru</label>
                            <input type="file" name="gallery_files[]" accept="image/*" multiple id="gallery_files" class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                            <p class="text-xs text-slate-500 mt-1">Bisa pilih banyak foto sekaligus.</p>
                            @error('gallery_files.*')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="gallery_preview" class="hidden mt-3 rounded-2xl border border-slate-200 bg-white p-3">
                            <p class="px-1 pb-3 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Gambar Baru</p>
                            <div id="gallery_preview_grid" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-2">
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="is_active" value="0">
                            <label class="inline-flex items-center gap-2 text-sm font-bold text-slate-700">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $waka->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                Tampilkan di Menu Utama
                            </label>
                            @error('is_active')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/admin-news-editor.js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('waka-title');
        const slugInput = document.getElementById('waka-slug');

        if (titleInput && slugInput && !slugInput.value) {
            titleInput.addEventListener('input', function() {
                let slug = this.value.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)+/g, '');
                slugInput.value = slug;
            });
        }

        // Gallery preview with captions
        const galleryInput = document.getElementById('gallery_files');
        const galleryPreview = document.getElementById('gallery_preview');
        const galleryGrid = document.getElementById('gallery_preview_grid');

        if (galleryInput && galleryGrid) {
            galleryInput.addEventListener('change', function () {
                galleryGrid.innerHTML = '';
                const files = this.files ? Array.from(this.files) : [];
                if (files.length === 0) {
                    galleryPreview.classList.add('hidden');
                    return;
                }
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'rounded-2xl overflow-hidden border border-slate-200 bg-slate-50';
                        const media = document.createElement('div');
                        media.className = 'aspect-[4/3] bg-slate-100';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-full object-cover';
                        media.appendChild(img);

                        const content = document.createElement('div');
                        content.className = 'space-y-2 p-4 bg-white';

                        const label = document.createElement('label');
                        label.className = 'text-[11px] font-bold text-slate-500 uppercase tracking-[0.18em]';
                        label.textContent = 'Caption Gambar ' + (index + 1);

                        const input = document.createElement('input');
                        input.type = 'text';
                        input.name = 'new_gallery_captions[]';
                        input.maxLength = 255;
                        input.placeholder = 'Contoh: Dokumentasi kegiatan rapat';
                        input.className = 'w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 text-sm';

                        content.appendChild(label);
                        content.appendChild(input);
                        wrapper.appendChild(media);
                        wrapper.appendChild(content);
                        galleryGrid.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
                galleryPreview.classList.remove('hidden');
            });
        }
    });
</script>
@endpush
