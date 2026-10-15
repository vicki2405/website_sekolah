@extends('admin.layout')

@section('title', 'Fasilitas')
@section('breadcrumb', 'Fasilitas')
@section('page-title', 'Manajemen Fasilitas')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in">
            <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Fasilitas</h2>
            <p class="text-slate-500 text-sm">Kelola daftar fasilitas yang tampil di halaman /fasilitas.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.facilities.categories.index') }}" class="px-6 py-3 bg-white border border-slate-200 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                <i class="fa-solid fa-layer-group"></i>
                Kategori
            </a>
            <a href="{{ route('admin.facilities.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i>
                Tambah Fasilitas
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Fasilitas</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Urutan</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($facilities as $f)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200 group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-16 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                                        @if($f->image)
                                            <img src="{{ str_starts_with($f->image, 'http') ? $f->image : asset(ltrim($f->image, '/')) }}" alt="{{ $f->title }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                            <div class="h-full w-full items-center justify-center text-slate-300 hidden">
                                                <i data-lucide="building-2" class="h-5 w-5"></i>
                                            </div>
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                <i data-lucide="building-2" class="h-5 w-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 group-hover:text-primary-600 transition-colors">{{ $f->title }}</p>
                                        <p class="text-xs text-slate-400 line-clamp-1 mt-1">{{ $f->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $f->category ?? 'Umum' }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                    {{ $f->order }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($f->is_active)
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
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.facilities.edit', $f) }}" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit Fasilitas">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.facilities.destroy', $f) }}" class="inline" onsubmit="return confirm('Hapus fasilitas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Fasilitas">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400 max-w-xs mx-auto">
                                    <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                                        <i data-lucide="sparkles" class="h-10 w-10 opacity-20"></i>
                                    </div>
                                    <h4 class="text-slate-900 font-bold mb-2">Belum ada fasilitas</h4>
                                    <p class="text-sm leading-relaxed">Klik tombol "Tambah Fasilitas" untuk menambahkan data.</p>
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

