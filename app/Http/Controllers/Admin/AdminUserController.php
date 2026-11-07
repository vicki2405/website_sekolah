<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.users.view')->only(['index']);
        $this->middleware('permission:admin.users.create')->only(['create', 'store']);
        $this->middleware('permission:admin.users.edit')->only(['edit', 'update']);
        $this->middleware('permission:admin.users.delete')->only(['destroy']);
    }

    public function index()
    {
        $users = User::query()
            ->with('roles')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.access.users.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $query = Role::query()->orderBy('name');
        if (!Auth::user()?->hasRole('superadmin')) {
            $query->where('name', '!=', 'superadmin');
        }
        $roles = $query->get();

        return view('admin.access.users.form', [
            'user' => new User(),
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', Rule::exists('roles', 'name')->where('guard_name', 'web')],
        ]);

        if ($data['role'] === 'superadmin' && !Auth::user()?->hasRole('superadmin')) {
            return redirect()->back()->with('error', 'Hanya superadmin yang dapat membuat akun dengan role superadmin');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'email_verified_at' => now(),
        ]);

        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.access.users.index')->with('success', 'Akun admin berhasil dibuat');
    }

    public function edit(User $user)
    {
        if ($user->hasRole('superadmin') && !Auth::user()?->hasRole('superadmin')) {
            return redirect()->route('admin.access.users.index')->with('error', 'Anda tidak memiliki hak untuk mengubah akun superadmin');
        }

        $query = Role::query()->orderBy('name');
        if (!Auth::user()?->hasRole('superadmin')) {
            $query->where('name', '!=', 'superadmin');
        }
        $roles = $query->get();

        return view('admin.access.users.form', [
            'user' => $user->load('roles'),
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        if ($user->hasRole('superadmin') && !Auth::user()?->hasRole('superadmin')) {
            return redirect()->route('admin.access.users.index')->with('error', 'Anda tidak memiliki hak untuk mengubah akun superadmin');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', Rule::exists('roles', 'name')->where('guard_name', 'web')],
        ]);

        if ($data['role'] === 'superadmin' && !Auth::user()?->hasRole('superadmin')) {
            return redirect()->back()->with('error', 'Hanya superadmin yang dapat memberikan role superadmin');
        }

        if ($user->hasRole('superadmin') && $data['role'] !== 'superadmin' && (int) $user->id === (int) Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat mencabut role superadmin dari akun Anda sendiri');
        }

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];
        if (!empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);

        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.access.users.index')->with('success', 'Akun admin berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $currentUserId = (int) Auth::id();
        if ((int) $user->id === $currentUserId) {
            return redirect()->back()->with('error', 'Akun sendiri tidak dapat dihapus');
        }

        if ($user->hasRole('superadmin')) {
            return redirect()->back()->with('error', 'Akun superadmin tidak dapat dihapus');
        }

        User::destroy($user->getKey());

        return redirect()->route('admin.access.users.index')->with('success', 'Akun admin berhasil dihapus');
    }
}
