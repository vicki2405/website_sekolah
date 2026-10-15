@extends('admin.layout')

@section('title', 'WAKA')
@section('breadcrumb', 'WAKA')
@section('page-title', 'Manajemen WAKA')

@section('content')
<div class="animate__animated animate__fadeInUp">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen WAKA</h2>
            <p class="text-slate-500 text-sm">Kelola halaman sub-menu WAKA yang tampil di website.</p>
        </div>
        <a href="{{ route('admin.waka.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            Tambah Halaman WAKA
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in items-center mb-6">
            <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    <div class="admin-card overflow-hidden">
        <div class="p-8 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-list-ul text-brand-500"></i>
                Daftar Halaman WAKA
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/50 text-slate-500 font-bold uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4 border-b border-slate-100 w-16 text-center">Urutan</th>
                        <th class="px-6 py-4 border-b border-slate-100">Judul</th>
                        <th class="px-6 py-4 border-b border-slate-100">Slug / URL</th>
                        <th class="px-6 py-4 border-b border-slate-100 w-32 text-center">Status</th>
                        <th class="px-6 py-4 border-b border-slate-100 w-32 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($wakas as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $item->order }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $item->title }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $item->slug }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($item->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.waka.edit', $item) }}" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit Data">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.waka.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus halaman WAKA ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Data">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-regular fa-folder-open text-4xl mb-3 text-slate-300"></i>
                                <p>Belum ada data WAKA.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection