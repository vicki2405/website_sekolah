@extends('admin.layout')

@section('title', 'Tambah Kategori Guru')
@section('breadcrumb', 'Guru & Staff / Kategori / Tambah')
@section('page-title', 'Tambah Kategori Guru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
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
                <i data-lucide="tags" class="h-5 w-5 text-primary-600"></i>
                <h3 class="font-bold text-slate-800">Form Tambah Kategori</h3>
            </div>
            <a href="{{ route('admin.teacher-categories.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-white/60 hover:text-slate-900 transition-all border border-transparent hover:border-slate-100">
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('admin.teacher-categories.store') }}" class="p-8 space-y-6">
            @csrf

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Kategori *</label>
                <input name="name" value="{{ old('name') }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700" placeholder="Cth: Tenaga Pendidik">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Urutan Tampil</label>
                <input name="order" type="number" value="{{ old('order', 0) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700" placeholder="0">
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <a href="{{ route('admin.teacher-categories.index') }}" class="px-6 py-3 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-all">
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
