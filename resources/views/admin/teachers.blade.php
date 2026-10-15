@extends('admin.layout')

@section('title', 'Guru')
@section('breadcrumb', 'Guru & Staff')
@section('page-title', 'Manajemen Guru')

@section('content')
@php
    $orgFlat = $orgFlat ?? [];
    $teachersList = $teachers instanceof \Illuminate\Support\Collection ? $teachers : collect($teachers ?? []);
    $categoriesList = $categories instanceof \Illuminate\Support\Collection ? $categories : collect($categories ?? []);
    $allCategoryNames = $categoriesList->pluck('name')->toArray();

    // Group teachers by known categories
    $teachersGrouped = $teachersList->groupBy(function ($item) use ($allCategoryNames) {
        $cat = trim((string)($item->category ?? ''));
        return in_array($cat, $allCategoryNames) ? $cat : '__uncategorized__';
    });

    $uncategorizedTeachers = $teachersGrouped->get('__uncategorized__', collect());

    $nodePayload = !empty($orgFlat) ? collect($orgFlat)->map(fn ($row) => [
        'id' => $row['node']->id,
        'title' => $row['node']->title,
        'parent_id' => $row['node']->parent_id,
        'order' => $row['node']->order ?? 0,
        'is_active' => (bool) ($row['node']->is_active ?? false),
    ])->values() : collect();

    $orgNodeUpdateUrlTemplate = route('admin.teachers.org.nodes.update', ['node' => '__ID__']);
@endphp

