@extends('admin.layout')

@section('title', 'Contact Details')
@section('breadcrumb', 'Contacts / Detail')
@section('page-title', 'Contact Details')

@section('content')
<div class="animate__animated animate__fadeInUp">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.contacts.index') }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-primary-600 hover:border-primary-100 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Detail Pesan</h2>
                <p class="text-slate-500 text-sm">Informasi lengkap pesan dari {{ $contact->name }}.</p>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-primary-600 text-white flex items-center justify-center shadow-lg shadow-primary-200">
                        <i class="fa-solid fa-envelope-open-text text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">{{ $contact->subject ?? 'Tanpa Subjek' }}</h3>
                        <p class="text-xs text-slate-400 font-medium">Diterima pada {{ $contact->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold {{ $contact->is_read ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }} uppercase tracking-wider">
                        <span class="h-1.5 w-1.5 rounded-full {{ $contact->is_read ? 'bg-emerald-500' : 'bg-amber-500 animate-pulse' }}"></span>
                        {{ $contact->is_read ? 'Sudah Dibaca' : 'Pesan Baru' }}
                    </span>
                </div>
            </div>

            <div class="p-8 space-y-8">
                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Pengirim</p>
                        <p class="font-bold text-slate-800">{{ $contact->name }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat Email</p>
                        <p class="font-bold text-slate-800">{{ $contact->email }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nomor Telepon</p>
                        <p class="font-bold text-slate-800">{{ $contact->phone ?? '-' }}</p>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 relative">
                    <i class="fa-solid fa-quote-left text-4xl text-slate-200 absolute top-4 left-4"></i>
                    <div class="relative z-10 pl-8 pt-4">
                        <p class="text-slate-600 leading-relaxed font-medium whitespace-pre-line">{{ $contact->message }}</p>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="flex items-center justify-between pt-4">
                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Hapus pesan ini secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <i class="fa-solid fa-trash"></i>
                            Hapus Pesan
                        </button>
                    </form>

                    <a href="mailto:{{ $contact->email }}" class="btn-primary">
                        <i class="fa-solid fa-reply"></i>
                        Balas via Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
