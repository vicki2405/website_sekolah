@extends('admin.layout')

@section('title', 'Tambah Fasilitas')
@section('breadcrumb', 'Fasilitas / Tambah')
@section('page-title', 'Tambah Fasilitas')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex gap-3 animate-fade-in">
            <i data-lucide="alert-circle" class="text-red-500 h-5 w-5 shrink-0"></i>
            <div>
                <p class="text-sm font-bold text-red-800">Gagal menyimpan data!</p>
                <ul class="mt-1 text-xs text-red-600 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <div class="flex items-center gap-2">
                <i data-lucide="sparkles" class="h-5 w-5 text-primary-600"></i>
                <h3 class="font-bold text-slate-800">Form Tambah Fasilitas</h3>
            </div>
            <a href="{{ route('admin.facilities.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-white/60 hover:text-slate-900 transition-all border border-transparent hover:border-slate-100">
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('admin.facilities.store') }}" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Judul *</label>
                <input name="title" value="{{ old('title') }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700" placeholder="Cth: Laboratorium Komputer">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700 resize-none" placeholder="Deskripsi singkat fasilitas...">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Kategori</label>
                    <select name="category" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700">
                        @forelse($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @empty
                            <option value="Umum">Umum</option>
                        @endforelse
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Icon</label>
                    <select name="icon" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700">
                        <option value="">Tanpa Icon</option>
                        <option value="monitor-play">Monitor Play</option>
                        <option value="wrench">Wrench</option>
                        <option value="chef-hat">Chef Hat</option>
                        <option value="book-open">Book Open</option>
                        <option value="dribbble">Dribbble</option>
                        <option value="book-heart">Book Heart</option>
                        <option value="sparkles">Sparkles</option>
                    </select>
                </div>
            </div>

            <!-- Main Image -->
            <div class="rounded-[28px] border border-slate-200 bg-slate-50/70 p-6 space-y-4">
                <div class="text-sm font-bold text-slate-800">Gambar Utama</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Upload File</label>
                        <input type="file" name="image_file" accept="image/*" class="mt-1 block w-full text-sm file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">URL Gambar</label>
                        <input type="text" name="image" value="{{ old('image') }}" class="mt-1 w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 text-sm" placeholder="https://... atau /storage/...">
                    </div>
                </div>
            </div>

            <!-- Galeri Foto -->
            <div class="rounded-[28px] border border-slate-200 bg-slate-50/70 p-6 space-y-4">
                <div>
                    <div class="text-sm font-bold text-slate-800">Galeri Foto</div>
                    <div class="text-xs text-slate-500 mt-1">Upload banyak foto sekaligus untuk ditampilkan di halaman fasilitas.</div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Tambah Foto</label>
                    <input type="file" name="gallery_files[]" accept="image/*" multiple id="gallery_files" class="mt-1 block w-full text-sm file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                    <p class="text-[11px] text-slate-400 mt-1">Bisa pilih banyak foto sekaligus.</p>
                    @error('gallery_files.*')
                        <div class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div id="gallery_preview" class="hidden">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Preview Foto</p>
                    <div id="gallery_preview_grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Urutan</label>
                    <input name="order" type="number" value="{{ old('order', 0) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700">
                </div>
                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 text-sm font-bold text-slate-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        Tampilkan di website
                    </label>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <a href="{{ route('admin.facilities.index') }}" class="px-6 py-3 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-all">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-2xl font-bold text-sm hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all active:scale-95">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                        wrapper.className = 'rounded-xl overflow-hidden border border-slate-200 bg-white';
                        const media = document.createElement('div');
                        media.className = 'aspect-[4/3] bg-slate-100';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-full object-cover';
                        media.appendChild(img);

                        const content = document.createElement('div');
                        content.className = 'p-1.5 bg-white';

                        const input = document.createElement('input');
                        input.type = 'text';
                        input.name = 'new_gallery_captions[]';
                        input.maxLength = 255;
                        input.placeholder = 'Caption';
                        input.className = 'w-full text-[11px] px-1.5 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary-500/20 focus:border-primary-500 text-slate-600';

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

