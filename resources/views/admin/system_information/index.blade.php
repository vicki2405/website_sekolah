@extends('admin.layout')

@section('title', 'Portal')
@section('breadcrumb', 'Portal')
@section('page-title', 'Portal')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
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
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in items-center">
            <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <div class="flex items-center gap-2">
                <i data-lucide="link" class="h-5 w-5 text-primary-600"></i>
                <h3 class="font-bold text-slate-800">Create Menu</h3>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.system-information.store') }}" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Menu *</label>
                    <input name="name" value="{{ old('name') }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700" placeholder="Cth: E-Raport">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Link Menu *</label>
                    <input name="url" value="{{ old('url') }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700" placeholder="https://... atau /path">
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-2xl font-bold text-sm hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all active:scale-95">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <div class="flex items-center gap-2">
                <i data-lucide="list" class="h-5 w-5 text-primary-600"></i>
                <h3 class="font-bold text-slate-800">Daftar Menu</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Link</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($menus as $m)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200">
                            <td class="px-8 py-5">
                                <p class="font-bold text-slate-900">{{ $m->name }}</p>
                            </td>
                            <td class="px-8 py-5">
                                <a href="{{ $m->url }}" target="_blank" class="text-sm font-medium text-primary-600 hover:underline break-all">
                                    {{ $m->url }}
                                </a>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <form method="POST" action="{{ route('admin.system-information.destroy', $m) }}" class="inline" onsubmit="return confirm('Hapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="h-9 w-9 inline-flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Menu">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-16 text-center text-slate-400">
                                Belum ada menu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
