<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemInformationMenu;
use Illuminate\Http\Request;

class SystemInformationMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.system_information.view')->only(['index']);
        $this->middleware('permission:admin.system_information.edit')->only(['store']);
        $this->middleware('permission:admin.system_information.delete')->only(['destroy']);
    }

    public function index()
    {
        $menus = SystemInformationMenu::query()->orderBy('id')->get();
        return view('admin.system_information.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:2048'],
        ]);

        SystemInformationMenu::create($data);
        $this->forgetPublicCaches('system_information');

        return redirect()->route('admin.system-information.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function destroy(SystemInformationMenu $menu)
    {
        $menu->delete();
        $this->forgetPublicCaches('system_information');

        return redirect()->route('admin.system-information.index')->with('success', 'Menu berhasil dihapus');
    }
}
