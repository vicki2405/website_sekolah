@extends('admin.layout')

@section('title', 'Roles & Permission')
@section('page-title', 'Roles & Permission')
@section('breadcrumb', 'Access')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Role</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola role dan permission untuk menu admin.</p>
            </div>
            @can('admin.roles.create')
                <a href="{{ route('admin.access.roles.create') }}" class="btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Role
                </a>
            @endcan
        </div>

        @if(session('success'))
            <div class="admin-card p-4 border border-emerald-200 bg-emerald-50 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left px-6 py-4 font-bold">Nama</th>
                            <th class="text-left px-6 py-4 font-bold">User</th>
                            <th class="text-right px-6 py-4 font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($roles as $role)
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    {{ $role->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $role->users_count }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('admin.roles.edit')
                                            <a href="{{ route('admin.access.roles.edit', $role) }}" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit Role">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                        @endcan
                                        @can('admin.roles.delete')
                                            <form method="POST" action="{{ route('admin.access.roles.destroy', $role) }}" onsubmit="return confirm('Hapus role ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus Role">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-slate-500">
                                    Belum ada data role.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

