<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FacilityCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.facility_categories.view')->only(['index']);
        $this->middleware('permission:admin.facility_categories.create')->only(['create', 'store']);
        $this->middleware('permission:admin.facility_categories.edit')->only(['edit', 'update']);
        $this->middleware('permission:admin.facility_categories.delete')->only(['destroy']);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base !== '' ? $base : 'kategori';

        $suffix = 1;
        while (
            FacilityCategory::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = ($base !== '' ? $base : 'kategori') . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    public function index()
    {
        $categories = FacilityCategory::query()->orderBy('order')->get();
        return view('admin.facility_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.facility_categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        FacilityCategory::create($data);
        $this->forgetPublicCaches('facilities');

        return redirect()->route('admin.facilities.categories.index')->with('success', 'Kategori fasilitas berhasil ditambahkan');
    }

    public function edit(FacilityCategory $category)
    {
        return view('admin.facility_categories.edit', compact('category'));
    }

    public function update(Request $request, FacilityCategory $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $oldName = $category->name;

        $data['slug'] = $this->uniqueSlug($data['name'], $category->id);
        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $category->update($data);

        if ($oldName !== $category->name) {
            Facility::query()
                ->where('category', $oldName)
                ->update(['category' => $category->name]);
        }

        $this->forgetPublicCaches('facilities');
        return redirect()->route('admin.facilities.categories.index')->with('success', 'Kategori fasilitas berhasil diperbarui');
    }

    public function destroy(FacilityCategory $category)
    {
        if ($category->slug === 'umum') {
            return redirect()->back()->with('success', 'Kategori "Umum" tidak dapat dihapus');
        }

        $fallback = FacilityCategory::query()->where('slug', 'umum')->first();
        $fallbackName = $fallback?->name ?? 'Umum';

        Facility::query()
            ->where('category', $category->name)
            ->update(['category' => $fallbackName]);

        $category->delete();
        $this->forgetPublicCaches('facilities');

        return redirect()->back()->with('success', 'Kategori fasilitas berhasil dihapus (data dipindahkan ke "Umum")');
    }
}
