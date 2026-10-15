@extends('admin.layout')

@section('title', 'Agendas Management')
@section('breadcrumb', 'Agendas')
@section('page-title', 'Agendas Management')

@section('content')
<div class="animate__animated animate__fadeInUp">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Agenda</h2>
            <p class="text-slate-500 text-sm">Kelola jadwal kegiatan dan agenda penting sekolah.</p>
        </div>
        <button onclick="openModal('create')" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            Tambah Agenda Baru
        </button>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-primary-600"></i>
                Daftar Agenda
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Agenda</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Waktu Pelaksanaan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($agendas as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-slate-100 flex flex-col items-center justify-center border border-slate-200 shrink-0">
                                    <span class="text-[10px] font-bold text-primary-600 uppercase">{{ $item->start_date->format('M') }}</span>
                                    <span class="text-lg font-bold text-slate-800 leading-none">{{ $item->start_date->format('d') }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm group-hover:text-primary-600 transition-colors line-clamp-1">{{ $item->title }}</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $item->start_date->format('d M Y') }} @if($item->end_date && $item->end_date != $item->start_date) - {{ $item->end_date->format('d M Y') }} @endif</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-700">{{ $item->start_date->format('d M Y') }}</span>
                                @if($item->end_date && $item->end_date != $item->start_date)
                                    <span class="text-[10px] text-slate-400 font-medium">s/d {{ $item->end_date->format('d M Y') }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->start_date->isPast())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-400 border border-slate-200 uppercase tracking-wider">Selesai</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-wider">Akan Datang</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openModal('edit', {{ $item->id }})" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="confirmDelete({{ $item->id }})" class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                    <i class="fa-solid fa-calendar-xmark text-3xl"></i>
                                </div>
                                <p class="text-slate-400 text-sm font-medium italic">Belum ada data agenda yang ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Agenda -->
<div id="agenda-modal" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-[32px] w-full max-w-2xl overflow-hidden shadow-2xl animate__animated animate__zoomIn animate__faster">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                <h3 id="modal-title" class="text-xl font-bold text-slate-900">Tambah Agenda</h3>
                <button onclick="closeModal()" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('admin.agendas.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div id="method-field"></div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Agenda</label>
                    <input type="text" name="title" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Cth: Ujian Tengah Semester">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none text-sm">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Lokasi</label>
                    <input type="text" name="location" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-slate-700 font-medium" placeholder="Cth: Aula Sekolah">
                </div>
                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-6 py-3.5 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Agenda</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(type, id = null) {
    const modal = document.getElementById('agenda-modal');
    const title = document.getElementById('modal-title');
    const form = modal.querySelector('form');
    const methodField = document.getElementById('method-field');

    modal.classList.remove('hidden');

    if (type === 'create') {
        title.innerText = 'Tambah Agenda Baru';
        form.action = "{{ route('admin.agendas.store') }}";
        methodField.innerHTML = '';
        form.reset();
    } else {
        title.innerText = 'Edit Agenda';
        form.action = `/admin/agendas/${id}`;
        methodField.innerHTML = '@method("PUT")';
    }
}

function closeModal() {
    document.getElementById('agenda-modal').classList.add('hidden');
}

function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus agenda ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/agendas/${id}`;
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
