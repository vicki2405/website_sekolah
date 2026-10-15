@extends('admin.layout')

@section('title', 'Users Admin')
@section('page-title', 'Users Admin')
@section('breadcrumb', 'Access')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Akun Admin</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola akun admin dan rolenya.</p>
            </div>
            @can('admin.users.create')
                <a href="{{ route('admin.access.users.create') }}" class="btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Admin
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
                            <th class="text-left px-6 py-4 font-bold">Email</th>
                            <th class="text-left px-6 py-4 font-bold">Role</th>
                            <th class="text-right px-6 py-4 font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @php($role = $user->roles->first())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ $role?->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('admin.users.edit')
                                            <a href="{{ route('admin.access.users.edit', $user) }}" class="h-9 w-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-200" title="Edit User">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                        @endcan
                                        @can('admin.users.delete')
                                            <form method="POST" action="{{ route('admin.access.users.destroy', $user) }}" onsubmit="return confirm('Hapus akun ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-200" title="Hapus User">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                                    Belum ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection

