@extends('admin.layout')

@section('title', 'Prestasi')
@section('breadcrumb', 'Prestasi')
@section('page-title', 'Manajemen Prestasi')

@section('content')
@php
    $pageType = $page['type'] ?? null;
    $pageAction = $page['action'] ?? null;
@endphp
<div class="animate__animated animate__fadeInUp">
    @if($pageType === 'achievement')
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('admin.prestasi.index') }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-primary-600 hover:border-primary-100 transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">{{ $pageAction === 'edit' ? 'Edit Prestasi' : 'Tambah Prestasi' }}</h2>
                    <p class="text-slate-500 text-sm">Kelola item prestasi yang tampil di halaman /prestasi.</p>
                </div>
            </div>

            <div class="admin-card overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-amber-500"></i>
                        Form Prestasi
                    </h3>
                </div>
                <div class="p-8">
                    <form action="{{ $pageAction === 'edit' ? route('admin.prestasi.update', $achievement) : route('admin.prestasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @if($pageAction === 'edit')
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Judul</label>
                                <input type="text" name="title" value="{{ old('title', $achievement->title) }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Cth: Juara 1 LKS Nasional">
                                @error('title')
                                    <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nilai</label>
                                <input type="text" name="value" value="{{ old('value', $achievement->value) }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Cth: 100+ / Juara 1 / 2026">
                                @error('value')
                                    <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Ikon</label>
                                <select name="icon" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium appearance-none">
                                    @php $currentIcon = old('icon', $achievement->icon); @endphp
                                    <option value="" {{ $currentIcon ? '' : 'selected' }}>Default (Sparkles)</option>
                                    <option value="trophy" {{ $currentIcon === 'trophy' ? 'selected' : '' }}>Trophy</option>
                                    <option value="award" {{ $currentIcon === 'award' ? 'selected' : '' }}>Award</option>
                                    <option value="graduation-cap" {{ $currentIcon === 'graduation-cap' ? 'selected' : '' }}>Graduation Cap</option>
                                    <option value="school" {{ $currentIcon === 'school' ? 'selected' : '' }}>School</option>
                                    <option value="users" {{ $currentIcon === 'users' ? 'selected' : '' }}>Users</option>
                                    <option value="check-circle" {{ $currentIcon === 'check-circle' ? 'selected' : '' }}>Check Circle</option>
                                </select>
                                @error('icon')
                                    <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Urutan</label>
                                <input type="number" name="order" value="{{ old('order', $achievement->order ?? 0) }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="1">
                                @error('order')
                                    <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="rounded-[28px] border border-slate-200 bg-slate-50/70 p-6 space-y-5">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-bold text-slate-800">Gambar (Opsional)</div>
                                    <div class="text-xs text-slate-500 mt-1">Upload gambar untuk kartu prestasi. Akan otomatis dikompres.</div>
                                </div>
                                @if($pageAction === 'edit' && ($achievement->image ?? null))
                                    <div class="h-14 w-14 rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm flex items-center justify-center">
                                        <img src="{{ str_starts_with($achievement->image, 'http') ? $achievement->image : asset(ltrim($achievement->image, '/')) }}" alt="Gambar Prestasi" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                        <div class="h-full w-full items-center justify-center text-slate-300 hidden">
                                            <i data-lucide="trophy" class="h-6 w-6"></i>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Upload</label>
                                <input type="file" name="image_file" accept="image/*" class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                                <p class="text-[10px] text-slate-400 mt-2 ml-1 uppercase tracking-widest">JPG, PNG atau WebP (Maks. 5MB)</p>
                                @error('image_file')
                                    <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Deskripsi</label>
                            <textarea name="description" rows="4" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Jelaskan mengenai prestasi ini...">{{ old('description', $achievement->description) }}</textarea>
                            @error('description')
                                <div class="text-xs font-semibold text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between gap-4 pt-2">
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="is_active" value="0">
                                <label class="inline-flex items-center gap-2 text-sm font-bold text-slate-700">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $achievement->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                    Aktif
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
    @else
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Manajemen Prestasi</h2>
                <p class="text-slate-500 text-sm">Atur konten halaman /prestasi dan daftar item prestasi.</p>
            </div>
            <a href="{{ route('admin.prestasi.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i>
                Tambah Prestasi Baru
            </a>
        </div>

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

        @php $showPrestasiSettings = $errors->hasAny(['hero_badge', 'hero_title', 'hero_description']); @endphp
        <div class="admin-card overflow-hidden mb-8">
            <button
                type="button"
                id="prestasi-settings-toggle"
                class="w-full p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between gap-4 text-left hover:bg-slate-100/70 transition-colors"
                aria-expanded="{{ $showPrestasiSettings ? 'true' : 'false' }}"
                aria-controls="prestasi-settings-panel"
            >
                <span class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-primary-600"></i>
                    Pengaturan Halaman Prestasi
                </span>
                <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                    <span>{{ $showPrestasiSettings ? 'Sembunyikan' : 'Tampilkan' }}</span>
                    <i
                        id="prestasi-settings-chevron"
                        class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 {{ $showPrestasiSettings ? 'rotate-180' : '' }}"
                        aria-hidden="true"
                    ></i>
                </span>
            </button>
            <div id="prestasi-settings-panel" class="{{ $showPrestasiSettings ? '' : 'hidden' }}">
                <div class="p-8">
                @php
                    $statsData = [];
                    if (!empty($pageSettings['prestasi.stats'])) {
                        $statsData = json_decode($pageSettings['prestasi.stats'], true) ?: [];
                    }
                    if (empty($statsData)) {
                        $statsData = [
                            ['value' => '300+', 'label' => 'Koleksi Juara', 'sublabel' => 'Prestasi & Trofi', 'icon' => 'trophy', 'color' => 'amber'],
                            ['value' => 'Nasional', 'label' => 'Ajang Nasional', 'sublabel' => 'Kejuaraan Bergengsi', 'icon' => 'medal', 'color' => 'brand'],
                            ['value' => 'Provinsi', 'label' => 'Tingkat Jatim', 'sublabel' => 'Medali Emas & Perak', 'icon' => 'award', 'color' => 'emerald'],
                            ['value' => 'Daerah', 'label' => 'Juara Umum', 'sublabel' => 'Lomba Kab. Banyuwangi', 'icon' => 'flame', 'color' => 'purple'],
                        ];
                    }
                @endphp
                <form action="{{ route('admin.prestasi.settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Badge Header</label>
                            <input type="text" name="hero_badge" value="{{ old('hero_badge', $pageSettings['prestasi.hero_badge'] ?? 'Prestasi & Pencapaian') }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Judul Header</label>
                            <input type="text" name="hero_title" value="{{ old('hero_title', $pageSettings['prestasi.hero_title'] ?? 'Prestasi Sekolah') }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Deskripsi Header</label>
                        <textarea name="hero_description" rows="3" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium">{{ old('hero_description', $pageSettings['prestasi.hero_description'] ?? 'Ringkasan pencapaian yang mencerminkan kualitas pembelajaran, budaya prestasi, dan perkembangan sekolah dari waktu ke waktu.') }}</textarea>
                    </div>

                    <!-- Repeater Kotak Statistik -->
                    <div class="border-t border-slate-100 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                    <i class="fa-solid fa-chart-simple text-amber-500"></i>
                                    Kotak Statistik / Highlight Prestasi
                                </h4>
                                <p class="text-xs text-slate-500 mt-0.5">Atur angka, label, dan ikon untuk kartu highlight prestasi di bagian atas halaman publik.</p>
                            </div>
                            <button type="button" onclick="addPrestasiStatRow()" class="px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold text-xs rounded-xl border border-amber-200 transition-all flex items-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                Tambah Statistik
                            </button>
                        </div>

                        <div id="prestasi-stats-container" class="space-y-4">
                            @foreach($statsData as $index => $stat)
                            <div class="stat-row p-4 rounded-2xl bg-slate-50 border border-slate-200 relative group transition-all">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                                    <div class="lg:col-span-2 space-y-1">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Angka / Nilai</label>
                                        <input type="text" name="stats[{{ $index }}][value]" value="{{ $stat['value'] ?? '' }}" placeholder="Cth: 300+ / Juara 1" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                    </div>
                                    <div class="lg:col-span-3 space-y-1">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Label Utama</label>
                                        <input type="text" name="stats[{{ $index }}][label]" value="{{ $stat['label'] ?? '' }}" placeholder="Cth: Koleksi Juara" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                    </div>
                                    <div class="lg:col-span-3 space-y-1">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sublabel / Keterangan</label>
                                        <input type="text" name="stats[{{ $index }}][sublabel]" value="{{ $stat['sublabel'] ?? '' }}" placeholder="Cth: Prestasi & Trofi" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-600 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                    </div>
                                    <div class="lg:col-span-2 space-y-1">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ikon</label>
                                        <select name="stats[{{ $index }}][icon]" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                            @php $curIco = $stat['icon'] ?? 'trophy'; @endphp
                                            <option value="trophy" {{ $curIco === 'trophy' ? 'selected' : '' }}>Trophy</option>
                                            <option value="medal" {{ $curIco === 'medal' ? 'selected' : '' }}>Medal</option>
                                            <option value="award" {{ $curIco === 'award' ? 'selected' : '' }}>Award</option>
                                            <option value="flame" {{ $curIco === 'flame' ? 'selected' : '' }}>Flame (Api)</option>
                                            <option value="star" {{ $curIco === 'star' ? 'selected' : '' }}>Star</option>
                                            <option value="sparkles" {{ $curIco === 'sparkles' ? 'selected' : '' }}>Sparkles</option>
                                            <option value="school" {{ $curIco === 'school' ? 'selected' : '' }}>School</option>
                                            <option value="users" {{ $curIco === 'users' ? 'selected' : '' }}>Users</option>
                                        </select>
                                    </div>
                                    <div class="lg:col-span-2 flex items-center justify-end">
                                        <button type="button" onclick="this.closest('.stat-row').remove()" class="w-full py-2.5 px-3 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs border border-red-200 transition-colors flex items-center justify-center gap-1">
                                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>

    <div class="admin-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-trophy text-amber-500"></i>
                Daftar Prestasi
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Prestasi</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Urutan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($achievements as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100 shrink-0">
                                    <i class="fa-solid fa-award text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm group-hover:text-primary-600 transition-colors line-clamp-1">{{ $item->title }}</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit(strip_tags($item->description), 60) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->is_active ?? true)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-400 border border-slate-200 uppercase tracking-wider">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold text-slate-500">{{ $item->order ?? 0 }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.prestasi.edit', $item) }}" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit Prestasi">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('admin.prestasi.destroy', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Prestasi" onclick="return confirm('Apakah Anda yakin ingin menghapus prestasi ini?')">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                    <i class="fa-solid fa-trophy text-3xl"></i>
                                </div>
                                <p class="text-slate-400 text-sm font-medium italic">Belum ada data prestasi yang ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('prestasi-settings-toggle');
        const panel = document.getElementById('prestasi-settings-panel');
        const chevron = document.getElementById('prestasi-settings-chevron');

        if (!toggle || !panel || !chevron) {
            return;
        }

        const label = toggle.querySelector('span:last-child span');

        function syncPrestasiSettingsState(isOpen) {
            panel.classList.toggle('hidden', !isOpen);
            chevron.classList.toggle('rotate-180', isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

            if (label) {
                label.textContent = isOpen ? 'Sembunyikan' : 'Tampilkan';
            }
        }

        syncPrestasiSettingsState(toggle.getAttribute('aria-expanded') === 'true');

        toggle.addEventListener('click', function () {
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            syncPrestasiSettingsState(!isOpen);
        });
    });

    function addPrestasiStatRow() {
        const container = document.getElementById('prestasi-stats-container');
        if (!container) return;

        const index = Date.now();
        const row = document.createElement('div');
        row.className = 'stat-row p-4 rounded-2xl bg-slate-50 border border-slate-200 relative group transition-all animate-fade-in';
        row.innerHTML = `
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                <div class="lg:col-span-2 space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Angka / Nilai</label>
                    <input type="text" name="stats[${index}][value]" placeholder="Cth: 300+ / Juara 1" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                </div>
                <div class="lg:col-span-3 space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Label Utama</label>
                    <input type="text" name="stats[${index}][label]" placeholder="Cth: Koleksi Juara" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                </div>
                <div class="lg:col-span-3 space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sublabel / Keterangan</label>
                    <input type="text" name="stats[${index}][sublabel]" placeholder="Cth: Prestasi & Trofi" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-600 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                </div>
                <div class="lg:col-span-2 space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ikon</label>
                    <select name="stats[${index}][icon]" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                        <option value="trophy">Trophy</option>
                        <option value="medal">Medal</option>
                        <option value="award">Award</option>
                        <option value="flame">Flame (Api)</option>
                        <option value="star">Star</option>
                        <option value="sparkles">Sparkles</option>
                        <option value="school">School</option>
                        <option value="users">Users</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex items-center justify-end">
                    <button type="button" onclick="this.closest('.stat-row').remove()" class="w-full py-2.5 px-3 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs border border-red-200 transition-colors flex items-center justify-center gap-1">
                        <i class="fa-solid fa-trash-can text-[11px]"></i>
                        Hapus
                    </button>
                </div>
            </div>
        `;
        container.appendChild(row);
    }
</script>
@endpush
