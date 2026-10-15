@extends('admin.layout')

@section('title', 'Backup')
@section('breadcrumb', 'Backup')
@section('page-title', 'Backup Data')

@section('content')
<div class="space-y-8">
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-emerald-800 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-6 py-4 text-red-800 font-semibold">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-6 py-4 text-red-800">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="font-semibold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @can('admin.backup.export')
        <div class="admin-card p-8">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-200">
                    <i data-lucide="download" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Export Data</h3>
                    <p class="text-sm text-slate-600 mt-1">Unduh file backup (SQL) berisi seluruh data admin.</p>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.backup.export') }}" class="btn-primary">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Export Backup
                </a>
            </div>
        </div>
        @endcan

        @can('admin.backup.import')
        <div class="admin-card p-8">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-200">
                    <i data-lucide="upload" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Import Data</h3>
                    <p class="text-sm text-slate-600 mt-1">Import hanya dari file hasil Export Backup.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.backup.import') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">File Backup (SQL)</label>
                    <input type="file" name="backup_file" accept=".sql,text/plain,application/sql,application/octet-stream" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi</label>
                    <input type="text" name="confirm" placeholder="Ketik: IMPORT" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    <p class="mt-2 text-xs font-semibold text-slate-500">
                        Import akan menghapus (truncate) data lama lalu menggantinya dari file backup.
                    </p>
                </div>

                <button type="submit" class="btn-primary">
                    <i data-lucide="upload" class="h-4 w-4"></i>
                    Import Backup
                </button>
            </form>
        </div>
        @endcan
    </div>
</div>
@endsection
