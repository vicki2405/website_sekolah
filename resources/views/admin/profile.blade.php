@extends('admin.layout')

@section('title', 'Profil Sekolah')
@section('breadcrumb', 'Profil')
@section('page-title', 'Manajemen Profil Sekolah')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in items-center">
            <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Prakata Section -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="message-square" class="h-5 w-5 text-primary-600"></i>
                Prakata Kepala Sekolah
            </h4>
        </div>
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="section_key" value="prakata">

            <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Judul Prakata</label>
                        <input type="text" name="title" value="{{ $sections['prakata']->title ?? '' }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium" placeholder="Contoh: Sambutan Kepala Sekolah">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Nama Kepala Sekolah</label>
                        <input type="text" name="principal_name" value="{{ $sections['prakata']->principal_name ?? '' }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium" placeholder="Contoh: Dr. Ahmad Fauzi, M.Pd">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Konten Prakata</label>
                        <textarea name="content" rows="8" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium resize-none" placeholder="Masukkan teks prakata kepala sekolah...">{{ $sections['prakata']->content ?? '' }}</textarea>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Foto Kepala Sekolah</label>
                        <div class="flex flex-col items-center">
                            <div id="prakata_image_preview" class="mb-4 w-48 h-48 rounded-2xl overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center">
                                @if(isset($sections['prakata']) && $sections['prakata']->image)
                                    <img src="{{ str_starts_with($sections['prakata']->image, 'http') ? $sections['prakata']->image : asset(ltrim($sections['prakata']->image, '/')) }}" class="w-full h-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';">
                                    <div class="text-center p-4 hidden">
                                        <i data-lucide="user" class="h-12 w-12 text-slate-300 mb-2 mx-auto"></i>
                                        <p class="text-xs text-slate-400">Foto tidak ditemukan</p>
                                    </div>
                                @else
                                    <div class="text-center p-4">
                                        <i data-lucide="user" class="h-12 w-12 text-slate-300 mb-2 mx-auto"></i>
                                        <p class="text-xs text-slate-400">Belum ada foto</p>
                                    </div>
                                @endif
                            </div>
                            <label class="flex items-center justify-center w-full max-w-xs h-12 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 hover:bg-slate-100 hover:border-primary-300 transition-all cursor-pointer">
                                <i data-lucide="upload" class="h-4 w-4 text-slate-400 mr-2"></i>
                                <span class="text-xs font-bold text-slate-500">Pilih Foto</span>
                                <input type="file" name="image_file" class="hidden" onchange="previewImage(this, 'prakata_image_preview', true)">
                            </label>
                            <p class="text-[10px] text-slate-400 mt-2">JPG, PNG atau WebP (Maks. 5MB)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Visi Section -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="eye" class="h-5 w-5 text-primary-600"></i>
                Visi Sekolah
            </h4>
        </div>
        <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="section_key" value="visi">

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Judul Visi</label>
                <input type="text" name="title" value="{{ $sections['visi']->title ?? '' }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium" placeholder="Contoh: Visi SMKN 1">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Konten Visi</label>
                <textarea name="content" rows="4" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium resize-none" placeholder="Masukkan visi sekolah...">{{ $sections['visi']->content ?? '' }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Misi Section -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="target" class="h-5 w-5 text-primary-600"></i>
                Misi Sekolah
            </h4>
        </div>
        <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="section_key" value="misi">

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Judul Misi</label>
                <input type="text" name="title" value="{{ $sections['misi']->title ?? '' }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium" placeholder="Contoh: Misi SMKN 1">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Konten Misi</label>
                <textarea name="content" rows="6" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium resize-none" placeholder="Masukkan misi sekolah (pisahkan dengan baris baru untuk setiap poin)...">{{ $sections['misi']->content ?? '' }}</textarea>
                <p class="text-[10px] text-slate-400 mt-2 ml-1">Pisahkan setiap poin misi dengan baris baru</p>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Tujuan Section -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="flag" class="h-5 w-5 text-primary-600"></i>
                Tujuan Sekolah
            </h4>
        </div>
        <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="section_key" value="tujuan">

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Judul Tujuan</label>
                <input type="text" name="title" value="{{ $sections['tujuan']->title ?? '' }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium" placeholder="Contoh: Tujuan SMKS Muhammadiyah 2 Genteng">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Tipe Tampilan</label>
                <select name="subtitle" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium cursor-pointer">
                    <option value="paragraph" {{ (($sections['tujuan']->subtitle ?? 'paragraph') === 'paragraph') ? 'selected' : '' }}>Paragraf</option>
                    <option value="list" {{ (($sections['tujuan']->subtitle ?? '') === 'list') ? 'selected' : '' }}>List</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Konten Tujuan</label>
                <textarea name="content" id="tujuan-content" class="hidden">{{ $sections['tujuan']->content ?? '' }}</textarea>
                <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                    <div
                        id="tujuan-content-editor"
                        data-quill-textarea-id="tujuan-content"
                        data-placeholder="Tulis tujuan sekolah di sini..."
                        class="bg-white"
                    ></div>
                </div>
                <p class="text-[10px] text-slate-400 mt-2 ml-1">Jika memilih List, gunakan bullet/numbering pada toolbar atau pisahkan tiap poin dengan baris baru.</p>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Sejarah Section -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="clock" class="h-5 w-5 text-primary-600"></i>
                Sejarah Sekolah
            </h4>
        </div>
        <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="section_key" value="sejarah">

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Judul Sejarah</label>
                <input type="text" name="title" value="{{ $sections['sejarah']->title ?? '' }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-medium" placeholder="Contoh: Sejarah SMKN 1">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Konten Sejarah</label>
                <textarea name="content" id="sejarah-content" class="hidden">{{ $sections['sejarah']->content ?? '' }}</textarea>
                <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                    <div
                        id="sejarah-content-editor"
                        data-quill-textarea-id="sejarah-content"
                        data-placeholder="Tulis sejarah sekolah di sini..."
                        class="bg-white"
                    ></div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function previewImage(input, containerId, isRound = false) {
        const container = document.getElementById(containerId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (isRound) {
                    container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                } else {
                    container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addMilestoneRow() {
        const container = document.getElementById('milestones_container');
        const emptyText = document.getElementById('empty_milestones_text');
        if (emptyText) emptyText.remove();

        const index = container.querySelectorAll('.milestone-row').length + '_' + Date.now();
        const row = document.createElement('div');
        row.className = 'milestone-row p-4 rounded-2xl bg-slate-50 border border-slate-200 grid grid-cols-1 md:grid-cols-12 gap-3 items-start animate-fade-in';
        row.innerHTML = `
            <div class="md:col-span-3 space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase">Tahun / Periode</label>
                <input type="text" name="items[${index}][year]" placeholder="Contoh: 1995 atau Sekarang" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
            </div>
            <div class="md:col-span-4 space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase">Judul Capaian</label>
                <input type="text" name="items[${index}][title]" placeholder="Contoh: Awal Pendirian" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
            </div>
            <div class="md:col-span-4 space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase">Keterangan Singkat</label>
                <input type="text" name="items[${index}][desc]" placeholder="Contoh: Didirikan sebagai respon..." class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
            </div>
            <div class="md:col-span-1 pt-6 text-right">
                <button type="button" onclick="this.closest('.milestone-row').remove()" class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        if (window.lucide) {
            lucide.createIcons();
        }
    }
</script>

@vite('resources/js/admin-news-editor.js')
@endsection
