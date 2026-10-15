<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AgendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.agendas.view')->only(['index']);
        $this->middleware('permission:admin.agendas.create')->only(['store']);
        $this->middleware('permission:admin.agendas.edit')->only(['update']);
        $this->middleware('permission:admin.agendas.delete')->only(['destroy']);
    }

    private function clearCache()
    {
        Cache::forget('admin_agendas_list');
        $this->forgetPublicCaches('agenda');
    }

    public function index()
    {
        // Admin listing is queried directly to avoid stale/corrupt cached rows
        // causing Blade to receive non-model values.
        $agendas = Agenda::query()
            ->orderBy('start_date', 'desc')
            ->get(['id', 'title', 'description', 'start_date', 'end_date', 'category', 'color', 'icon', 'is_active']);

        return view('admin.agendas', compact('agendas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'category' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['color'] = $data['color'] ?? 'brand-500';
        $data['icon'] = $data['icon'] ?? '📅';
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        Agenda::create($data);
        $this->clearCache();

        return redirect()->route('admin.agendas.index');
    }

    public function update(Request $request, Agenda $agenda)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'category' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['color'] = $data['color'] ?? 'brand-500';
        $data['icon'] = $data['icon'] ?? '📅';
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $agenda->update($data);
        $this->clearCache();

        return redirect()->route('admin.agendas.index');
    }

    public function destroy(Agenda $agenda)
    {
        Agenda::destroy($agenda->id);
        $this->clearCache();

        return redirect()->route('admin.agendas.index');
    }
}
