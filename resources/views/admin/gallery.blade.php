@extends('admin.layout')

@section('title', 'Gallery Management')
@section('breadcrumb', 'Gallery')
@section('page-title', 'Manajemen Galeri')

@section('content')
<div class="space-y-8 animate__animated animate__fadeInUp">

    <!-- Flash & Error Notifications -->
    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex gap-3 animate-fade-in">
            <i data-lucide="alert-circle" class="text-red-500 h-5 w-5 shrink-0 mt-0.5"></i>
            <div>
                <p class="text-sm font-bold text-red-800">Gagal mengunggah media!</p>
                <ul class="mt-1 text-xs text-red-600 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate-fade-in shadow-sm">
            <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex items-center gap-3 animate-fade-in">
            <i data-lucide="alert-triangle" class="text-red-500 h-5 w-5 shrink-0"></i>
            <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    @if($viewMode === 'albums')
    <!-- ========================================== -->
    <!-- MODE 1: TAMPILAN ALBUM / FOLDER KATEGORI  -->
    <!-- ========================================== -->
    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-folder-open text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Album & Kategori Galeri</h2>
                        <p class="text-slate-500 text-xs mt-0.5">Pilih album untuk mengelola foto atau buat album baru.</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.gallery.categories.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-xs text-slate-700 hover:bg-slate-100 transition-all">
                    <i class="fa-solid fa-gear text-slate-500"></i>
                    Atur Kategori
                </a>
                <a href="{{ route('admin.gallery.categories.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-primary-200 text-primary-600 rounded-xl font-bold text-xs hover:bg-primary-50 transition-all shadow-sm">
                    <i class="fa-solid fa-folder-plus"></i>
                    Buat Album Baru
                </a>
                <button onclick="openModal('create', null, 'Umum')" class="btn-primary">
                    <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i>
                    Unggah Media Baru
                </button>
            </div>
        </div>

        <!-- Albums Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
            <!-- 🌟 Kartu Khusus: Semua Foto -->
            <div class="admin-card overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between border-2 border-primary-100 bg-gradient-to-b from-primary-50/30 to-white">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-primary-600 text-white shadow-sm uppercase tracking-wider">
                            <i class="fa-solid fa-layer-group text-[9px]"></i>
                            Semua Media
                        </span>
                        <span class="text-xs font-bold text-slate-500">{{ $totalCount }} Foto</span>
                    </div>

                    <!-- Mosaic 4 Photos Preview -->
                    <a href="{{ route('admin.gallery.index', ['category' => 'all']) }}" class="block relative aspect-video rounded-2xl overflow-hidden bg-slate-200/80 mb-4 group-hover:ring-4 group-hover:ring-primary-500/20 transition-all">
                        @if($allPreviews->count() > 0)
                            <div class="grid grid-cols-2 grid-rows-2 gap-1 h-full w-full p-1 bg-slate-900/5">
                                @foreach($allPreviews->take(4) as $p)
                                    <div class="overflow-hidden rounded-lg bg-slate-100 relative">
                                        <img src="{{ str_starts_with($p->image, 'http') ? $p->image : asset($p->image) }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.style.display='none'">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-full w-full flex flex-col items-center justify-center text-slate-300">
                                <i class="fa-solid fa-images text-3xl"></i>
                                <span class="text-[10px] mt-1 font-semibold">Belum Ada Foto</span>
                            </div>
                        @endif
                    </a>

                    <h3 class="font-bold text-slate-900 text-base group-hover:text-primary-600 transition-colors">
                        Arsip Seluruh Foto
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">Lihat dan kelola seluruh arsip galeri sekolah dalam satu daftar.</p>
                </div>

                <div class="p-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.gallery.index', ['category' => 'all']) }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1.5 group-hover:translate-x-1 transition-transform">
                        Buka Semua Arsip
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Kartu Tiap Album / Kategori -->
            @forelse($albums as $album)
            <div class="admin-card overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 uppercase tracking-wider">
                            <i class="fa-solid fa-folder text-primary-500 text-[10px]"></i>
                            Album
                        </span>
                        <span class="text-xs font-bold {{ $album['count'] > 0 ? 'text-primary-600 font-extrabold' : 'text-slate-400' }}">
                            {{ $album['count'] }} Foto
                        </span>
                    </div>

                    <!-- Mosaic Preview -->
                    <a href="{{ route('admin.gallery.index', ['category' => $album['name']]) }}" class="block relative aspect-video rounded-2xl overflow-hidden bg-slate-100 mb-4 group-hover:ring-4 group-hover:ring-primary-500/10 transition-all">
                        @if($album['previews']->count() > 0)
                            <div class="grid grid-cols-2 grid-rows-2 gap-1 h-full w-full p-1 bg-slate-900/5">
                                @foreach($album['previews']->take(4) as $p)
                                    <div class="overflow-hidden rounded-lg bg-slate-200 relative">
                                        <img src="{{ str_starts_with($p->image, 'http') ? $p->image : asset($p->image) }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.style.display='none'">
                                    </div>
                                @endforeach
                                @for($i = $album['previews']->count(); $i < 4; $i++)
                                    <div class="rounded-lg bg-slate-100 flex items-center justify-center text-slate-300">
                                        <i class="fa-solid fa-image text-xs"></i>
                                    </div>
                                @endfor
                            </div>
                        @else
                            <div class="h-full w-full flex flex-col items-center justify-center text-slate-300">
                                <i class="fa-solid fa-folder-open text-3xl text-slate-200"></i>
                                <span class="text-[10px] mt-1 font-semibold text-slate-400">Album Kosong</span>
                            </div>
                        @endif
                    </a>

                    <h3 class="font-bold text-slate-900 text-base line-clamp-1 group-hover:text-primary-600 transition-colors" title="{{ $album['name'] }}">
                        {{ $album['name'] }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">Kategori dokumentasi resmi</p>
                </div>

                <div class="p-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.gallery.index', ['category' => $album['name']]) }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1.5 group-hover:translate-x-1 transition-transform">
                        Buka Album
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                    <button onclick="openModal('create', null, '{{ addslashes($album['name']) }}')" class="h-8 px-3 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-primary-600 hover:text-white hover:border-primary-600 text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm" title="Upload ke album ini">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        Upload
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center admin-card">
                <div class="h-16 w-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i class="fa-solid fa-folder-open text-2xl"></i>
                </div>
                <h4 class="font-bold text-slate-700">Belum Ada Album</h4>
                <p class="text-xs text-slate-400 mt-1">Buat album kategori pertama Anda untuk mengelompokkan foto.</p>
                <a href="{{ route('admin.gallery.categories.create') }}" class="btn-primary mt-4 inline-flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Buat Kategori Album
                </a>
            </div>
            @endforelse

        </div>
    </div>

    @else
    <!-- =================================================== -->
    <!-- MODE 2: TAMPILAN ISI ALBUM FOTO + PAGINASI 16 FOTO  -->
    <!-- =================================================== -->
    <div class="space-y-6">
        <!-- Top Navigation Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.gallery.index') }}" class="h-11 w-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-all shadow-sm" title="Kembali ke Daftar Album">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-primary-50 text-primary-600 uppercase tracking-widest border border-primary-100">
                            {{ $selectedCategory === 'all' ? 'Semua Foto' : 'Album' }}
                        </span>
                        <h2 class="text-xl font-bold text-slate-800">
                            {{ $selectedCategory === 'all' ? 'Semua Arsip Foto Galeri' : $selectedCategory }}
                        </h2>
                    </div>
                    <p class="text-slate-500 text-xs mt-0.5">
                        Menampilkan total <strong>{{ $photos->total() }} foto</strong> • Halaman {{ $photos->currentPage() }} dari {{ $photos->lastPage() }}
                    </p>
                </div>
            </div>

            <!-- Actions inside Album -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Dropdown Ganti Album Cepat -->
                <div class="relative">
                    <select onchange="window.location.href=this.value" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 cursor-pointer">
                        <option value="{{ route('admin.gallery.index', ['category' => 'all']) }}" {{ $selectedCategory === 'all' ? 'selected' : '' }}>
                            📁 Semua Foto ({{ \App\Models\Gallery::count() }})
                        </option>
                        @foreach($categories as $cat)
                            @php
                                $catCount = \App\Models\Gallery::where('category', $cat->name)->count();
                            @endphp
                            <option value="{{ route('admin.gallery.index', ['category' => $cat->name]) }}" {{ $selectedCategory === $cat->name ? 'selected' : '' }}>
                                📁 {{ $cat->name }} ({{ $catCount }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button onclick="openModal('create', null, '{{ $selectedCategory === 'all' ? 'Umum' : addslashes($selectedCategory) }}')" class="btn-primary">
                    <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i>
                    Unggah Foto ke Sini
                </button>
            </div>
        </div>

        <!-- Photos Grid inside Album -->
        @if($photos->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($photos as $item)
                @php
                    if (!is_object($item)) continue;
                    $img = $item->image ?? '';
                    $imageUrl = $img;
                    if (is_string($img) && $img !== '' && !str_starts_with($img, 'http') && !str_starts_with($img, '/storage/')) {
                        $imageUrl = asset('storage/' . ltrim($img, '/'));
                    }
                    $itemCategory = trim((string)($item->category ?? 'Umum'));
                    if ($itemCategory === '') $itemCategory = 'Umum';
                    $dataItemJson = json_encode([
                        'id' => $item->id ?? null,
                        'title' => $item->title ?? '',
                        'category' => $itemCategory,
                        'image' => $imageUrl,
                    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
                @endphp
                <div class="admin-card overflow-hidden group hover:shadow-xl transition-all duration-300 flex flex-col justify-between" id="gallery-item-{{ $item->id ?? '' }}" data-item='{{ $dataItemJson }}'>
                    <div class="relative aspect-video overflow-hidden bg-slate-100">
                        @if($item->image)
                            <img src="{{ $imageUrl }}" alt="{{ $item->title ?? '' }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="display:none;" class="h-full w-full items-center justify-center text-slate-300 bg-slate-100">
                                <i class="fa-solid fa-image text-3xl"></i>
                            </div>
                        @else
                            <div class="h-full w-full flex items-center justify-center text-slate-300">
                                <i class="fa-solid fa-image text-3xl"></i>
                            </div>
                        @endif

                        <!-- Hover Action Buttons -->
                        <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2.5 backdrop-blur-[2px]">
                            <button onclick="openModal('edit', {{ $item->id }}, '{{ addslashes($itemCategory) }}')" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-xl font-bold" title="Edit Media">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="confirmDelete({{ $item->id }})" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-xl font-bold" title="Hapus Media">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-3 left-3">
                            @if($item->is_active ?? true)
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-500 text-white shadow-md uppercase tracking-wider">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-600 text-white shadow-md uppercase tracking-wider">Draft</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-5">
                        <h4 class="font-bold text-slate-800 text-sm line-clamp-1 mb-1" title="{{ $item->title }}">
                            {{ $item->title ?? 'Untitled Media' }}
                        </h4>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 text-[10px] text-slate-400 font-medium">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-bold">{{ $itemCategory }}</span>
                            <span>{{ $item->created_at ? $item->created_at->format('d M Y') : '' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination Bar -->
            <div class="p-6 bg-white rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs font-bold text-slate-500">
                    Menampilkan <span class="text-slate-800">{{ $photos->firstItem() ?? 0 }} - {{ $photos->lastItem() ?? 0 }}</span> dari <span class="text-primary-600">{{ $photos->total() }}</span> total foto
                </p>
                <div>
                    {{ $photos->links() }}
                </div>
            </div>
        @else
            <div class="py-20 admin-card flex flex-col items-center justify-center gap-4 bg-slate-50/50 border-dashed border-2 border-slate-200 shadow-none text-center">
                <div class="h-20 w-20 bg-white rounded-full flex items-center justify-center text-slate-300 shadow-sm">
                    <i class="fa-solid fa-folder-open text-4xl"></i>
                </div>
                <div>
                    <p class="text-slate-700 font-bold text-base">Album Ini Masih Kosong</p>
                    <p class="text-slate-400 text-xs mt-1">Mulai isi album "{{ $selectedCategory }}" dengan mengunggah foto dokumentasi.</p>
                </div>
                <button onclick="openModal('create', null, '{{ $selectedCategory === 'all' ? 'Umum' : addslashes($selectedCategory) }}')" class="btn-primary mt-2">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Unggah Foto Sekarang
                </button>
            </div>
        @endif
    </div>
    @endif

</div>

<!-- ========================================== -->
<!-- MODAL TAMBAH / EDIT MEDIA GALERI           -->
<!-- ========================================== -->
<div id="gallery-modal" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-[32px] w-full max-w-xl overflow-hidden shadow-2xl animate__animated animate__zoomIn animate__faster">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-images"></i>
                    </div>
                    <div>
                        <h3 id="modal-title" class="text-lg font-bold text-slate-900">Unggah Foto Galeri</h3>
                        <p class="text-xs text-slate-400">Pilih satu atau banyak foto untuk diunggah.</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div id="method-field"></div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Kategori / Album *</label>
                    <select id="gallery-category" name="category" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-bold cursor-pointer">
                        @php
                            $cats = $categories ?? collect();
                        @endphp
                        @forelse($cats as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @empty
                            <option value="Umum">Umum</option>
                        @endforelse
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Judul Media <span class="text-slate-400 font-normal">(opsional, default: nama file)</span></label>
                    <input type="text" id="gallery-title" name="title" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Cth: Dokumentasi Upacara Bendera">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Pilih File Foto *</label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-primary-400 rounded-2xl p-6 bg-slate-50/50 text-center transition-all">
                        <input type="file" id="gallery-image-file" name="image_files[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple class="hidden">
                        <label for="gallery-image-file" class="cursor-pointer flex flex-col items-center">
                            <div class="h-12 w-12 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center mb-2">
                                <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-700">Klik untuk memilih foto (Bisa pilih banyak foto)</span>
                            <span class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 5MB per file)</span>
                            <span id="file-count" class="mt-3 px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-xs font-bold hidden"></span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-2xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-cloud-arrow-up mr-2"></i>
                        Simpan & Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateFileCount() {
    const input = document.getElementById('gallery-image-file');
    const countBadge = document.getElementById('file-count');
    if (input && countBadge) {
        const n = input.files?.length || 0;
        if (n > 0) {
            countBadge.textContent = '✓ ' + n + ' file foto dipilih';
            countBadge.classList.remove('hidden');
        } else {
            countBadge.classList.add('hidden');
        }
    }
}

document.addEventListener('change', function (e) {
    if (e.target.id === 'gallery-image-file') updateFileCount();
});

function openModal(type, id = null, defaultCategory = null) {
    const modal = document.getElementById('gallery-modal');
    const title = document.getElementById('modal-title');
    const form = modal.querySelector('form');
    const methodField = document.getElementById('method-field');
    const titleInput = document.getElementById('gallery-title');
    const categorySelect = document.getElementById('gallery-category');
    const imageFileInput = document.getElementById('gallery-image-file');
    const fileCount = document.getElementById('file-count');

    modal.classList.remove('hidden');

    if (type === 'create') {
        title.innerText = 'Unggah Foto Galeri';
        form.action = "{{ route('admin.gallery.store') }}";
        methodField.innerHTML = '';
        form.reset();
        if (defaultCategory && categorySelect) {
            categorySelect.value = defaultCategory;
        }
        if (imageFileInput) imageFileInput.value = '';
        if (fileCount) fileCount.classList.add('hidden');
    } else {
        title.innerText = 'Edit Media Galeri';
        form.action = `/admin/gallery/${id}`;
        methodField.innerHTML = '@method("PUT")';

        const card = document.getElementById(`gallery-item-${id}`);
        const data = card?.dataset?.item ? JSON.parse(card.dataset.item) : null;
        if (data) {
            if (titleInput) titleInput.value = data.title || '';
            if (categorySelect) categorySelect.value = data.category || 'Umum';
            if (imageFileInput) imageFileInput.value = '';
            if (fileCount) fileCount.classList.add('hidden');
        }
    }
}

function closeModal() {
    document.getElementById('gallery-modal').classList.add('hidden');
}

function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus foto media ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/gallery/${id}`;
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
