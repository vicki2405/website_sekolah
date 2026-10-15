@extends('admin.layout')

@section('title', 'News Management')
@section('breadcrumb', 'News')
@section('page-title', 'News Management')

@section('content')
<div class="animate__animated animate__fadeInUp">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Berita</h2>
            <p class="text-slate-500 text-sm">Kelola semua artikel dan publikasi berita sekolah Anda.</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            Tambah Berita Baru
        </a>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-table-list text-primary-600"></i>
                Daftar Berita
            </h3>
            <div class="relative">
                <input type="text" placeholder="Cari berita..." class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Berita</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($news as $item)
                    @continue(!is_object($item))
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200 flex items-center justify-center">
                                    @if($item->image)
                                        @php
                                            $imageUrl = $item->image;
                                            if (is_string($imageUrl) && $imageUrl !== '' && !str_starts_with($imageUrl, 'http') && !str_starts_with($imageUrl, '/storage/')) {
                                                $imageUrl = asset('storage/' . ltrim($imageUrl, '/'));
                                            } else if (is_string($imageUrl) && !str_starts_with($imageUrl, 'http')) {
                                                $imageUrl = asset(ltrim($imageUrl, '/'));
                                            }
                                        @endphp
                                        <img src="{{ $imageUrl }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                        <div class="h-full w-full items-center justify-center text-slate-300 hidden">
                                            <i class="fa-solid fa-image text-xl"></i>
                                        </div>
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-slate-300">
                                            <i class="fa-solid fa-image text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm group-hover:text-primary-600 transition-colors line-clamp-1">{{ $item->title }}</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit(strip_tags($item->content), 60) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->is_published)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-400 border border-slate-200 uppercase tracking-wider">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold text-slate-500">{{ $item->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.news.edit', $item) }}" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('admin.news.destroy', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
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
                                    <i class="fa-solid fa-newspaper text-3xl"></i>
                                </div>
                                <p class="text-slate-400 text-sm font-medium italic">Belum ada data berita yang ditambahkan.</p>
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
