@extends('admin.layout')

@section('title', 'Tampilan & Logo Website')
@section('breadcrumb', 'Tampilan & Logo')
@section('page-title', 'Manajemen Tampilan & Logo Website')

@section('content')
<div class="space-y-8 max-w-5xl">

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in items-center">
            <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-100 flex gap-3 animate-fade-in items-start">
            <i data-lucide="alert-circle" class="text-rose-500 h-5 w-5 shrink-0 mt-0.5"></i>
            <div class="space-y-1">
                @foreach($errors->all() as $err)
                    <p class="text-xs font-semibold text-rose-700">{{ $err }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Live Preview Header Website -->
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-6 text-white shadow-xl border border-slate-700/50 relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="flex h-2.5 w-2.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Live Preview Header Website</span>
            </div>
            <span class="text-[11px] text-slate-400 bg-slate-800/80 px-2.5 py-1 rounded-full border border-slate-700">Pratinjau Nyata di Navbar</span>
        </div>

        <!-- Simulated Navbar Container -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-lg flex items-center justify-between border border-slate-200 text-slate-900 transition-all">
            <div class="flex items-center gap-3 shrink-0">
                <!-- Logo Preview Box -->
                <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-xl flex items-center justify-center p-1 bg-slate-50 border border-slate-100 overflow-hidden shadow-inner">
                    @php
                        $currentLogo = $settings['school_logo'] ?? null;
                    @endphp
                    <img id="live-logo-preview" 
                         src="{{ $currentLogo ? (str_starts_with($currentLogo, 'http') ? $currentLogo : asset(ltrim($currentLogo, '/'))) : '' }}" 
                         alt="Logo Preview" 
                         class="max-h-full max-w-full object-contain {{ $currentLogo ? '' : 'hidden' }}">
                    <div id="live-logo-fallback" class="flex flex-col items-center justify-center text-slate-400 {{ $currentLogo ? 'hidden' : '' }}">
                        <i data-lucide="shield" class="h-6 w-6 text-blue-500"></i>
                    </div>
                </div>

                <!-- Live Text Preview -->
                <div class="leading-none shrink-0 select-none">
                    <div class="text-xl sm:text-2xl font-black tracking-tight">
                        <span id="live-primary-text" class="text-blue-600 transition-colors">{{ $settings['school_name_primary'] ?? 'STEMDA' }}</span>
                        <span id="live-secondary-text" class="text-slate-900 transition-colors ml-1">{{ $settings['school_name_secondary'] ?? 'GENTENG' }}</span>
                    </div>
                    <div id="live-tagline-text"
                         class="text-[10px] sm:text-[11px] font-bold tracking-wide text-red-600 leading-snug mt-0.5"
                         style="text-shadow:-1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
                        {{ $settings['school_tagline'] ?? 'Outstanding and Innovative Vocational School' }}
                    </div>
                </div>
            </div>

            <!-- Simulated Nav Menu -->
            <div class="hidden md:flex items-center gap-3 text-xs font-semibold text-slate-500">
                <span class="text-blue-600 font-bold bg-blue-50 px-3 py-1.5 rounded-lg">Beranda</span>
                <span class="hover:text-slate-900 px-2 py-1.5">Profil</span>
                <span class="hover:text-slate-900 px-2 py-1.5">Jurusan</span>
                <span class="hover:text-slate-900 px-2 py-1.5">Berita</span>
                <span class="bg-blue-600 text-white font-bold px-3 py-1.5 rounded-lg shadow-sm shadow-blue-500/20">PPDB</span>
            </div>
        </div>

        <p class="text-xs text-slate-400 mt-3 flex items-center gap-1.5">
            <i data-lucide="info" class="h-3.5 w-3.5 text-blue-400"></i>
            Teks dan logo di atas akan berubah langsung saat Anda mengetik atau memilih file di bawah ini.
        </p>
    </div>

    <!-- Form Pengaturan -->
    <form action="{{ route('admin.appearance.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Bagian 1: Logo Sekolah Utama -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i data-lucide="image" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900">Logo Sekolah Utama</h4>
                        <p class="text-xs text-slate-500">Tampil pada Navbar atas, Footer, dan kartu identitas website</p>
                    </div>
                </div>
                <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                    <i data-lucide="zap" class="h-3.5 w-3.5 text-emerald-500"></i>
                    Auto-Convert & Resize ke WebP
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Info Alert Auto Convert -->
                <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 flex gap-3 text-xs text-blue-900 leading-relaxed">
                    <i data-lucide="sparkles" class="h-5 w-5 text-blue-600 shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold mb-0.5">Tidak Perlu Convert Online / Resize Manual!</p>
                        <p class="text-blue-700">Anda dapat langsung mengunggah file foto/gambar logo ukuran besar (hingga 10 MB). Sistem otomatis mengecilkan dan mengonversi formatnya menjadi <strong>.WebP</strong> super ringan (~30 KB) dengan latar belakang transparan tetap terjaga.</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6 items-start">
                    <!-- Current / Preview Logo Box -->
                    <div class="flex flex-col items-center justify-center p-5 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 text-center">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Logo Aktif Saat Ini</label>
                        <div class="h-28 w-28 rounded-2xl bg-white border border-slate-200/80 p-2 flex items-center justify-center shadow-sm relative group overflow-hidden">
                            @if(!empty($settings['school_logo']))
                                <img id="logo-preview-box" src="{{ str_starts_with($settings['school_logo'], 'http') ? $settings['school_logo'] : asset(ltrim($settings['school_logo'], '/')) }}" class="max-h-full max-w-full object-contain">
                            @else
                                <div id="logo-preview-box-empty" class="text-center text-slate-400">
                                    <i data-lucide="image-off" class="h-8 w-8 mx-auto mb-1 text-slate-300"></i>
                                    <span class="text-[11px]">Belum ada logo</span>
                                </div>
                            @endif
                        </div>

                        @if(!empty($settings['school_logo']))
                            <div class="mt-3">
                                <button type="button" 
                                        onclick="if(confirm('Yakin ingin mereset logo sekolah ke default?')) document.getElementById('form-reset-logo').submit();"
                                        class="text-xs font-semibold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition-colors">
                                    <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                    Hapus / Reset Logo
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Upload Input Controls -->
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Pilih Berkas Logo Baru</label>
                            <input type="file" 
                                   id="input-logo-file" 
                                   name="logo_file" 
                                   accept="image/png,image/jpeg,image/webp,image/gif"
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer cursor-pointer border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none">
                            <p class="text-[11px] text-slate-400 mt-1.5">Mendukung format PNG, WebP, JPG, GIF. Maksimal ukuran file 10 MB.</p>
                        </div>

                        <!-- Checkbox Auto Favicon -->
                        <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80 flex items-start gap-3">
                            <input type="checkbox" 
                                   id="auto_favicon" 
                                   name="auto_favicon" 
                                   value="1" 
                                   checked 
                                   class="mt-1 h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <label for="auto_favicon" class="text-xs text-slate-700 font-medium cursor-pointer">
                                <span class="font-bold text-slate-900 block">Jadikan logo ini sekaligus sebagai Favicon tab browser (1-Klik)</span>
                                <span class="text-slate-500 text-[11px]">Sistem akan memotong dan mengompresi logo menjadi icon kotak (128x128px) untuk tab browser secara otomatis.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian 2: Teks Branding Navbar -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <i data-lucide="type" class="h-5 w-5"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900">Identitas Teks Navbar & Header</h4>
                    <p class="text-xs text-slate-500">Nama sekolah dan slogan yang mendampingi logo pada navbar</p>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Sekolah Utama (Warna Biru)</label>
                        <input type="text" 
                               id="input-name-primary"
                               name="school_name_primary" 
                               value="{{ old('school_name_primary', $settings['school_name_primary'] ?? 'STEMDA') }}" 
                               class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                               placeholder="Contoh: STEMDA">
                        <p class="text-[11px] text-slate-400 mt-1">Kata pertama/singkatan nama sekolah.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Sekunder / Wilayah (Warna Hitam)</label>
                        <input type="text" 
                               id="input-name-secondary"
                               name="school_name_secondary" 
                               value="{{ old('school_name_secondary', $settings['school_name_secondary'] ?? 'GENTENG') }}" 
                               class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                               placeholder="Contoh: GENTENG">
                        <p class="text-[11px] text-slate-400 mt-1">Wilayah atau pelengkap nama sekolah.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Slogan / Tagline Sekolah</label>
                    <input type="text" 
                           id="input-tagline"
                           name="school_tagline" 
                           value="{{ old('school_tagline', $settings['school_tagline'] ?? 'Outstanding and Innovative Vocational School') }}" 
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                           placeholder="Contoh: Outstanding and Innovative Vocational School">
                    <p class="text-[11px] text-slate-400 mt-1">Tagline tampil di bawah nama sekolah pada layar tablet dan desktop.</p>
                </div>
            </div>
        </div>

        <!-- Bagian 3: Favicon Kustom Terpisah (Opsional) -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i data-lucide="globe" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900">Favicon Khusus (Opsional)</h4>
                        <p class="text-xs text-slate-500">Gunakan jika Anda ingin icon tab browser berbeda dengan logo utama</p>
                    </div>
                </div>
            </div>

            <div class="p-6 grid sm:grid-cols-3 gap-6 items-center">
                <div class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Favicon Aktif</label>
                    <div class="h-14 w-14 rounded-xl bg-white border border-slate-200 p-2 flex items-center justify-center shadow-sm">
                        @if(!empty($settings['school_favicon']))
                            <img src="{{ str_starts_with($settings['school_favicon'], 'http') ? $settings['school_favicon'] : asset(ltrim($settings['school_favicon'], '/')) }}" class="max-h-full max-w-full object-contain">
                        @else
                            <i data-lucide="globe" class="h-6 w-6 text-slate-300"></i>
                        @endif
                    </div>
                    @if(!empty($settings['school_favicon']))
                        <button type="button" 
                                onclick="if(confirm('Yakin ingin mereset favicon?')) document.getElementById('form-reset-favicon').submit();"
                                class="text-[11px] font-semibold text-rose-600 hover:text-rose-700 mt-2 flex items-center gap-1 transition-colors">
                            <i data-lucide="trash-2" class="h-3 w-3"></i>
                            Reset Favicon
                        </button>
                    @endif
                </div>

                <div class="sm:col-span-2 space-y-2">
                    <label class="block text-xs font-bold text-slate-700">Unggah Favicon Khusus (.ico, .png, .webp)</label>
                    <input type="file" 
                           name="favicon_file" 
                           accept="image/x-icon,image/png,image/webp,image/jpeg"
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-700 file:text-white hover:file:bg-slate-800 file:cursor-pointer cursor-pointer border border-slate-200 rounded-2xl bg-slate-50 focus:outline-none">
                    <p class="text-[11px] text-slate-400">Jika dikosongkan, favicon akan otomatis mengikuti logo sekolah jika opsi 1-Klik dicentang.</p>
                </div>
            </div>
        </div>

        <!-- Submit Button Action -->
        <div class="flex items-center justify-end gap-4 pt-2">
            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                <i data-lucide="save" class="h-4 w-4"></i>
                Simpan Perubahan Tampilan
            </button>
        </div>
    </form>

    <!-- Hidden Reset Forms -->
    <form id="form-reset-logo" action="{{ route('admin.appearance.reset-logo') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    <form id="form-reset-favicon" action="{{ route('admin.appearance.reset-favicon') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<!-- Realtime Client-side Preview Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputPrimary = document.getElementById('input-name-primary');
    const inputSecondary = document.getElementById('input-name-secondary');
    const inputTagline = document.getElementById('input-tagline');
    const inputLogoFile = document.getElementById('input-logo-file');

    const livePrimary = document.getElementById('live-primary-text');
    const liveSecondary = document.getElementById('live-secondary-text');
    const liveTagline = document.getElementById('live-tagline-text');
    const liveLogoPreview = document.getElementById('live-logo-preview');
    const liveLogoFallback = document.getElementById('live-logo-fallback');

    if (inputPrimary && livePrimary) {
        inputPrimary.addEventListener('input', function () {
            livePrimary.textContent = this.value.trim() || 'STEMDA';
        });
    }

    if (inputSecondary && liveSecondary) {
        inputSecondary.addEventListener('input', function () {
            liveSecondary.textContent = this.value.trim() || 'GENTENG';
        });
    }

    if (inputTagline && liveTagline) {
        inputTagline.addEventListener('input', function () {
            liveTagline.textContent = this.value.trim() || 'Outstanding and Innovative Vocational School';
        });
    }

    if (inputLogoFile && liveLogoPreview) {
        inputLogoFile.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    liveLogoPreview.src = e.target.result;
                    liveLogoPreview.classList.remove('hidden');
                    if (liveLogoFallback) liveLogoFallback.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection
