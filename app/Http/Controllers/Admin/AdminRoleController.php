<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.roles.view')->only(['index']);
        $this->middleware('permission:admin.roles.create')->only(['create', 'store']);
        $this->middleware('permission:admin.roles.edit')->only(['edit', 'update']);
        $this->middleware('permission:admin.roles.delete')->only(['destroy']);
    }

    public function index()
    {
        $roles = Role::query()
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return view('admin.access.roles.index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        return view('admin.access.roles.form', [
            'role' => new Role(),
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where('guard_name', 'web')],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')->where('guard_name', 'web')],
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.access.roles.index')->with('success', 'Role berhasil dibuat');
    }

    public function edit(Role $role)
    {
        return view('admin.access.roles.form', [
            'role' => $role->load('permissions'),
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        if (in_array($role->name, ['admin', 'superadmin'], true)) {
            return redirect()->back()->with('error', 'Role ini tidak dapat diubah');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where('guard_name', 'web')->ignore($role->id)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')->where('guard_name', 'web')],
        ]);

        $role->update([
            'name' => $data['name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.access.roles.index')->with('success', 'Role berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['admin', 'superadmin'], true)) {
            return redirect()->back()->with('error', 'Role ini tidak dapat dihapus');
        }

        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.access.roles.index')->with('success', 'Role berhasil dihapus');
    }

    private function permissionGroups(): array
    {
        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get()
            ->pluck('name')
            ->all();

        $groups = [];
        foreach ($permissions as $p) {
            $parts = explode('.', $p);
            $group = $parts[1] ?? 'misc';
            $action = $parts[2] ?? 'view';
            $groups[$group][$action][] = $p;
        }

        ksort($groups);

        return $groups;
    }
}

