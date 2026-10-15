@extends('admin.layout')

@section('title', $tefa->exists ? 'Edit TEFA' : 'Tambah TEFA')
@section('breadcrumb', $tefa->exists ? 'TEFA / Edit' : 'TEFA / Create')
@section('page-title', $tefa->exists ? 'Edit TEFA' : 'Tambah TEFA')

@section('content')
<div class="animate__animated animate__fadeInUp">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.tefa.index') }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-primary-600 hover:border-primary-100 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $tefa->exists ? 'Edit Halaman TEFA' : 'Tambah Halaman TEFA' }}</h2>
                <p class="text-slate-500 text-sm">Atur judul, konten, dan galeri gambar untuk TEFA.</p>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-primary-600"></i>
                    Form TEFA
                </h3>
            </div>

            <div class="p-8">
                <form action="{{ $tefa->exists ? route('admin.tefa.update', $tefa) : route('admin.tefa.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="tefa-form">
                    @csrf
                    @if($tefa->exists)
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Judul</label>
                            <input id="tefa-title" type="text" name="title" value="{{ old('title', $tefa->title) }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Contoh: TEFA Kuliner">
                            @error('title')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Slug / URL</label>
                            <input id="tefa-slug" type="text" name="slug" value="{{ old('slug', $tefa->slug) }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="contoh: tefa-kuliner">
                            @error('slug')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Subtitle Hero</label>
                        <input type="text" name="hero_subtitle" value="{{ old('hero_subtitle', $tefa->hero_subtitle) }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Contoh: Teaching Factory • Dokumentasi dan informasi kegiatan">
                        <p class="text-xs text-slate-500 ml-1">Text ini tampil di bawah judul utama pada hero halaman TEFA.</p>
                        @error('hero_subtitle')
                            <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Urutan</label>
                            <input type="number" name="order" value="{{ old('order', $tefa->order ?? 0) }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="0">
                            @error('order')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Status</label>
                            <div class="flex items-center gap-3 pt-3">
                                <input type="hidden" name="is_active" value="0">
                                <label class="inline-flex items-center gap-2 text-sm font-bold text-slate-700">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tefa->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                    Tampilkan di menu TEFA
                                </label>
                            </div>
                            @error('is_active')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Konten</label>
                        <textarea name="content" id="tefa-content" class="hidden">{{ old('content', $tefa->content) }}</textarea>
                        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                            <div
                                data-quill-textarea-id="tefa-content"
                                data-upload-url="{{ route('admin.tefa.editor-images.store') }}"
                                data-placeholder="Tulis konten TEFA di sini..."
                                class="bg-white"
                                style="min-height: 220px;"
                            ></div>
                        </div>
                        @error('content')
                            <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="rounded-[28px] border border-slate-200 bg-slate-50/70 p-6 space-y-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-slate-800">Background Hero</div>
                                <div class="text-xs text-slate-500 mt-1">Gambar ini dipakai sebagai latar belakang header halaman TEFA.</div>
                            </div>
                            @if($tefa->exists && $tefa->background_image)
                                <div class="h-16 w-24 rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm shrink-0 flex items-center justify-center">
                                    <img src="{{ str_starts_with($tefa->background_image, 'http') ? $tefa->background_image : asset(ltrim($tefa->background_image, '/')) }}" alt="Background Hero" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                    <div class="h-full w-full items-center justify-center text-slate-300 hidden"><i data-lucide="image" class="h-6 w-6"></i></div>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Upload Background</label>
                            <input type="file" name="background_image_file" accept="image/*" class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                            <p class="text-xs text-slate-500 mt-1">Saat upload baru, background lama akan diganti otomatis dan file akan dikompres.</p>
                            @error('background_image_file')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-[28px] border border-slate-200 bg-slate-50/70 p-6 space-y-5">
                        <div>
                            <div class="text-sm font-bold text-slate-800">Galeri Gambar (Slider)</div>
                            <div class="text-xs text-slate-500 mt-1">Upload banyak gambar sekaligus. Gambar baru akan ditambahkan tanpa menghapus gambar yang sudah ada.</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Upload Gambar (Multiple)</label>
                            <input type="file" name="gallery_files[]" accept="image/*" multiple class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                            @error('gallery_files.*')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($tefa->exists && is_array($tefa->gallery) && count($tefa->gallery) > 0)
                            <div class="mt-4">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Gambar Tersimpan (Centang untuk Hapus)</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                                    @foreach($tefa->gallery as $img)
                                        <div class="relative group rounded-xl overflow-hidden border border-slate-200 aspect-square flex items-center justify-center bg-slate-100">
                                            <img src="{{ str_starts_with($img, 'http') ? $img : asset(ltrim($img, '/')) }}" alt="Gallery Image" class="w-full h-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                            <div class="h-full w-full items-center justify-center text-slate-300 hidden"><i data-lucide="image" class="h-6 w-6"></i></div>
                                            <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <label class="cursor-pointer p-2 flex flex-col items-center text-white">
                                                    <input type="checkbox" name="remove_gallery[]" value="{{ $img }}" class="rounded border-slate-300 text-red-500 focus:ring-red-500 w-5 h-5 mb-1">
                                                    <span class="text-xs font-bold shadow-sm">Hapus</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
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
        const titleInput = document.getElementById('tefa-title');
        const slugInput = document.getElementById('tefa-slug');

        if (titleInput && slugInput && !slugInput.value) {
            titleInput.addEventListener('input', function() {
                let slug = this.value.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)+/g, '');
                slugInput.value = slug;
            });
        }
    });
</script>
@endpush
