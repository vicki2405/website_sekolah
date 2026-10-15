@extends('admin.layout')

@section('title', $news->exists ? 'Edit Berita' : 'Tambah Berita')
@section('breadcrumb', $news->exists ? 'News / Edit' : 'News / Create')
@section('page-title', $news->exists ? 'Edit Berita' : 'Tambah Berita')

@section('content')
<div class="animate__animated animate__fadeInUp">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.news.index') }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-primary-600 hover:border-primary-100 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $news->exists ? 'Edit Berita' : 'Tambah Berita Baru' }}</h2>
                <p class="text-slate-500 text-sm">Lengkapi informasi berita dan konten artikel.</p>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-primary-600"></i>
                    Form Berita
                </h3>
            </div>

            <div class="p-8">
                <form action="{{ $news->exists ? route('admin.news.update', $news) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="news-form">
                    @csrf
                    @if($news->exists)
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Judul</label>
                            <input id="news-title" type="text" name="title" value="{{ old('title', $news->title) }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Masukkan judul berita...">
                            @error('title')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Slug</label>
                            <input id="news-slug" type="text" name="slug" value="{{ old('slug', $news->slug) }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="contoh: kegiatan-lomba-2026">
                            @error('slug')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Ringkasan (Excerpt)</label>
                        <textarea name="excerpt" rows="3" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Ringkasan singkat berita (opsional)">{{ old('excerpt', $news->excerpt) }}</textarea>
                        @error('excerpt')
                            <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Konten Berita</label>
                        <textarea name="content" id="news-content" class="hidden">{{ old('content', $news->content) }}</textarea>
                        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                            <div id="news-content-editor" data-upload-url="{{ route('admin.news.editor-images.store') }}" class="bg-white"></div>
                        </div>
                        @error('content')
                            <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Kategori</label>
                            <input type="text" name="category" value="{{ old('category', $news->category ?? 'Berita') }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Berita / Pengumuman / dll">
                            @error('category')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Tanggal Publish</label>
                            <input type="date" name="published_at" value="{{ old('published_at', $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('Y-m-d') : '') }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium">
                            @error('published_at')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-[28px] border border-slate-200 bg-slate-50/70 p-6 space-y-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-slate-800">Gambar Cover</div>
                                <div class="text-xs text-slate-500 mt-1">Upload file atau isi URL. Upload akan otomatis dikompres.</div>
                            </div>
                            @php
                                $coverUrl = $news->image;
                                if (is_string($coverUrl) && $coverUrl !== '' && !str_starts_with($coverUrl, 'http') && !str_starts_with($coverUrl, '/storage/')) {
                                    $coverUrl = asset('storage/' . ltrim($coverUrl, '/'));
                                }
                            @endphp
                            @if($coverUrl)
                                <div class="h-14 w-14 rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm flex items-center justify-center">
                                    <img src="{{ str_starts_with($coverUrl, 'http') ? $coverUrl : asset(ltrim($coverUrl, '/')) }}" alt="Cover" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                    <div class="h-full w-full items-center justify-center text-slate-300 hidden"><i data-lucide="image" class="h-6 w-6"></i></div>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Upload</label>
                                <input type="file" name="image_file" accept="image/*" class="w-full px-5 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none text-sm">
                                @error('image_file')
                                    <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">URL Gambar</label>
                                <input type="text" name="image" value="{{ old('image', $news->image) }}" class="w-full px-5 py-3.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="https://... atau /storage/...">
                                @error('image')
                                    <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-2">
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="is_published" value="0">
                            <label class="inline-flex items-center gap-2 text-sm font-bold text-slate-700">
                                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $news->is_published) ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                Publish
                            </label>
                            @error('is_published')
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

@vite('resources/js/admin-news-editor.js')

<script>
(() => {
    const title = document.getElementById('news-title');
    const slug = document.getElementById('news-slug');
    if (!title || !slug) return;

    const slugify = (value) =>
        (value || '')
            .toString()
            .trim()
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');

    const existing = slug.value;
    if (existing) return;

    title.addEventListener('input', () => {
        if (slug.value) return;
        slug.value = slugify(title.value);
    });
})();
</script>
@endsection
