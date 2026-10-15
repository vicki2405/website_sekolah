@extends('admin.layout')

@section('title', 'Sliders')
@section('breadcrumb', 'Media Sliders')
@section('page-title', 'Homepage Sliders')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/30">
            <div>
                <h3 class="font-bold text-slate-900">Slider Beranda</h3>
                <p class="text-xs text-slate-400 mt-1">Kelola gambar yang tampil di slide utama website</p>
            </div>
            <button type="button" onclick="window.location.href='#'" class="flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all active:scale-95 group">
                <i data-lucide="plus" class="h-5 w-5 group-hover:rotate-90 transition-transform duration-300"></i>
                Tambah Slider
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Preview Image</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Judul / Caption</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Urutan</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sliders as $slider)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200 group">
                            <td class="px-8 py-5">
                                <div class="h-20 w-36 rounded-2xl bg-slate-100 overflow-hidden border-2 border-white shadow-md ring-1 ring-slate-100 flex items-center justify-center">
                                    @if($slider->image)
                                        <img src="{{ str_starts_with($slider->image, 'http') ? $slider->image : asset(ltrim($slider->image, '/')) }}" class="h-full w-full object-cover" alt="{{ $slider->title ?? 'Slider' }}" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                        <div class="h-full w-full items-center justify-center text-slate-300 hidden">
                                            <i data-lucide="image" class="h-8 w-8"></i>
                                        </div>
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-slate-300">
                                            <i data-lucide="image" class="h-8 w-8"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <h6 class="font-bold text-slate-900 leading-tight group-hover:text-primary-600 transition-colors">{{ Str::limit($slider->title ?? 'Tanpa Judul', 50) }}</h6>
                                <p class="text-xs text-slate-400 mt-2 font-medium">Ditambahkan pada {{ $slider->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                    {{ $slider->order ?? 0 }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($slider->is_active ?? true)
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
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="window.location.href='#'" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit Slider">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form method="POST" action="#" class="inline" onsubmit="return confirm('Hapus slider ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Slider">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400 max-w-xs mx-auto">
                                    <div class="h-24 w-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 shadow-inner">
                                        <i data-lucide="gallery-horizontal" class="h-12 w-12 opacity-20"></i>
                                    </div>
                                    <h4 class="text-slate-900 font-bold mb-2 text-lg">Belum ada slider</h4>
                                    <p class="text-sm leading-relaxed">Silakan tambahkan gambar slider untuk mempercantik halaman utama website Anda.</p>
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