<div class="space-y-6">

    <!-- Stats / Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="users" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Guru</p>
                <p class="text-2xl font-display font-bold text-slate-900">{{ $teachersList->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="check-circle" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Guru Aktif</p>
                <p class="text-2xl font-display font-bold text-slate-900">{{ $teachersList->where('is_active', true)->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="git-branch" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Struktur Node</p>
                <p class="text-2xl font-display font-bold text-slate-900">{{ count($orgFlat) }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate-fade-in shadow-sm">
            <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex items-center gap-3 animate-fade-in">
            <i data-lucide="alert-triangle" class="text-red-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
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
    <div class="flex p-1.5 bg-slate-200/50 rounded-2xl w-fit backdrop-blur-md">
        <a href="{{ route('admin.teacher-categories.index') }}" class="flex items-center gap-2 px-8 py-3 text-sm font-bold rounded-xl transition-all duration-300 text-slate-500 hover:bg-white hover:text-primary-600">
            <i data-lucide="tags" class="h-4 w-4"></i>
            Kategori Guru
        </a>
        <button type="button" data-tab-btn="teachers" class="flex items-center gap-2 px-8 py-3 text-sm font-bold rounded-xl transition-all duration-300">
            <i data-lucide="users" class="h-4 w-4"></i>
            Data Guru
        </button>
        <button type="button" data-tab-btn="org" class="flex items-center gap-2 px-8 py-3 text-sm font-bold rounded-xl transition-all duration-300">
            <i data-lucide="git-branch" class="h-4 w-4"></i>
            Struktur Organisasi
        </button>
    </div>

    <!-- TAB: DATA GURU -->
    <div id="tab-teachers" class="tab-pane animate-fade-in space-y-6">
        
        <!-- Controls & Category Filter Toolbar -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative group">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                        <input id="teacherSearch" type="text" class="pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all w-72 shadow-sm text-slate-700" placeholder="Cari guru, email, jabatan...">
                    </div>
                    <select id="teacherStatus" class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none shadow-sm cursor-pointer text-slate-700 font-medium">
                        <option value="all">Semua Status</option>
                        <option value="active">Status: Aktif</option>
                        <option value="inactive">Status: Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.teacher-categories.index') }}" class="flex items-center gap-2 px-5 py-3 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl font-bold text-sm border border-slate-200 transition-all active:scale-95 shadow-sm">
                        <i data-lucide="tags" class="h-4 w-4 text-slate-500"></i>
                        Atur Kategori
                    </a>
                    <a href="{{ route('admin.teachers.create') }}" class="flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all active:scale-95 group">
                        <i data-lucide="plus" class="h-5 w-5 group-hover:rotate-90 transition-transform duration-300"></i>
                        Tambah Guru
                    </a>
                </div>
            </div>

            <!-- Quick Filter / Category Navigation Pills -->
            <div class="mt-6 pt-6 border-t border-slate-100">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        <i data-lucide="layers" class="h-4 w-4 text-primary-600"></i>
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Kelompok Kategori:</span>
                    </div>
                    <span class="text-xs text-slate-400">Pilih kategori untuk memfilter tampilan</span>
                </div>
                <div class="flex flex-wrap items-center gap-2" id="catPillsWrapper">
                    <button type="button" data-cat-filter="all" class="cat-pill active flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all border shadow-sm bg-primary-600 text-white border-primary-600 shadow-primary-200 cursor-pointer">
                        <span>Semua Kategori</span>
                        <span class="cat-count px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white">{{ $teachersList->count() }}</span>
                    </button>
                    @foreach($categoriesList as $cat)
                        @php
                            $cTeachers = $teachersGrouped->get($cat->name, collect());
                            $cCount = $cTeachers->count();
                            $catSlug = \Illuminate\Support\Str::slug($cat->name) ?: 'cat-'.$cat->id;
                        @endphp
                        <button type="button" data-cat-filter="{{ $catSlug }}" class="cat-pill flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all border shadow-sm bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-slate-200 cursor-pointer">
                            <span>{{ $cat->name }}</span>
                            <span class="cat-count px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600">{{ $cCount }}</span>
                        </button>
                    @endforeach

                    @if($uncategorizedTeachers->count() > 0)
                        <button type="button" data-cat-filter="uncategorized" class="cat-pill flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all border shadow-sm bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-slate-200 cursor-pointer">
                            <span>Tanpa Kategori</span>
                            <span class="cat-count px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-700">{{ $uncategorizedTeachers->count() }}</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grouped Category Sections -->
        <div class="space-y-6" id="teacherCategoriesContainer">
            @foreach($categoriesList as $cat)
                @php
                    $catTeachers = $teachersGrouped->get($cat->name, collect());
                    $catSlug = \Illuminate\Support\Str::slug($cat->name) ?: 'cat-'.$cat->id;
                @endphp
                <div class="teacher-cat-card bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300" data-cat-card="{{ $catSlug }}">
                    <!-- Category Header -->
                    <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center border border-primary-100 shrink-0">
                                <i data-lucide="tag" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <h3 class="font-bold text-slate-800 text-base leading-tight">{{ $cat->name }}</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-primary-50 text-primary-700 border border-primary-100">
                                        {{ $catTeachers->count() }} Guru / Staff
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">Kelompok pengajar & staff pada bidang ini</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.teachers.create', ['category' => $cat->name]) }}" class="flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-primary-50 text-primary-600 hover:text-primary-700 border border-slate-200 hover:border-primary-200 rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95">
                                <i data-lucide="plus" class="h-4 w-4"></i>
                                Tambah Guru
                            </a>
                            <button type="button" data-toggle-cat="{{ $catSlug }}" class="h-9 w-9 flex items-center justify-center rounded-xl bg-white hover:bg-slate-100 text-slate-500 border border-slate-200 transition-all shadow-sm cursor-pointer" title="Buka/Tutup Tabel">
                                <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-300"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Category Table -->
                    <div class="overflow-x-auto cat-table-body" id="cat-body-{{ $catSlug }}">
                        @if($catTeachers->count() > 0)
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/30 border-b border-slate-100">
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Profil Guru</th>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jabatan / Mapel</th>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Urutan</th>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($catTeachers as $t)
                                        @php
                                            $photoUrl = $t->photo ? (str_starts_with($t->photo, 'http') ? $t->photo : asset($t->photo)) : null;
                                        @endphp
                                        <tr class="hover:bg-slate-50/50 transition-all duration-200 group" data-teacher-row data-teacher-active="{{ ($t->is_active ?? true) ? 'active' : 'inactive' }}" data-teacher-search="{{ strtolower(($t->name ?? '').' '.($t->email ?? '').' '.($t->subject ?? '').' '.($t->position ?? '')) }}">
                                            <td class="px-8 py-4">
                                                <div class="flex items-center gap-4">
                                                    <div class="h-12 w-12 rounded-2xl bg-slate-100 overflow-hidden border-2 border-white shadow-md shrink-0 ring-1 ring-slate-100 relative">
                                                        @if($photoUrl)
                                                            <img src="{{ $photoUrl }}" alt="{{ $t->name }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                                            <div style="display:none;" class="h-full w-full items-center justify-center text-slate-400 bg-slate-100 font-bold text-xs uppercase">
                                                                {{ strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $t->name) ?: 'GR', 0, 2)) }}
                                                            </div>
                                                        @else
                                                            <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                                <i data-lucide="user-2" class="h-6 w-6"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-900 leading-tight group-hover:text-primary-600 transition-colors text-sm">{{ $t->name }}</p>
                                                        <div class="flex items-center gap-2 mt-1.5">
                                                            <i data-lucide="mail" class="h-3 w-3 text-slate-400"></i>
                                                            <p class="text-xs text-slate-400 font-medium">{{ $t->email ?: 'Email belum diatur' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-4">
                                                <div class="space-y-1">
                                                    <div class="flex items-center gap-2">
                                                        <div class="h-1.5 w-1.5 rounded-full bg-primary-500"></div>
                                                        <p class="text-sm font-bold text-slate-700 leading-none">{{ $t->position ?: $t->subject }}</p>
                                                    </div>
                                                    @if($t->subject && $t->subject !== $t->position)
                                                        <p class="text-xs text-slate-400 ml-3.5 font-medium">{{ $t->subject }}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-8 py-4 text-center">
                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                                    {{ $t->order ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-4 text-center">
                                                @if($t->is_active ?? true)
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-slate-50 text-slate-400 border border-slate-200 uppercase tracking-wider">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                                                        Off
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.teachers.edit', $t) }}" class="h-8 w-8 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit Data">
                                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.teachers.destroy', $t) }}" class="inline" onsubmit="return confirm('Hapus data guru ini secara permanen?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="h-8 w-8 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Data">
                                                            <i class="fa-solid fa-trash text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-8 text-center text-slate-400">
                                <div class="h-12 w-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="users" class="h-6 w-6 opacity-30"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-600">Belum ada pengajar di kategori ini</p>
                                <a href="{{ route('admin.teachers.create', ['category' => $cat->name]) }}" class="inline-flex items-center gap-1.5 mt-2.5 text-xs font-bold text-primary-600 hover:text-primary-700 underline">
                                    + Tambah Pengajar Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($uncategorizedTeachers->count() > 0)
                <div class="teacher-cat-card bg-white rounded-3xl border border-amber-200 shadow-sm overflow-hidden transition-all duration-300" data-cat-card="uncategorized">
                    <div class="p-6 border-b border-amber-100 flex flex-wrap items-center justify-between gap-4 bg-amber-50/40">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center border border-amber-200 shrink-0">
                                <i data-lucide="help-circle" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <h3 class="font-bold text-slate-800 text-base leading-tight">Tanpa Kategori / Belum Diatur</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        {{ $uncategorizedTeachers->count() }} Guru / Staff
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-0.5">Pengajar yang belum ditentukan kategorinya</p>
                            </div>
                        </div>
                        <button type="button" data-toggle-cat="uncategorized" class="h-9 w-9 flex items-center justify-center rounded-xl bg-white hover:bg-slate-100 text-slate-500 border border-slate-200 transition-all shadow-sm cursor-pointer" title="Buka/Tutup Tabel">
                            <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-300"></i>
                        </button>
                    </div>

                    <div class="overflow-x-auto cat-table-body" id="cat-body-uncategorized">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/30 border-b border-slate-100">
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Profil Guru</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jabatan / Mapel</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Urutan</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($uncategorizedTeachers as $t)
                                    @php
                                        $photoUrl = $t->photo ? (str_starts_with($t->photo, 'http') ? $t->photo : asset($t->photo)) : null;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-all duration-200 group" data-teacher-row data-teacher-active="{{ ($t->is_active ?? true) ? 'active' : 'inactive' }}" data-teacher-search="{{ strtolower(($t->name ?? '').' '.($t->email ?? '').' '.($t->subject ?? '').' '.($t->position ?? '')) }}">
                                        <td class="px-8 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="h-12 w-12 rounded-2xl bg-slate-100 overflow-hidden border-2 border-white shadow-md shrink-0 ring-1 ring-slate-100 relative">
                                                    @if($photoUrl)
                                                        <img src="{{ $photoUrl }}" alt="{{ $t->name }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                                        <div style="display:none;" class="h-full w-full items-center justify-center text-slate-400 bg-slate-100 font-bold text-xs uppercase">
                                                            {{ strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $t->name) ?: 'GR', 0, 2)) }}
                                                        </div>
                                                    @else
                                                        <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                            <i data-lucide="user-2" class="h-6 w-6"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900 leading-tight group-hover:text-primary-600 transition-colors text-sm">{{ $t->name }}</p>
                                                    <div class="flex items-center gap-2 mt-1.5">
                                                        <i data-lucide="mail" class="h-3 w-3 text-slate-400"></i>
                                                        <p class="text-xs text-slate-400 font-medium">{{ $t->email ?: 'Email belum diatur' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="space-y-1">
                                                <div class="flex items-center gap-2">
                                                    <div class="h-1.5 w-1.5 rounded-full bg-amber-500"></div>
                                                    <p class="text-sm font-bold text-slate-700 leading-none">{{ $t->position ?: $t->subject }}</p>
                                                </div>
                                                <p class="text-xs text-amber-600 ml-3.5 font-medium">Kategori belum dipilih</p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4 text-center">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                                {{ $t->order ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-4 text-center">
                                            @if($t->is_active ?? true)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-slate-50 text-slate-400 border border-slate-200 uppercase tracking-wider">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                                                    Off
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.teachers.edit', $t) }}" class="h-8 w-8 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit Data">
                                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.teachers.destroy', $t) }}" class="inline" onsubmit="return confirm('Hapus data guru ini secara permanen?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="h-8 w-8 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Data">
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
            @endif

            <!-- Global Empty State when search has no matches -->
            <div id="teacherGlobalEmpty" class="hidden bg-white rounded-3xl border border-slate-200 shadow-sm p-12 text-center text-slate-400">
                <i data-lucide="search-x" class="h-12 w-12 mb-3 opacity-30 mx-auto text-primary-500"></i>
                <h4 class="text-slate-800 font-bold text-base mb-1">Tidak ada data yang cocok</h4>
                <p class="text-xs text-slate-400">Silakan sesuaikan kata kunci pencarian atau filter status Anda.</p>
            </div>
        </div>

    </div>



    <!-- TAB: STRUKTUR ORGANISASI (UPLOAD GAMBAR) -->
    <div id="tab-org" class="tab-pane animate-fade-in hidden">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-xl">Struktur Organisasi</h3>
                        <p class="text-xs text-slate-400 mt-1">Upload gambar bagan struktur organisasi sekolah</p>
                    </div>
                </div>

                @if($orgImage && $orgImage->value)
                    <div class="mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold text-slate-700">Gambar Saat Ini</span>
                            <form method="POST" action="{{ route('admin.teachers.org-image.delete') }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium" onclick="return confirm('Hapus gambar ini?')">Hapus</button>
                            </form>
                        </div>
                        <img src="{{ asset($orgImage->value) }}" alt="Struktur Organisasi" class="w-full rounded-xl shadow-sm border border-slate-100">
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.teachers.org-image.upload') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">Pilih Gambar</label>
                        <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center hover:border-primary-400 transition-colors cursor-pointer" id="dropZone">
                            <input type="file" name="org_image" id="orgImageInput" accept="image/*" class="hidden" onchange="previewImage(this)">
                            <label for="orgImageInput" class="cursor-pointer">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 bg-primary-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="h-8 w-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-700 mb-1">Klik untuk upload atau drag & drop</p>
                                    <p class="text-xs text-slate-400">PNG, JPG, JPEG (Maks 5MB)</p>
                                </div>
                            </label>
                        </div>
                        <div id="previewContainer" class="hidden mt-4">
                            <img id="previewImg" src="" alt="Preview" class="max-h-64 rounded-xl border border-slate-200 shadow-sm mx-auto">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-4 bg-primary-600 text-white rounded-2xl font-bold text-sm hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all active:scale-[0.98]">
                        Upload Gambar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('previewContainer').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</div>

<!-- MODAL WRAPPER -->
<div id="modalContainer" class="fixed inset-0 z-[100] hidden">
    <div id="modalOverlay" class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity duration-300 opacity-0"></div>
    <div id="modalContent" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-white rounded-[40px] shadow-2xl transition-all duration-300 scale-90 opacity-0 flex flex-col max-h-[90vh] border border-white/20">
        <!-- Content injected via JS -->
    </div>
</div>

<!-- TEMPLATE: FORM JABATAN -->
<template id="orgNodeFormTemplate">
    <div class="p-10 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
        <div>
            <h3 class="text-2xl font-display font-bold text-slate-900">Edit Jabatan</h3>
            <p class="text-xs text-slate-400 mt-1">Sesuaikan informasi struktural jabatan</p>
        </div>
        <button onclick="closeModal()" class="h-12 w-12 flex items-center justify-center hover:bg-white hover:shadow-sm rounded-2xl transition-all text-slate-400 hover:text-slate-900 border border-transparent hover:border-slate-100 group">
            <i data-lucide="x" class="h-6 w-6 group-hover:rotate-90 transition-transform duration-300"></i>
        </button>
    </div>
    <div class="p-10">
        <form id="mainForm" method="POST" class="space-y-8">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Jabatan</label>
                <input name="title" id="field_node_title" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Atasan (Hierarki)</label>
                <select name="parent_id" id="field_node_parent" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700 cursor-pointer appearance-none">
                    <option value="">-- Tingkat Paling Atas --</option>
                    @foreach($orgFlat as $row)
                        <option value="{{ $row['node']->id }}">{{ str_repeat('—', $row['depth']) }} {{ $row['node']->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center justify-between p-6 bg-slate-50 rounded-[30px] border border-slate-100">
                <div class="flex items-center gap-4">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Urutan</label>
                    <input name="order" id="field_node_order" type="number" value="0" class="w-20 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-center text-slate-700 focus:ring-2 focus:ring-primary-500/20 outline-none">
                </div>
                <label class="flex items-center gap-4 cursor-pointer group">
                    <div class="relative">
                        <input type="checkbox" name="is_active" id="field_node_active" value="1" checked class="sr-only peer">
                        <div class="w-12 h-7 bg-slate-200 peer-checked:bg-emerald-500 rounded-full transition-colors duration-300"></div>
                        <div class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-5 shadow-md"></div>
                    </div>
                    <span class="text-sm font-bold text-slate-600 group-hover:text-emerald-600 transition-colors">Aktif</span>
                </label>
            </div>
        </form>
    </div>
    <div class="p-10 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-4 rounded-b-[40px]">
        <button onclick="closeModal()" class="px-8 py-3 text-sm font-bold text-slate-400 hover:text-slate-700 transition-colors">Batal</button>
        <button onclick="document.getElementById('mainForm').submit()" class="px-10 py-3.5 bg-primary-600 text-white rounded-2xl font-bold text-sm hover:bg-primary-700 shadow-2xl shadow-primary-200 transition-all active:scale-95">Simpan Perubahan</button>
    </div>
</template>



<script>
const __ORG_NODES__ = @json($nodePayload);
const __ORG_NODE_UPDATE_URL_TEMPLATE__ = @json($orgNodeUpdateUrlTemplate);

document.addEventListener('DOMContentLoaded', () => {
    // Dropdown assign form toggle
    document.querySelectorAll('[data-assign-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const nodeId = btn.getAttribute('data-assign-toggle');
            const container = document.getElementById('assignForm-' + nodeId);
            if (container) container.classList.toggle('hidden');
        });
    });

    // Real-time search and filter with category grouping
    const searchInput = document.getElementById('teacherSearch');
    const statusSelect = document.getElementById('teacherStatus');
    const catPills = document.querySelectorAll('[data-cat-filter]');
    const catCards = document.querySelectorAll('[data-cat-card]');
    const globalEmpty = document.getElementById('teacherGlobalEmpty');
    let currentCat = 'all';

    function applyFilters() {
        if (!searchInput || !statusSelect) return;
        const query = searchInput.value.toLowerCase().trim();
        const status = statusSelect.value;
        let totalVisibleInSelectedCat = 0;

        catCards.forEach(card => {
            const cardCat = card.getAttribute('data-cat-card');
            const matchesCategory = (currentCat === 'all' || currentCat === cardCat);

            if (!matchesCategory) {
                card.style.display = 'none';
                return;
            }

            const rows = card.querySelectorAll('[data-teacher-row]');
            let cardVisibleRows = 0;

            rows.forEach(row => {
                const searchData = row.getAttribute('data-teacher-search') || '';
                const statusData = row.getAttribute('data-teacher-active') || '';
                const matchSearch = query === '' || searchData.includes(query);
                const matchStatus = status === 'all' || status === statusData;

                if (matchSearch && matchStatus) {
                    row.style.display = '';
                    cardVisibleRows++;
                    totalVisibleInSelectedCat++;
                } else {
                    row.style.display = 'none';
                }
            });

            // If there's active search or status filter and this card has 0 matches, hide the card
            if ((query !== '' || status !== 'all') && cardVisibleRows === 0) {
                card.style.display = 'none';
            } else {
                card.style.display = '';
            }
        });

        if (globalEmpty) {
            const isSearching = (query !== '' || status !== 'all');
            if (isSearching) {
                globalEmpty.classList.toggle('hidden', totalVisibleInSelectedCat > 0);
            } else {
                globalEmpty.classList.add('hidden');
            }
        }
    }

    catPills.forEach(pill => {
        pill.addEventListener('click', () => {
            currentCat = pill.getAttribute('data-cat-filter');
            catPills.forEach(p => {
                const isActive = (p === pill);
                if (isActive) {
                    p.className = 'cat-pill active flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all border shadow-sm bg-primary-600 text-white border-primary-600 shadow-primary-200 cursor-pointer';
                    const cnt = p.querySelector('.cat-count');
                    if (cnt) cnt.className = 'cat-count px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white';
                } else {
                    p.className = 'cat-pill flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all border shadow-sm bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-slate-200 cursor-pointer';
                    const cnt = p.querySelector('.cat-count');
                    if (cnt) {
                        const isAmber = p.getAttribute('data-cat-filter') === 'uncategorized';
                        cnt.className = 'cat-count px-2 py-0.5 rounded-full text-[10px] font-extrabold ' + (isAmber ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600');
                    }
                }
            });
            applyFilters();
        });
    });

    searchInput?.addEventListener('input', applyFilters);
    statusSelect?.addEventListener('change', applyFilters);

    // Accordion collapse/expand toggle per category
    document.querySelectorAll('[data-toggle-cat]').forEach(btn => {
        btn.addEventListener('click', () => {
            const slug = btn.getAttribute('data-toggle-cat');
            const body = document.getElementById('cat-body-' + slug);
            const icon = btn.querySelector('svg, i, [data-lucide="chevron-down"]');
            if (body) {
                body.classList.toggle('hidden');
                if (icon) {
                    icon.classList.toggle('rotate-180');
                }
            }
        });
    });
});

// Modal Logic
const container = document.getElementById('modalContainer');
const overlay = document.getElementById('modalOverlay');
const content = document.getElementById('modalContent');

function openModal(templateId) {
    const template = document.getElementById(templateId);
    content.innerHTML = template.innerHTML;
    container.classList.remove('hidden');
    setTimeout(() => {
        overlay.classList.replace('opacity-0', 'opacity-100');
        content.classList.replace('scale-90', 'scale-100');
        content.classList.replace('opacity-0', 'opacity-100');
        lucide.createIcons();
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    overlay.classList.replace('opacity-100', 'opacity-0');
    content.classList.replace('scale-100', 'scale-90');
    content.classList.replace('opacity-100', 'opacity-0');
    setTimeout(() => {
        container.classList.add('hidden');
    }, 300);
    document.body.style.overflow = '';
}



function openOrgNodeEdit(id) {
    const n = __ORG_NODES__.find(x => x.id === id);
    if (!n) return;
    openModal('orgNodeFormTemplate');
    document.getElementById('mainForm').action = __ORG_NODE_UPDATE_URL_TEMPLATE__.replace('__ID__', String(id));

    document.getElementById('field_node_title').value = n.title ?? '';
    document.getElementById('field_node_parent').value = n.parent_id ?? '';
    document.getElementById('field_node_order').value = n.order ?? 0;
    document.getElementById('field_node_active').checked = !!n.is_active;
}

// Tab Switching
function setActiveTab(tab) {
    document.querySelectorAll('.tab-pane').forEach(el => {
        el.classList.toggle('hidden', el.id !== 'tab-' + tab);
    });
    document.querySelectorAll('[data-tab-btn]').forEach(btn => {
        const isActive = btn.getAttribute('data-tab-btn') === tab;
        if (isActive) {
            btn.classList.add('bg-white', 'text-primary-600', 'shadow-sm');
            btn.classList.remove('text-slate-500');
        } else {
            btn.classList.remove('bg-white', 'text-primary-600', 'shadow-sm');
            btn.classList.add('text-slate-500');
        }
    });
    localStorage.setItem('admin_teachers_tab', tab);
}

document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-tab-btn]');
    if (btn) setActiveTab(btn.getAttribute('data-tab-btn'));
});

(() => {
    const tab = localStorage.getItem('admin_teachers_tab') || 'teachers';
    setActiveTab(tab);
})();

window.addEventListener('keydown', (e) => { if(e.key === 'Escape') closeModal(); });
</script>
@endsection
