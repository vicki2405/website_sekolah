@extends('admin.layout')

@section('title', 'Home')
@section('breadcrumb', 'Home Management')
@section('page-title', 'Manajemen Halaman Utama')

@section('content')
@php($pageType = $page['type'] ?? null)
@php($pageAction = $page['action'] ?? null)
<div class="space-y-6">

    @if($pageType === 'slider')
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <div>
                    <h4 class="font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="image" class="h-5 w-5 text-primary-600"></i>
                        {{ $pageAction === 'edit' ? 'Edit Slider' : 'Tambah Slider' }}
                    </h4>
                    <p class="text-xs text-slate-400 mt-1">Kelola gambar slider beranda</p>
                </div>
                <a href="{{ route('admin.home.index', ['tab' => 'sliders']) }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition-all">Kembali</a>
            </div>
            <div class="p-6">
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in items-center mb-6">
                        <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
                        <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                    </div>
                @endif
                @if($errors->any())
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex gap-3 animate-fade-in mb-6">
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
                <form method="POST" enctype="multipart/form-data" action="{{ $pageAction === 'edit' ? route('admin.home.sliders.update', $slider) : route('admin.home.sliders.store') }}" class="space-y-6">
                    @csrf
                    @if($pageAction === 'edit')
                        @method('PUT')
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Judul Slider</label>
                            <input name="title" value="{{ old('title', $slider->title ?? '') }}" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Urutan</label>
                            <input type="number" name="order" value="{{ old('order', $slider->order ?? 0) }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Sub Judul (Deskripsi Singkat)</label>
                        <textarea name="subtitle" rows="4" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium resize-none">{{ old('subtitle', $slider->subtitle ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Status</label>
                        <select name="is_active" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium appearance-none">
                            @php($currentActive = old('is_active', isset($slider) ? ((int)($slider->is_active ?? false)) : 1))
                            <option value="1" {{ (string)$currentActive === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ (string)$currentActive === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Gambar Slider</label>
                        @if($pageAction === 'edit' && ($slider->image ?? null))
                            <div class="mb-4 rounded-2xl overflow-hidden border border-slate-200 aspect-video bg-slate-100 flex items-center justify-center">
                                <img src="{{ str_starts_with($slider->image, 'http') ? $slider->image : asset(ltrim($slider->image, '/')) }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                <div class="h-full w-full items-center justify-center text-slate-300 hidden">
                                    <i data-lucide="image" class="h-8 w-8"></i>
                                </div>
                            </div>
                        @endif
                        <input type="file" name="image_file" {{ $pageAction === 'edit' ? '' : 'required' }} class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                        <p class="text-[10px] text-slate-400 mt-2 ml-1 uppercase tracking-widest">JPG, PNG atau WebP (Maks. 5MB)</p>
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-3">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-primary-600 text-white font-bold text-sm hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all active:scale-[0.98]">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @elseif($pageType === 'partner')
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <div>
                    <h4 class="font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="handshake" class="h-5 w-5 text-primary-600"></i>
                        {{ $pageAction === 'edit' ? 'Edit Mitra' : 'Tambah Mitra' }}
                    </h4>
                    <p class="text-xs text-slate-400 mt-1">Kelola logo mitra</p>
                </div>
                <a href="{{ route('admin.home.index', ['tab' => 'partners']) }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition-all">Kembali</a>
            </div>
            <div class="p-6">
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in items-center mb-6">
                        <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
                        <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                    </div>
                @endif
                @if($errors->any())
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex gap-3 animate-fade-in mb-6">
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
                <form method="POST" enctype="multipart/form-data" action="{{ $pageAction === 'edit' ? route('admin.home.partners.update', $partner) : route('admin.home.partners.store') }}" class="space-y-6">
                    @csrf
                    @if($pageAction === 'edit')
                        @method('PUT')
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Nama Mitra</label>
                            <input name="name" value="{{ old('name', $partner->name ?? '') }}" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Kategori</label>
                            <input name="category" value="{{ old('category', $partner->category ?? '') }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Link Website (Optional)</label>
                        <input type="url" name="link" value="{{ old('link', $partner->link ?? '') }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium" placeholder="https://...">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Urutan (Optional)</label>
                        <input type="number" name="order" value="{{ old('order', $partner->order ?? 0) }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Logo Mitra</label>
                        @if($pageAction === 'edit' && ($partner->logo ?? null))
                            <div class="mb-4 rounded-2xl border border-slate-200 p-4 bg-slate-50 h-32 flex items-center justify-center">
                                <img src="{{ str_starts_with($partner->logo, 'http') ? $partner->logo : asset(ltrim($partner->logo, '/')) }}" class="max-h-full max-w-full object-contain" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                <div class="h-full w-full items-center justify-center text-slate-300 hidden">
                                    <i data-lucide="handshake" class="h-8 w-8"></i>
                                </div>
                            </div>
                        @endif
                        <input type="file" name="logo_file" {{ $pageAction === 'edit' ? '' : 'required' }} class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                        <p class="text-[10px] text-slate-400 mt-2 ml-1 uppercase tracking-widest">JPG, PNG atau WebP (Maks. 2MB)</p>
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-3">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-primary-600 text-white font-bold text-sm hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all active:scale-[0.98]">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @elseif($pageType === 'testimonial')
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <div>
                    <h4 class="font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="quote" class="h-5 w-5 text-primary-600"></i>
                        {{ $pageAction === 'edit' ? 'Edit Testimoni' : 'Tambah Testimoni' }}
                    </h4>
                    <p class="text-xs text-slate-400 mt-1">Kelola testimoni alumni</p>
                </div>
                <a href="{{ route('admin.home.index', ['tab' => 'testimonials']) }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition-all">Kembali</a>
            </div>
            <div class="p-6">
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in items-center mb-6">
                        <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
                        <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                    </div>
                @endif
                @if($errors->any())
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex gap-3 animate-fade-in mb-6">
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
                <form method="POST" enctype="multipart/form-data" action="{{ $pageAction === 'edit' ? route('admin.home.testimonials.update', $testimonial) : route('admin.home.testimonials.store') }}" class="space-y-6">
                    @csrf
                    @if($pageAction === 'edit')
                        @method('PUT')
                    @endif
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Nama Alumni</label>
                        <input name="name" value="{{ old('name', $testimonial->name ?? '') }}" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Tahun Lulus</label>
                            <input name="graduation_year" value="{{ old('graduation_year', $testimonial->graduation_year ?? '') }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Instansi</label>
                            <input name="company" value="{{ old('company', $testimonial->company ?? '') }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Posisi</label>
                        <input name="position" value="{{ old('position', $testimonial->position ?? '') }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Pesan</label>
                        <textarea name="message" rows="5" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium resize-none">{{ old('message', $testimonial->message ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Foto (Optional)</label>
                        @if($pageAction === 'edit' && ($testimonial->photo ?? null))
                            <div class="mb-4 h-24 w-24 rounded-full overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center">
                                <img src="{{ str_starts_with($testimonial->photo, 'http') ? $testimonial->photo : asset(ltrim($testimonial->photo, '/')) }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                <div class="h-full w-full items-center justify-center text-slate-400 hidden">
                                    <i data-lucide="user" class="h-8 w-8"></i>
                                </div>
                            </div>
                        @endif
                        <input type="file" name="photo_file" class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                        <p class="text-[10px] text-slate-400 mt-2 ml-1 uppercase tracking-widest">JPG, PNG atau WebP (Maks. 2MB)</p>
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-3">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-primary-600 text-white font-bold text-sm hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all active:scale-[0.98]">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
    <!-- Stats / Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="image" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sliders</p>
                <p class="text-2xl font-display font-bold text-slate-900">{{ count($sliders ?? []) }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="briefcase" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keahlian</p>
                <p class="text-2xl font-display font-bold text-slate-900">{{ count($programs ?? []) }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="handshake" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mitra</p>
                <p class="text-2xl font-display font-bold text-slate-900">{{ count($partners ?? []) }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="quote" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Testimoni</p>
                <p class="text-2xl font-display font-bold text-slate-900">{{ count($testimonials ?? []) }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in items-center">
            <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

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

    <!-- Tabs Navigation -->
    <div class="flex p-1.5 bg-slate-200/50 rounded-2xl w-fit backdrop-blur-md overflow-x-auto custom-scrollbar">
        <button type="button" data-tab-btn="sliders" class="flex items-center gap-2 px-6 py-3 text-sm font-bold rounded-xl transition-all duration-300 shrink-0">
            <i data-lucide="image" class="h-4 w-4"></i>
            Sliders
        </button>
        <button type="button" data-tab-btn="partners" class="flex items-center gap-2 px-6 py-3 text-sm font-bold rounded-xl transition-all duration-300 shrink-0">
            <i data-lucide="handshake" class="h-4 w-4"></i>
            Mitra
        </button>
        <button type="button" data-tab-btn="testimonials" class="flex items-center gap-2 px-6 py-3 text-sm font-bold rounded-xl transition-all duration-300 shrink-0">
            <i data-lucide="quote" class="h-4 w-4"></i>
            Testimoni
        </button>
    </div>

    <!-- TAB: SLIDERS -->
    <div id="tab-sliders" class="tab-pane animate-fade-in hidden">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <h4 class="font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="image" class="h-5 w-5 text-primary-600"></i>
                    Daftar Sliders
                </h4>
                <a href="{{ route('admin.home.sliders.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-xl font-bold text-xs hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all active:scale-95 group">
                    <i data-lucide="plus" class="h-4 w-4 group-hover:rotate-90 transition-transform duration-300"></i>
                    Tambah Slider
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Gambar</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Teks</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Urutan</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($sliders as $slider)
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-4">
                                <div class="h-20 w-36 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                    <img src="{{ str_starts_with($slider->image, 'http') ? $slider->image : asset(ltrim($slider->image, '/')) }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                    <div class="h-full w-full items-center justify-center text-slate-300 hidden">
                                        <i data-lucide="image" class="h-8 w-8"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $slider->title }}</p>
                                <p class="text-xs text-slate-400 mt-1 line-clamp-1">{{ $slider->subtitle }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">{{ $slider->order }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($slider->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider">Aktif</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400 border border-slate-200 uppercase tracking-wider">Off</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.home.sliders.edit', $slider) }}" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit Slider">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.home.sliders.destroy', $slider) }}" method="POST" class="inline" onsubmit="return confirm('Hapus slider ini?')">
                                        @csrf @method('DELETE')
                                        <button class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Slider">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB: PARTNERS -->
    <div id="tab-partners" class="tab-pane animate-fade-in hidden">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <h4 class="font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="handshake" class="h-5 w-5 text-primary-600"></i>
                    Mitra & Kolaborasi Strategis
                </h4>
                <a href="{{ route('admin.home.partners.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-xl font-bold text-xs hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all active:scale-95 group">
                    <i data-lucide="plus" class="h-4 w-4 group-hover:rotate-90 transition-transform duration-300"></i>
                    Tambah Mitra
                </a>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($partners as $partner)
                <div class="bg-slate-50/50 rounded-2xl p-6 border border-slate-100 group relative">
                    <div class="h-16 flex items-center justify-center mb-4">
                        <img src="{{ str_starts_with($partner->logo, 'http') ? $partner->logo : asset(ltrim($partner->logo, '/')) }}" class="max-h-full max-w-full object-contain transition-all duration-300" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                        <div class="h-full w-full items-center justify-center text-slate-300 hidden">
                            <i data-lucide="handshake" class="h-8 w-8"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="font-bold text-slate-900 text-sm">{{ $partner->name }}</p>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest mt-1">{{ $partner->category ?: 'Mitra Industri' }}</p>
                    </div>
                    <div class="absolute top-4 right-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.home.partners.edit', $partner) }}" class="p-1.5 bg-white shadow-sm rounded-lg text-primary-600 hover:text-primary-700 transition-colors"><i data-lucide="edit-3" class="h-3.5 w-3.5"></i></a>
                        <form action="{{ route('admin.home.partners.destroy', $partner) }}" method="POST" class="inline" onsubmit="return confirm('Hapus mitra ini?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 bg-white shadow-sm rounded-lg text-red-400 hover:text-red-600 transition-colors"><i data-lucide="trash-2" class="h-3.5 w-3.5"></i></button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- TAB: TESTIMONIALS -->
    <div id="tab-testimonials" class="tab-pane animate-fade-in hidden">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <h4 class="font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="quote" class="h-5 w-5 text-primary-600"></i>
                    Testimoni Alumni
                </h4>
                <a href="{{ route('admin.home.testimonials.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-xl font-bold text-xs hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all active:scale-95 group">
                    <i data-lucide="plus" class="h-4 w-4 group-hover:rotate-90 transition-transform duration-300"></i>
                    Tambah Testimoni
                </a>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($testimonials as $testi)
                <div class="bg-slate-50/50 rounded-2xl p-6 border border-slate-100 flex flex-col group relative">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-12 w-12 rounded-full overflow-hidden border-2 border-white shadow-sm bg-slate-200 flex items-center justify-center">
                            @if($testi->photo)
                                <img src="{{ str_starts_with($testi->photo, 'http') ? $testi->photo : asset(ltrim($testi->photo, '/')) }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                <div class="h-full w-full items-center justify-center text-slate-400 hidden"><i data-lucide="user" class="h-5 w-5"></i></div>
                            @else
                                <div class="h-full w-full flex items-center justify-center text-slate-400"><i data-lucide="user" class="h-5 w-5"></i></div>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-sm">{{ $testi->name }}</p>
                            <p class="text-[10px] text-slate-400 uppercase tracking-tight">Angkatan {{ $testi->graduation_year ?: '-' }}</p>
                        </div>
                    </div>
                    <div class="relative">
                        <i data-lucide="quote" class="h-8 w-8 text-primary-100 absolute -top-2 -left-2 -z-10"></i>
                        <p class="text-xs text-slate-600 italic leading-relaxed line-clamp-3">"{{ $testi->message }}"</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <p class="text-[10px] font-bold text-slate-900">{{ $testi->company ?: '-' }}</p>
                        <p class="text-[9px] text-slate-400">{{ $testi->position ?: '-' }}</p>
                    </div>
                    <div class="absolute top-4 right-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.home.testimonials.edit', $testi) }}" class="p-1.5 bg-white shadow-sm rounded-lg text-primary-600 hover:text-primary-700 transition-colors"><i data-lucide="edit-3" class="h-3.5 w-3.5"></i></a>
                        <form action="{{ route('admin.home.testimonials.destroy', $testi) }}" method="POST" class="inline" onsubmit="return confirm('Hapus testimoni ini?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 bg-white shadow-sm rounded-lg text-red-400 hover:text-red-600 transition-colors"><i data-lucide="trash-2" class="h-3.5 w-3.5"></i></button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@endif
</div>

<style>
    .tab-btn-active {
        background-color: white;
        box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.05);
        color: #0f172a;
    }
    .tab-btn-inactive {
        color: #64748b;
    }
</style>

<script>
    function initTabs() {
        const tabBtns = document.querySelectorAll('[data-tab-btn]');
        const tabPanes = document.querySelectorAll('.tab-pane');
        if (tabBtns.length === 0 || tabPanes.length === 0) return;

        const params = new URLSearchParams(window.location.search);
        const queryTab = params.get('tab');
        const requested = queryTab || localStorage.getItem('admin_home_tab') || 'sliders';
        const availableTabs = Array.from(tabBtns).map(b => b.getAttribute('data-tab-btn')).filter(Boolean);
        let activeTab = availableTabs.includes(requested) ? requested : (availableTabs[0] || 'sliders');

        function switchTab(tabId) {
            tabBtns.forEach(btn => {
                const id = btn.getAttribute('data-tab-btn');
                if (id === tabId) {
                    btn.classList.add('tab-btn-active');
                    btn.classList.remove('tab-btn-inactive');
                } else {
                    btn.classList.remove('tab-btn-active');
                    btn.classList.add('tab-btn-inactive');
                }
            });

            tabPanes.forEach(pane => {
                if (pane.id === `tab-${tabId}`) {
                    pane.classList.remove('hidden');
                } else {
                    pane.classList.add('hidden');
                }
            });

            localStorage.setItem('admin_home_tab', tabId);
        }

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => switchTab(btn.getAttribute('data-tab-btn')));
        });

        switchTab(activeTab);
    }

    document.addEventListener('DOMContentLoaded', () => {
        initTabs();
    });
</script>
@endsection
