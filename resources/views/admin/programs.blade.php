@extends('admin.layout')

@section('title', 'Keahlian')
@section('breadcrumb', 'Keahlian')
@section('page-title', 'Manajemen Keahlian')

@section('content')
@php
    $pageAction = $page['action'] ?? null;
    $existingActivityItems = [];

    if ($pageAction === 'edit' && $program) {
        $oldExistingUrls = old('existing_activity_images');
        $oldExistingCaptions = old('existing_activity_captions');

        if (is_array($oldExistingUrls)) {
            foreach ($oldExistingUrls as $index => $url) {
                $normalizedUrl = trim((string) $url);
                if ($normalizedUrl === '') {
                    continue;
                }

                $existingActivityItems[] = [
                    'url' => $normalizedUrl,
                    'caption' => trim((string) ($oldExistingCaptions[$index] ?? '')),
                ];
            }
        } else {
            foreach (($program->activity_images ?? []) as $item) {
                $normalizedUrl = trim((string) ($item['url'] ?? ''));
                if ($normalizedUrl === '') {
                    continue;
                }

                $existingActivityItems[] = [
                    'url' => $normalizedUrl,
                    'caption' => trim((string) ($item['caption'] ?? '')),
                ];
            }
        }
    }
@endphp

@if($pageAction === 'create' || $pageAction === 'edit')
    <div class="space-y-6">
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $pageAction === 'edit' ? 'Edit Keahlian' : 'Tambah Keahlian' }}</h2>
                <p class="text-slate-500 text-sm">Kelola konten, logo/banner, dan galeri kegiatan.</p>
            </div>
            <a href="{{ route('admin.programs.index') }}" class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-bold text-sm hover:bg-slate-200 transition-all">Kembali</a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in items-center">
                <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif
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

        <div class="admin-card p-8">
            <form method="POST" enctype="multipart/form-data" action="{{ $pageAction === 'edit' ? route('admin.programs.update', $program) : route('admin.programs.store') }}" class="space-y-6">
                @csrf
                @if($pageAction === 'edit')
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Keahlian</label>
                        <input type="text" name="name" value="{{ old('name', $program->name ?? '') }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Slug (Optional)</label>
                        <input type="text" name="slug" value="{{ old('slug', $program->slug ?? '') }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Urutan</label>
                        <input type="number" name="order" value="{{ old('order', $program->order ?? 0) }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Durasi (Tahun)</label>
                        <input type="number" min="1" max="6" name="duration" value="{{ old('duration', $program->duration ?? 3) }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Status</label>
                        @php
                            $activeValue = old('is_active', isset($program) ? ((int)($program->is_active ?? false)) : 1);
                        @endphp
                        <select name="is_active" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium">
                            <option value="1" {{ (string)$activeValue === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ (string)$activeValue === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Ikon (Lucide Name, Optional)</label>
                    <input type="text" name="icon" value="{{ old('icon', $program->icon ?? '') }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="car, monitor, ...">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Konten / Deskripsi Keahlian</label>
                    <textarea id="field_description" name="description" class="hidden">{{ old('description', $program->description ?? '') }}</textarea>
                    <div class="rounded-2xl overflow-hidden border border-slate-200 bg-white">
                        <div id="quill_description" class="min-h-[220px]"></div>
                    </div>
                    <p class="text-xs text-slate-500">Gunakan toolbar untuk format teks dan tombol gambar untuk upload.</p>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Kelas Industri (Optional)</label>
                    <textarea id="field_industry" name="industry_class_content" class="hidden">{{ old('industry_class_content', $program->industry_class_content ?? '') }}</textarea>
                    <div class="rounded-2xl overflow-hidden border border-slate-200 bg-white">
                        <div id="quill_industry" class="min-h-[220px]"></div>
                    </div>
                    <p class="text-xs text-slate-500">Opsional. Anda bisa menambahkan teks dan gambar untuk section Kelas Industri.</p>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Logo / Banner</label>
                    @if($pageAction === 'edit' && ($program->image ?? null))
                        <div class="mb-3 rounded-2xl overflow-hidden border border-slate-200 h-44 bg-slate-100 flex items-center justify-center">
                            <img src="{{ str_starts_with($program->image, 'http') ? $program->image : asset(ltrim($program->image, '/')) }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                            <div class="h-full w-full items-center justify-center text-slate-300 hidden"><i data-lucide="layers" class="h-8 w-8"></i></div>
                        </div>
                    @endif
                    <input id="image_file" type="file" name="image_file" class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-primary-600 file:text-white file:font-bold hover:file:bg-primary-700">
                    <div id="image_preview" class="hidden mt-3 rounded-2xl overflow-hidden border border-slate-200 h-44 bg-slate-100">
                        <img id="image_preview_img" src="" class="h-full w-full object-cover">
                    </div>
                </div>

                <!-- MODUL KOMPETENSI (REPEATER) -->
                <div class="space-y-3 pt-4 border-t border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-widest flex items-center gap-2">
                                <i data-lucide="target" class="w-4 h-4 text-primary-600"></i>
                                <span>Modul Kompetensi Keahlian (Custom Cards)</span>
                            </label>
                            <p class="text-xs text-slate-500">Kustomisasi modul kompetensi yang dipelajari. Jika dikosongkan, sistem akan menggunakan modul standar bawaan.</p>
                        </div>
                        <button type="button" onclick="addCompetencyRow()" class="px-4 py-2 rounded-xl bg-primary-50 text-primary-600 hover:bg-primary-100 text-xs font-bold transition-all inline-flex items-center gap-1.5 self-start sm:self-auto">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            Tambah Modul
                        </button>
                    </div>

                    <div id="competencies_container" class="space-y-3">
                        @php
                            $compItems = old('competencies', $program->competencies ?? []);
                            if (!is_array($compItems)) $compItems = [];
                        @endphp

                        @forelse($compItems as $cIndex => $cItem)
                            <div class="competency-row p-4 rounded-2xl bg-slate-50 border border-slate-200 grid grid-cols-1 md:grid-cols-12 gap-3 items-start">
                                <div class="md:col-span-4 space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase">Judul Modul</label>
                                    <input type="text" name="competencies[{{ $cIndex }}][title]" value="{{ $cItem['title'] ?? '' }}" placeholder="Contoh: Perawatan Mesin" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                </div>
                                <div class="md:col-span-5 space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase">Keterangan Singkat</label>
                                    <input type="text" name="competencies[{{ $cIndex }}][desc]" value="{{ $cItem['desc'] ?? '' }}" placeholder="Contoh: Diagnosa dan overhaul bensin & diesel" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                </div>
                                <div class="md:col-span-2 space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase">Ikon</label>
                                    <select name="competencies[{{ $cIndex }}][icon]" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none">
                                        <option value="Wrench" {{ ($cItem['icon'] ?? '') === 'Wrench' ? 'selected' : '' }}>Wrench (Kunci/Teknik)</option>
                                        <option value="Cpu" {{ ($cItem['icon'] ?? '') === 'Cpu' ? 'selected' : '' }}>Cpu (Komputer/Elektronik)</option>
                                        <option value="Zap" {{ ($cItem['icon'] ?? '') === 'Zap' ? 'selected' : '' }}>Zap (Listrik/Power)</option>
                                        <option value="Layers" {{ ($cItem['icon'] ?? '') === 'Layers' ? 'selected' : '' }}>Layers (Sistem/Lapisan)</option>
                                        <option value="Hammer" {{ ($cItem['icon'] ?? '') === 'Hammer' ? 'selected' : '' }}>Hammer (Mekanik/Bodi)</option>
                                        <option value="Shield" {{ ($cItem['icon'] ?? '') === 'Shield' ? 'selected' : '' }}>Shield (Keamanan/Safety)</option>
                                        <option value="Palette" {{ ($cItem['icon'] ?? '') === 'Palette' ? 'selected' : '' }}>Palette (Desain/Animasi)</option>
                                        <option value="ChefHat" {{ ($cItem['icon'] ?? '') === 'ChefHat' ? 'selected' : '' }}>ChefHat (Kuliner/Boga)</option>
                                        <option value="Factory" {{ ($cItem['icon'] ?? '') === 'Factory' ? 'selected' : '' }}>Factory (Industri/Pabrik)</option>
                                        <option value="Sparkles" {{ ($cItem['icon'] ?? '') === 'Sparkles' ? 'selected' : '' }}>Sparkles (Kreasi/Art)</option>
                                    </select>
                                </div>
                                <div class="md:col-span-1 pt-6 text-right">
                                    <button type="button" onclick="this.closest('.competency-row').remove()" class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p id="empty_competencies_text" class="text-xs text-slate-400 italic">Belum ada modul kustom. Sistem akan menampilkan modul bawaan kejuruan di halaman publik.</p>
                        @endforelse
                    </div>
                </div>

                <!-- PROSPEK KARIR LULUSAN (REPEATER) -->
                <div class="space-y-3 pt-4 border-t border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-widest flex items-center gap-2">
                                <i data-lucide="briefcase" class="w-4 h-4 text-primary-600"></i>
                                <span>Peluang & Prospek Karir Lulusan</span>
                            </label>
                            <p class="text-xs text-slate-500">Kustomisasi profesi lulusan. Jika dikosongkan, sistem akan menggunakan karir standar bawaan.</p>
                        </div>
                        <button type="button" onclick="addCareerRow()" class="px-4 py-2 rounded-xl bg-primary-50 text-primary-600 hover:bg-primary-100 text-xs font-bold transition-all inline-flex items-center gap-1.5 self-start sm:self-auto">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            Tambah Karir
                        </button>
                    </div>

                    <div id="careers_container" class="space-y-3">
                        @php
                            $careerItems = old('careers', $program->careers ?? []);
                            if (!is_array($careerItems)) $careerItems = [];
                        @endphp

                        @forelse($careerItems as $rIndex => $rItem)
                            <div class="career-row p-4 rounded-2xl bg-slate-50 border border-slate-200 grid grid-cols-1 md:grid-cols-12 gap-3 items-start">
                                <div class="md:col-span-4 space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase">Nama Karir / Profesi</label>
                                    <input type="text" name="careers[{{ $rIndex }}][title]" value="{{ $rItem['title'] ?? '' }}" placeholder="Contoh: Teknisi Ahli Otomotif" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                </div>
                                <div class="md:col-span-7 space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase">Keterangan / Tempat Kerja</label>
                                    <input type="text" name="careers[{{ $rIndex }}][desc]" value="{{ $rItem['desc'] ?? '' }}" placeholder="Contoh: Mekanik spesialis di bengkel resmi APM" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                </div>
                                <div class="md:col-span-1 pt-6 text-right">
                                    <button type="button" onclick="this.closest('.career-row').remove()" class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p id="empty_careers_text" class="text-xs text-slate-400 italic">Belum ada karir kustom. Sistem akan menampilkan karir bawaan kejuruan di halaman publik.</p>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Galeri Kegiatan (Multiple)</label>
                    <p class="text-xs text-slate-500">Setiap gambar bisa diberi caption singkat untuk tampil di halaman publik.</p>
                    @if($pageAction === 'edit' && count($existingActivityItems) > 0)
                        <div class="rounded-2xl border border-slate-200 bg-white p-3">
                            <div class="flex items-center justify-between gap-3 px-1 pb-3">
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Gambar Tersimpan</p>
                                <span class="text-[11px] font-medium text-slate-400">{{ count($existingActivityItems) }} item</span>
                            </div>
                            <div class="grid grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-3 max-h-[36rem] overflow-y-auto pr-1">
                                @foreach($existingActivityItems as $index => $item)
                                    <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-50">
                                        <div class="h-32 bg-slate-100 flex items-center justify-center overflow-hidden">
                                            <img src="{{ str_starts_with($item['url'], 'http') ? $item['url'] : asset(ltrim($item['url'], '/')) }}" class="w-full h-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                            <div class="h-full w-full items-center justify-center text-slate-300 hidden"><i data-lucide="image" class="h-6 w-6"></i></div>
                                        </div>
                                        <div class="space-y-2 p-3 bg-white">
                                            <input type="hidden" name="existing_activity_images[]" value="{{ $item['url'] }}">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-[0.16em]">Caption {{ $index + 1 }}</label>
                                            <input
                                                type="text"
                                                name="existing_activity_captions[]"
                                                value="{{ $item['caption'] }}"
                                                maxlength="255"
                                                placeholder="Contoh: Praktik tune up mesin di bengkel sekolah"
                                                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 text-sm"
                                            >
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <input id="activity_images" type="file" name="activity_images[]" multiple class="block w-full text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-slate-800 file:text-white file:font-bold hover:file:bg-slate-900">
                    <div id="activity_preview" class="hidden mt-3 rounded-2xl border border-slate-200 bg-white p-3">
                        <div class="flex items-center justify-between gap-3 px-1 pb-3">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Gambar Baru</p>
                            <span id="activity_preview_count" class="text-[11px] font-medium text-slate-400">0 item</span>
                        </div>
                        <div id="activity_preview_grid" class="grid grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-3 max-h-[36rem] overflow-y-auto pr-1"></div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="submit" class="btn-primary">{{ $pageAction === 'edit' ? 'Simpan Perubahan' : 'Simpan Keahlian' }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            function initQuill({ containerId, fieldId, placeholder }) {
                const container = document.getElementById(containerId);
                const field = document.getElementById(fieldId);
                if (!container || !field || !window.Quill) return null;

                const quill = new window.Quill(container, {
                    theme: 'snow',
                    placeholder: placeholder || '',
                    modules: {
                        toolbar: {
                            container: [
                                [{ header: [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ list: 'ordered' }, { list: 'bullet' }],
                                [{ align: [] }],
                                ['link', 'image'],
                                ['clean'],
                            ],
                            handlers: {
                                image: function () {
                                    const input = document.createElement('input');
                                    input.type = 'file';
                                    input.accept = 'image/*';
                                    input.click();

                                    input.addEventListener('change', async () => {
                                        const file = input.files && input.files[0];
                                        if (!file) return;

                                        const fd = new FormData();
                                        fd.append('image_file', file);

                                        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                                        const res = await fetch(@json(route('admin.programs.editor-images.store')), {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': csrf,
                                                'Accept': 'application/json',
                                            },
                                            body: fd,
                                        });

                                        if (!res.ok) return;
                                        const json = await res.json();
                                        const url = json?.url;
                                        if (!url) return;

                                        const range = quill.getSelection(true);
                                        quill.insertEmbed(range ? range.index : 0, 'image', url, 'user');
                                        quill.setSelection((range ? range.index : 0) + 1, 0, 'user');
                                    });
                                }
                            }
                        }
                    }
                });

                const initial = field.value || '';
                if (initial) {
                    quill.clipboard.dangerouslyPasteHTML(initial);
                }

                return { quill, field };
            }

            const descriptionEditor = initQuill({
                containerId: 'quill_description',
                fieldId: 'field_description',
                placeholder: 'Tulis konten keahlian di sini...',
            });

            const industryEditor = initQuill({
                containerId: 'quill_industry',
                fieldId: 'field_industry',
                placeholder: 'Tulis konten kelas industri di sini...',
            });

            const form = document.querySelector('form[action*="admin/programs"]');
            form?.addEventListener('submit', () => {
                const normalize = (html) => {
                    const v = (html || '').trim();
                    if (!v || v === '<p><br></p>' || v === '<p></p>') return '';
                    return v;
                };

                if (descriptionEditor) {
                    descriptionEditor.field.value = normalize(descriptionEditor.quill.root.innerHTML);
                }
                if (industryEditor) {
                    industryEditor.field.value = normalize(industryEditor.quill.root.innerHTML);
                }
            });

            const imageInput = document.getElementById('image_file');
            const imagePreview = document.getElementById('image_preview');
            const imagePreviewImg = document.getElementById('image_preview_img');

            if (imageInput) {
                imageInput.addEventListener('change', function () {
                    const file = this.files && this.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        imagePreviewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                });
            }

            const activityInput = document.getElementById('activity_images');
            const activityPreview = document.getElementById('activity_preview');
            const activityGrid = document.getElementById('activity_preview_grid');
            const activityCount = document.getElementById('activity_preview_count');
            if (activityInput && activityGrid) {
                activityInput.addEventListener('change', function () {
                    activityGrid.innerHTML = '';
                    const files = this.files ? Array.from(this.files) : [];
                    if (files.length === 0) {
                        activityPreview.classList.add('hidden');
                        if (activityCount) activityCount.textContent = '0 item';
                        return;
                    }
                    if (activityCount) {
                        activityCount.textContent = `${files.length} item`;
                    }
                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'rounded-2xl overflow-hidden border border-slate-200 bg-slate-50';
                            const media = document.createElement('div');
                            media.className = 'h-32 bg-slate-100';
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'w-full h-full object-cover';
                            media.appendChild(img);

                            const content = document.createElement('div');
                            content.className = 'space-y-2 p-3 bg-white';

                            const label = document.createElement('label');
                            label.className = 'block text-[10px] font-bold text-slate-500 uppercase tracking-[0.16em]';
                            label.textContent = `Caption ${index + 1}`;

                            const input = document.createElement('input');
                            input.type = 'text';
                            input.name = 'new_activity_captions[]';
                            input.maxLength = 255;
                            input.placeholder = 'Contoh: Praktik tune up mesin di bengkel sekolah';
                            input.className = 'w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 text-sm';

                            content.appendChild(label);
                            content.appendChild(input);
                            wrapper.appendChild(media);
                            wrapper.appendChild(content);
                            activityGrid.appendChild(wrapper);
                        };
                        reader.readAsDataURL(file);
                    });
                    activityPreview.classList.remove('hidden');
                });
            }

            window.addCompetencyRow = function () {
                const container = document.getElementById('competencies_container');
                const emptyText = document.getElementById('empty_competencies_text');
                if (emptyText) emptyText.remove();

                const index = container.querySelectorAll('.competency-row').length + '_' + Date.now();
                const row = document.createElement('div');
                row.className = 'competency-row p-4 rounded-2xl bg-slate-50 border border-slate-200 grid grid-cols-1 md:grid-cols-12 gap-3 items-start animate-fade-in';
                row.innerHTML = `
                    <div class="md:col-span-4 space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Judul Modul</label>
                        <input type="text" name="competencies[${index}][title]" placeholder="Contoh: Perawatan Mesin" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    </div>
                    <div class="md:col-span-5 space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Keterangan Singkat</label>
                        <input type="text" name="competencies[${index}][desc]" placeholder="Contoh: Diagnosa dan overhaul bensin & diesel" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    </div>
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Ikon</label>
                        <select name="competencies[${index}][icon]" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none">
                            <option value="Wrench">Wrench (Kunci/Teknik)</option>
                            <option value="Cpu">Cpu (Komputer/Elektronik)</option>
                            <option value="Zap">Zap (Listrik/Power)</option>
                            <option value="Layers">Layers (Sistem/Lapisan)</option>
                            <option value="Hammer">Hammer (Mekanik/Bodi)</option>
                            <option value="Shield">Shield (Keamanan/Safety)</option>
                            <option value="Palette">Palette (Desain/Animasi)</option>
                            <option value="ChefHat">ChefHat (Kuliner/Boga)</option>
                            <option value="Factory">Factory (Industri/Pabrik)</option>
                            <option value="Sparkles">Sparkles (Kreasi/Art)</option>
                        </select>
                    </div>
                    <div class="md:col-span-1 pt-6 text-right">
                        <button type="button" onclick="this.closest('.competency-row').remove()" class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                `;
                container.appendChild(row);
                if (window.lucide) {
                    lucide.createIcons();
                }
            };

            window.addCareerRow = function () {
                const container = document.getElementById('careers_container');
                const emptyText = document.getElementById('empty_careers_text');
                if (emptyText) emptyText.remove();

                const index = container.querySelectorAll('.career-row').length + '_' + Date.now();
                const row = document.createElement('div');
                row.className = 'career-row p-4 rounded-2xl bg-slate-50 border border-slate-200 grid grid-cols-1 md:grid-cols-12 gap-3 items-start animate-fade-in';
                row.innerHTML = `
                    <div class="md:col-span-4 space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Nama Karir / Profesi</label>
                        <input type="text" name="careers[${index}][title]" placeholder="Contoh: Teknisi Ahli Otomotif" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    </div>
                    <div class="md:col-span-7 space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Keterangan / Tempat Kerja</label>
                        <input type="text" name="careers[${index}][desc]" placeholder="Contoh: Mekanik spesialis di bengkel resmi APM" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    </div>
                    <div class="md:col-span-1 pt-6 text-right">
                        <button type="button" onclick="this.closest('.career-row').remove()" class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                `;
                container.appendChild(row);
                if (window.lucide) {
                    lucide.createIcons();
                }
            };
        })();
    </script>
