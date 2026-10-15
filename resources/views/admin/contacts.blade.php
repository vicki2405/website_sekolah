@extends('admin.layout')

@section('title', 'Manajemen Informasi PPDB')
@section('breadcrumb', 'Informasi PPDB')
@section('page-title', 'Manajemen Informasi PPDB')

@section('content')
<div class="animate__animated animate__fadeInUp space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
                <i class="fa-solid fa-address-card text-primary-600"></i>
                Pusat Informasi PPDB & Kontak
            </h2>
            <p class="text-slate-500 text-sm mt-1">Kelola poster PPDB, link pendaftaran online, kontak narahubung WhatsApp, serta informasi pendaftaran siswa baru.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('contact') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold transition-colors inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>Lihat Halaman Publik</span>
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center gap-3 animate__animated animate__fadeIn">
        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- PPDB Settings Form -->
    <form action="{{ route('admin.contacts.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Section 1: Poster & Status Pendaftaran -->
        <div class="admin-card p-6 md:p-8 space-y-6">
            <input type="hidden" name="posters_submitted" value="1">
            <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-images text-primary-600"></i>
                        Poster & Brosur Resmi PPDB (Multi-Gambar)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Upload poster PPDB (bisa lebih dari 1 gambar). Pada halaman publik, poster akan otomatis bergantian secara berkala dengan nyaman.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-700 w-fit">
                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                    Otomatis Kompresi WebP
                </span>
            </div>

            @php
                $rawPosters = $ppdbSettings['ppdb.poster_images'] ?? null;
                $currentPosters = [];
                if (!empty($rawPosters)) {
                    $decoded = json_decode($rawPosters, true);
                    if (is_array($decoded)) {
                        $currentPosters = $decoded;
                    }
                }
                if (empty($currentPosters) && !empty($ppdbSettings['ppdb.poster_image'])) {
                    $currentPosters = [$ppdbSettings['ppdb.poster_image']];
                }
            @endphp

            <div class="space-y-6">
                <!-- Multi-Poster Upload & Existing Grid -->
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/60 p-5 space-y-5">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Upload File Gambar Poster (JPG, PNG, WebP) - Bisa Pilih Lebih Dari 1
                            </label>
                            <span class="text-[11px] font-bold text-primary-600 bg-primary-50 px-2.5 py-0.5 rounded-full">
                                Multi-Upload Aktif
                            </span>
                        </div>
                        <input type="file" name="poster_images[]" id="poster_input" accept="image/jpeg,image/png,image/webp" multiple
                               class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-600 file:text-white hover:file:bg-primary-700 file:transition-colors file:cursor-pointer border border-slate-200 rounded-2xl p-2 bg-white"
                               onchange="previewNewPosters(event)" />
                        <p class="text-[11px] text-slate-500 mt-2 font-medium flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info text-primary-500"></i>
                            <span>Tips: Anda dapat memilih beberapa gambar sekaligus dengan menahan tombol <strong>Ctrl</strong> (atau <strong>Cmd</strong> di Mac) saat memilih file. Maksimal 5MB per file.</span>
                        </p>
                    </div>

                    <!-- New Selected Posters Preview Container (Client-side) -->
                    <div id="new-posters-preview-container" class="hidden space-y-3 pt-3 border-t border-slate-200">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-upload text-emerald-600"></i>
                                File Baru yang Akan Diunggah:
                            </h4>
                            <button type="button" onclick="clearNewPosters()" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 hover:underline">
                                Batalkan Pilihan File Baru
                            </button>
                        </div>
                        <div id="new-posters-preview-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3"></div>
                    </div>

                    <!-- Existing Saved Posters -->
                    <div class="space-y-3 pt-3 border-t border-slate-200">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-images text-primary-600"></i>
                                Poster yang Sedang Aktif Tersimpan ({{ count($currentPosters) }})
                            </h4>
                            <span class="text-[11px] text-slate-400">Klik ikon tempat sampah pada poster untuk menghapusnya</span>
                        </div>

                        <div id="existing-posters-empty" class="{{ count($currentPosters) > 0 ? 'hidden' : '' }} py-8 text-center text-slate-400 text-xs font-medium bg-white rounded-2xl border border-dashed border-slate-200">
                            <i class="fa-regular fa-image text-3xl mb-2 text-slate-300 block"></i>
                            Belum ada poster tersimpan. Silakan pilih dan upload poster di atas.
                        </div>

                        <div id="existing-posters-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach($currentPosters as $index => $poster)
                            <div class="poster-card relative group rounded-2xl overflow-hidden border border-slate-200 bg-white p-2 shadow-sm transition-all hover:shadow-md">
                                <input type="hidden" name="existing_posters[]" value="{{ $poster }}">
                                
                                <div class="aspect-[3/4] w-full rounded-xl overflow-hidden bg-slate-100 relative">
                                    <img src="{{ $poster }}" alt="Poster {{ $index + 1 }}" class="w-full h-full object-contain" />
                                    
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        <a href="{{ $poster }}" target="_blank" class="h-8 w-8 rounded-lg bg-white/90 hover:bg-white text-slate-800 grid place-items-center text-xs shadow-md" title="Buka Gambar">
                                            <i class="fa-solid fa-up-right-from-square"></i>
                                        </a>
                                        <button type="button" onclick="removePosterCard(this)" class="h-8 w-8 rounded-lg bg-rose-600 hover:bg-rose-700 text-white grid place-items-center text-xs shadow-md transition-colors" title="Hapus Poster Ini">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-2 flex items-center justify-between px-1">
                                    <span class="text-[11px] font-bold text-slate-700">Poster #{{ $index + 1 }}</span>
                                    <button type="button" onclick="removePosterCard(this)" class="text-rose-500 hover:text-rose-700 text-xs font-medium" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Status Pendaftaran & Hero Info Inputs -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Pendaftaran</label>
                        <select name="registration_status" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="Buka" {{ ($ppdbSettings['ppdb.registration_status'] ?? 'Buka') === 'Buka' ? 'selected' : '' }}>🟢 Sedang Dibuka (Aktif)</option>
                            <option value="Segera" {{ ($ppdbSettings['ppdb.registration_status'] ?? '') === 'Segera' ? 'selected' : '' }}>🟡 Segera Dibuka</option>
                            <option value="Tutup" {{ ($ppdbSettings['ppdb.registration_status'] ?? '') === 'Tutup' ? 'selected' : '' }}>🔴 Pendaftaran Ditutup</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Badge Tahun / Kategori</label>
                        <input type="text" name="hero_badge" value="{{ $ppdbSettings['ppdb.hero_badge'] ?? 'PPDB 2026/2027' }}"
                               placeholder="Contoh: PPDB 2026/2027"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Utama Halaman</label>
                    <input type="text" name="hero_title" value="{{ $ppdbSettings['ppdb.hero_title'] ?? 'Penerimaan Peserta Didik Baru' }}"
                           placeholder="Contoh: Penerimaan Peserta Didik Baru"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi / Subjudul</label>
                    <textarea name="hero_subtitle" rows="3"
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 leading-relaxed">{{ $ppdbSettings['ppdb.hero_subtitle'] ?? 'Bergabunglah bersama SMKS Muhammadiyah 2 Genteng, sekolah vokasi pusat keunggulan siap kerja di era industri 4.0.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 2: Link Pendaftaran & WhatsApp Panitia -->
        <div class="admin-card p-6 md:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-link text-primary-600"></i>
                    Link Pendaftaran Online & Narahubung WhatsApp
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Atur tautan formulir pendaftaran online dan nomor kontak panitia PPDB.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Link Formulir Online (Google Form / Portal PPDB)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-globe text-sm"></i>
                        </div>
                        <input type="url" name="registration_link" value="{{ $ppdbSettings['ppdb.registration_link'] ?? '' }}"
                               placeholder="https://forms.gle/... atau https://ppdb.smkstemda.sch.id"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Jika diisi, tombol 'Daftar Sekarang Online' akan mengarahkan calon siswa langsung ke link ini.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor WhatsApp Panitia</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-600">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                        </div>
                        <input type="text" name="whatsapp_number" value="{{ $ppdbSettings['ppdb.whatsapp_number'] ?? '081236453325' }}"
                               placeholder="Contoh: 081236453325"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Posko / Narahubung</label>
                    <input type="text" name="hotline_name" value="{{ $ppdbSettings['ppdb.hotline_name'] ?? 'Panitia PPDB STEMDA' }}"
                           placeholder="Contoh: Panitia PPDB STEMDA"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pesan Template WhatsApp Otomatis</label>
                    <input type="text" name="whatsapp_text" value="{{ $ppdbSettings['ppdb.whatsapp_text'] ?? 'Halo Panitia PPDB SMKS Muhammadiyah 2 Genteng, saya ingin bertanya mengenai pendaftaran siswa baru.' }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Informasi Resmi</label>
                    <input type="email" name="email" value="{{ $ppdbSettings['ppdb.email'] ?? 'smkmudagenteng@gmail.com' }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Sekretariat PPDB</label>
                    <input type="text" name="address" value="{{ $ppdbSettings['ppdb.address'] ?? 'JL Hasanudin 103, Genteng Wetan - Genteng, Banyuwangi' }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                </div>
            </div>
        </div>

        <!-- Section 3: Informasi Jadwal & Syarat Pendaftaran -->
        <div class="admin-card p-6 md:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-primary-600"></i>
                    Informasi Jadwal, Syarat & Alur Pendaftaran
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Tuliskan poin-poin persyaratan dan jadwal pendaftaran (pisahkan tiap poin dengan baris baru).</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-calendar-check text-primary-600 mr-1"></i>
                        Jadwal Gelombang (1 Baris = 1 Item)
                    </label>
                    <textarea name="waves" rows="6"
                              placeholder="Gelombang 1: 1 Januari - 31 Maret 2026&#10;Gelombang 2: 1 April - 31 Mei 2026&#10;Gelombang 3: 1 Juni - 10 Juli 2026"
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500 leading-relaxed">{{ $ppdbSettings['ppdb.waves'] ?? "Gelombang 1: 1 Januari - 31 Maret 2026 (Potongan Khusus)&#10;Gelombang 2: 1 April - 31 Mei 2026&#10;Gelombang 3: 1 Juni - 10 Juli 2026 (Penutupan Kuota)" }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-file-lines text-primary-600 mr-1"></i>
                        Syarat Pendaftaran (1 Baris = 1 Syarat)
                    </label>
                    <textarea name="requirements" rows="6"
                              placeholder="Fotokopi Ijazah / SKL SMP/MTs (2 lembar)&#10;Fotokopi Kartu Keluarga & KTP Orang Tua&#10;Fotokopi Akta Kelahiran&#10;Pas Foto 3x4 background merah (4 lembar)"
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500 leading-relaxed">{{ $ppdbSettings['ppdb.requirements'] ?? "Fotokopi Ijazah / Surat Keterangan Lulus (SKL) SMP/MTs (2 Lembar)&#10;Fotokopi Kartu Keluarga (KK) & Akta Kelahiran (2 Lembar)&#10;Fotokopi KTP Orang Tua / Wali (2 Lembar)&#10;Pas Foto 3x4 berwarna (4 Lembar)&#10;Mengisi Formulir Pendaftaran Online / Offline" }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-route text-primary-600 mr-1"></i>
                        Alur Pendaftaran (1 Baris = 1 Langkah)
                    </label>
                    <textarea name="steps" rows="6"
                              placeholder="1. Pendaftaran Online / Datang Langsung ke Posko&#10;2. Penyerahan & Verifikasi Berkas Persyaratan&#10;3. Tes Bakat Minat & Wawancara Kejuruan&#10;4. Pengumuman & Daftar Ulang Siswa Baru"
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500 leading-relaxed">{{ $ppdbSettings['ppdb.steps'] ?? "1. Mengisi formulir pendaftaran secara online atau datang langsung ke posko PPDB STEMDA.&#10;2. Menyerahkan berkas persyaratan administrasi ke panitia.&#10;3. Mengikuti tes peminatan kejuruan & wawancara singkat.&#10;4. Menyelesaikan proses daftar ulang dan pengukuran seragam." }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4 pt-4">
            <button type="submit" class="px-8 py-4 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm shadow-xl shadow-primary-600/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Pengaturan PPDB</span>
            </button>
        </div>
    </form>

    <!-- Archive Messages Section -->
    @if(count($contacts) > 0)
    <div class="admin-card overflow-hidden mt-12">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-inbox text-slate-500"></i>
                Arsip Pesan Masuk Lama ({{ count($contacts) }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pengirim</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Subjek & Pesan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($contacts as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs shrink-0 uppercase">
                                    {{ substr($item->name, 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">{{ $item->name }}</h4>
                                    <p class="text-[10px] text-slate-400">{{ $item->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-md">
                                <h5 class="font-bold text-slate-700 text-xs truncate">{{ $item->subject ?? 'Tanpa Subjek' }}</h5>
                                <p class="text-xs text-slate-400 mt-0.5 line-clamp-1 italic">"{{ Str::limit($item->message, 80) }}"</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold text-slate-500">{{ $item->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="confirmDelete({{ $item->id }})" class="h-9 w-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all inline-flex items-center justify-center shadow-sm border border-red-200" title="Hapus Pesan">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<script>
function previewNewPosters(event) {
    const files = event.target.files;
    const container = document.getElementById('new-posters-preview-container');
    const grid = document.getElementById('new-posters-preview-grid');
    grid.innerHTML = '';
    
    if (!files || files.length === 0) {
        container.classList.add('hidden');
        return;
    }
    
    container.classList.remove('hidden');
    
    Array.from(files).forEach((file, index) => {
        if (!file.type.startsWith('image/')) return;
        
        const card = document.createElement('div');
        card.className = 'relative group rounded-2xl overflow-hidden border border-emerald-300 bg-white p-2 shadow-sm flex flex-col';
        
        const imgWrapper = document.createElement('div');
        imgWrapper.className = 'aspect-[3/4] w-full rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center relative';
        
        const img = document.createElement('img');
        img.className = 'w-full h-full object-contain';
        img.alt = file.name;
        
        const reader = new FileReader();
        reader.onload = (e) => {
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        imgWrapper.appendChild(img);
        card.appendChild(imgWrapper);
        
        const info = document.createElement('div');
        info.className = 'mt-2 text-[11px] text-slate-600 truncate';
        info.innerHTML = `<span class="inline-block px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold text-[10px] mr-1">Baru</span>${file.name} (${(file.size / 1024).toFixed(0)} KB)`;
        card.appendChild(info);
        
        grid.appendChild(card);
    });
}

function clearNewPosters() {
    const input = document.getElementById('poster_input');
    if (input) input.value = '';
    const container = document.getElementById('new-posters-preview-container');
    const grid = document.getElementById('new-posters-preview-grid');
    if (grid) grid.innerHTML = '';
    if (container) container.classList.add('hidden');
}

function removePosterCard(button) {
    if (confirm('Hapus poster ini dari daftar? (Perubahan akan disimpan permanen saat Anda menekan tombol "Simpan Pengaturan PPDB")')) {
        const card = button.closest('.poster-card');
        if (card) {
            card.remove();
            checkExistingPostersCount();
        }
    }
}

function checkExistingPostersCount() {
    const list = document.querySelectorAll('#existing-posters-grid .poster-card');
    const emptyMsg = document.getElementById('existing-posters-empty');
    if (list.length === 0 && emptyMsg) {
        emptyMsg.classList.remove('hidden');
    }
}

function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus arsip pesan ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/contacts/${id}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
