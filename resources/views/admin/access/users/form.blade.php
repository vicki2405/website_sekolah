@extends('admin.layout')

@php($isEdit = (bool) ($user->id ?? false))

@section('title', $isEdit ? 'Edit User Admin' : 'Tambah User Admin')
@section('page-title', $isEdit ? 'Edit User Admin' : 'Tambah User Admin')
@section('breadcrumb', 'Access')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="admin-card p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                        {{ $isEdit ? 'Edit Akun Admin' : 'Tambah Akun Admin' }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ $isEdit ? 'Perbarui data akun dan role.' : 'Buat akun admin baru dan pilih rolenya.' }}
                    </p>
                </div>
                <a href="{{ route('admin.access.users.index') }}" class="px-4 py-2 rounded-xl bg-white/60 hover:bg-white text-slate-700 font-bold border border-slate-200">
                    Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="mt-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="mt-8 space-y-6" method="POST" action="{{ $isEdit ? route('admin.access.users.update', $user) : route('admin.access.users.store') }}">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border-slate-200 focus:border-primary-500 focus:ring-primary-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border-slate-200 focus:border-primary-500 focus:ring-primary-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Password {{ $isEdit ? '(kosongkan jika tidak diganti)' : '' }}
                        </label>
                        <input type="password" name="password" class="w-full rounded-2xl border-slate-200 focus:border-primary-500 focus:ring-primary-500" {{ $isEdit ? '' : 'required' }}>
                        <p class="text-xs text-slate-500 mt-2">Minimal 8 karakter.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Role</label>
                        @php($currentRole = old('role', $user->roles->first()->name ?? 'admin'))
                        <select name="role" class="w-full rounded-2xl border-slate-200 focus:border-primary-500 focus:ring-primary-500" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $currentRole === $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