@endif
@if($pageAction !== 'create' && $pageAction !== 'edit')
    <div class="animate__animated animate__fadeInUp">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Manajemen Keahlian</h2>
                <p class="text-slate-500 text-sm">Tambah, edit konten, dan atur logo untuk menu Keahlian di website.</p>
            </div>
            <a href="{{ route('admin.programs.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i>
                Tambah Keahlian
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 animate-fade-in items-center mb-6">
                <i data-lucide="check-circle" class="text-emerald-500 h-5 w-5 shrink-0"></i>
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex gap-3 animate-fade-in mb-6">
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

        <div class="admin-card overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-primary-600"></i>
                    Daftar Keahlian
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/30">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Logo</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Urutan</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($programs as $item)
                            @php
                                $_img = is_object($item) ? $item->image ?? null : (is_array($item) ? $item['image'] ?? null : null);
                                $_name = is_object($item) ? $item->name ?? null : (is_array($item) ? $item['name'] ?? null : null);
                                $_slug = is_object($item) ? $item->slug ?? null : (is_array($item) ? $item['slug'] ?? null : null);
                                $_status = is_object($item) ? $item->is_active ?? true : (is_array($item) ? $item['is_active'] ?? true : true);
                                $_order = is_object($item) ? $item->order ?? 0 : (is_array($item) ? $item['order'] ?? 0 : 0);
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="h-12 w-16 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center">
                                        @if($_img)
                                            <img src="{{ str_starts_with($_img, 'http') ? $_img : asset(ltrim($_img, '/')) }}" alt="{{ $_name ?? 'Keahlian' }}" class="h-full w-full object-cover" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='inline';">
                                            <span class="text-[10px] font-bold text-slate-400 hidden">No Img</span>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-400">No Img</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-sm group-hover:text-primary-600 transition-colors">{{ $_name ?? '-' }}</h4>
                                        <p class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-widest">{{ $_slug ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($_status)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-400 border border-slate-200 uppercase tracking-wider">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-bold text-slate-500">{{ $_order }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.programs.edit', $item) }}" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit Program">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.programs.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Program">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                            <i class="fa-solid fa-graduation-cap text-3xl"></i>
                                        </div>
                                        <p class="text-slate-400 text-sm font-medium italic">Belum ada data keahlian yang ditambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
