@extends('admin.layout')

@php($isEdit = (bool) ($role->id ?? false))

@section('title', $isEdit ? 'Edit Role' : 'Tambah Role')
@section('page-title', $isEdit ? 'Edit Role' : 'Tambah Role')
@section('breadcrumb', 'Access')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="admin-card p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                        {{ $isEdit ? 'Edit Role' : 'Tambah Role' }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Atur permission view/create/edit/delete untuk setiap menu admin.</p>
                </div>
                <a href="{{ route('admin.access.roles.index') }}" class="px-4 py-2 rounded-xl bg-white/60 hover:bg-white text-slate-700 font-bold border border-slate-200">
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

            <form class="mt-8 space-y-8" method="POST" action="{{ $isEdit ? route('admin.access.roles.update', $role) : route('admin.access.roles.store') }}">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Role</label>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}" class="w-full rounded-2xl border-slate-200 focus:border-primary-500 focus:ring-primary-500" required>
                        <p class="text-xs text-slate-500 mt-2">Contoh: editor, operator, humas.</p>
                    </div>
                </div>

                @php($selected = collect(old('permissions', $role->permissions->pluck('name')->all() ?? []))->flip())

                <div class="space-y-6">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-wide uppercase">Permissions</h3>
                        <label class="flex items-center gap-3 px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/60 hover:bg-white cursor-pointer">
                            <input id="permissions_all" type="checkbox" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm font-extrabold text-slate-700">All Permission</span>
                        </label>
                    </div>

                    @foreach($permissionGroups as $module => $actions)
                        <div class="admin-card p-6 border border-slate-100">
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-sm font-extrabold text-slate-900">
                                    {{ str_replace('_', ' ', $module) }}
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                @foreach(['view','create','edit','delete'] as $action)
                                    @php($permName = "admin.{$module}.{$action}")
                                    <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-white/60 hover:bg-white cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $permName }}" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500"
                                            {{ $selected->has($permName) ? 'checked' : '' }}
                                            {{ !in_array($permName, ($actions[$action] ?? []), true) ? 'disabled' : '' }}
                                        >
                                        <span class="text-sm font-bold text-slate-700">{{ strtoupper($action) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const master = document.getElementById('permissions_all');
            if (!master) return;

            const getBoxes = () =>
                Array.from(document.querySelectorAll('input[type="checkbox"][name="permissions[]"]')).filter((el) => !el.disabled);

            const updateMasterState = () => {
                const boxes = getBoxes();
                if (boxes.length === 0) {
                    master.checked = false;
                    master.indeterminate = false;
                    master.disabled = true;
                    return;
                }

                const checkedCount = boxes.filter((el) => el.checked).length;
                master.disabled = false;
                master.checked = checkedCount === boxes.length;
                master.indeterminate = checkedCount > 0 && checkedCount < boxes.length;
            };

            master.addEventListener('change', () => {
                const boxes = getBoxes();
                boxes.forEach((el) => {
                    el.checked = master.checked;
                });
                updateMasterState();
            });

            document.addEventListener('change', (e) => {
                const target = e.target;
                if (!(target instanceof HTMLInputElement)) return;
                if (target.name !== 'permissions[]') return;
                updateMasterState();
            });

            updateMasterState();
        });
    </script>
@endsection
