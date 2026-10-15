@extends('admin.layout')

@section('title', 'Edit Guru')
@section('breadcrumb', 'Guru & Staff / Edit')
@section('page-title', 'Edit Guru')

@section('content')
@php
    $photoUrl = $teacher->photo ? (str_starts_with($teacher->photo, 'http') ? $teacher->photo : asset($teacher->photo)) : null;
@endphp

<div class="max-w-3xl mx-auto space-y-6">
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

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <div class="flex items-center gap-2">
                <i data-lucide="edit-3" class="h-5 w-5 text-primary-600"></i>
                <h3 class="font-bold text-slate-800">Form Edit Guru</h3>
            </div>
            <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-white/60 hover:text-slate-900 transition-all border border-transparent hover:border-slate-100">
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Lengkap *</label>
                <input name="name" value="{{ old('name', $teacher->name) }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Jabatan *</label>
                    <input name="position" value="{{ old('position', $teacher->position) }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Mata Pelajaran (Opsional)</label>
                    <input name="subject" value="{{ old('subject', $teacher->subject) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700" placeholder="Cth: Matematika / TKR">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Kategori</label>
                    <select name="category" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700 cursor-pointer">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}" {{ old('category', $teacher->category) === $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Urutan Tampilan</label>
                    <input type="number" name="order" value="{{ old('order', $teacher->order ?? 0) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700" placeholder="0">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Email (Opsional)</label>
                    <input type="email" name="email" value="{{ old('email', $teacher->email) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700" placeholder="guru@smk.sch.id">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">No. Telepon / WA (Opsional)</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Biodata / Profil Singkat</label>
                <textarea name="bio" rows="3" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all text-slate-700 resize-none" placeholder="Profil singkat latar belakang pendidikan, sertifikasi atau keahlian...">{{ old('bio', $teacher->bio) }}</textarea>
            </div>

            <div class="rounded-[28px] border border-slate-200 bg-slate-50/70 p-6 space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-sm font-bold text-slate-800">Foto Saat Ini</div>
                        <div class="text-xs text-slate-400">Preview foto profil yang sedang aktif</div>
                    </div>
                    @if($photoUrl)
                        <div class="h-14 w-14 rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm flex items-center justify-center relative">
                            <img src="{{ $photoUrl }}" alt="{{ $teacher->name }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                            <div class="h-full w-full items-center justify-center bg-slate-100 text-slate-500 font-bold text-sm hidden">
                                {{ strtoupper(substr($teacher->name, 0, 2)) }}
                            </div>
                        </div>
                    @else
                        <div class="h-14 w-14 rounded-2xl border border-slate-200 bg-slate-100 flex items-center justify-center text-slate-400 text-xs font-bold">
                            No Foto
                        </div>
                    @endif
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Ganti Foto (Opsional)</label>
                    <input type="file" name="photo_file" accept="image/*" class="w-full px-5 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none text-sm">
                    <p class="text-xs text-slate-400 font-medium">Foto akan otomatis dikompres dan dikonversi ke WebP.</p>
                </div>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-700">Status Aktif</p>
                    <p class="text-xs text-slate-400">Guru ditampilkan pada direktori website publik</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $teacher->is_active) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <a href="{{ route('admin.teachers.index') }}" class="px-6 py-3 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-all">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-2xl font-bold text-sm hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
