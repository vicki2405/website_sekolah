@extends('admin.layout')

@section('title', 'Kategori Fasilitas')
@section('breadcrumb', 'Fasilitas / Kategori')
@section('page-title', 'Manajemen Kategori Fasilitas')

@section('content')
<div class="space-y-6">
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

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in">
            <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in">
        <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/30">
            <div class="flex items-center gap-2">
                <i data-lucide="layers" class="h-5 w-5 text-primary-600"></i>
                <h3 class="font-bold text-slate-800">Daftar Kategori Fasilitas</h3>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.facilities.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-white/60 hover:text-slate-900 transition-all border border-transparent hover:border-slate-100">
                    Kembali ke Fasilitas
                </a>
                <a href="{{ route('admin.facilities.categories.create') }}" class="flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all active:scale-95">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Kategori
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Kategori</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Urutan</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200 group">
                            <td class="px-8 py-5">
                                <p class="font-bold text-slate-900 group-hover:text-primary-600 transition-colors">{{ $cat->name }}</p>
                                <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $cat->slug }}</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($cat->is_active)
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-widest border border-emerald-100">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-widest border border-slate-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                    {{ $cat->order }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.facilities.categories.edit', $cat) }}" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit Kategori">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.facilities.categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Hapus kategori ini? Semua fasilitas dalam kategori ini akan dipindahkan ke \"Umum\".')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Kategori">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400 max-w-xs mx-auto">
                                    <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                                        <i data-lucide="layers" class="h-10 w-10 opacity-20"></i>
                                    </div>
                                    <h4 class="text-slate-900 font-bold mb-2">Belum ada kategori</h4>
                                    <p class="text-sm leading-relaxed">Klik tombol "Tambah Kategori" untuk membuat kategori fasilitas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

