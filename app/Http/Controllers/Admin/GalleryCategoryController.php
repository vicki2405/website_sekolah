<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.gallery_categories.view')->only(['index']);
        $this->middleware('permission:admin.gallery_categories.create')->only(['create', 'store']);
        $this->middleware('permission:admin.gallery_categories.edit')->only(['edit', 'update']);
        $this->middleware('permission:admin.gallery_categories.delete')->only(['destroy']);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base !== '' ? $base : 'kategori';

        $suffix = 1;
        while (
            GalleryCategory::query()
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
        $categories = GalleryCategory::query()->orderBy('order')->get();
        return view('admin.gallery_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.gallery_categories.create');
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

        GalleryCategory::create($data);
        $this->forgetPublicCaches('gallery');

        return redirect()->route('admin.gallery.categories.index')->with('success', 'Kategori galeri berhasil ditambahkan');
    }

    public function edit(GalleryCategory $category)
    {
        return view('admin.gallery_categories.edit', compact('category'));
    }

    public function update(Request $request, GalleryCategory $category)
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
            Gallery::query()
                ->where('category', $oldName)
                ->update(['category' => $category->name]);
        }

        $this->forgetPublicCaches('gallery');
        return redirect()->route('admin.gallery.categories.index')->with('success', 'Kategori galeri berhasil diperbarui');
    }

    public function destroy(GalleryCategory $category)
    {
        if ($category->slug === 'umum') {
            return redirect()->back()->with('success', 'Kategori "Umum" tidak dapat dihapus');
        }

        $fallback = GalleryCategory::query()->where('slug', 'umum')->first();
        $fallbackName = $fallback?->name ?? 'Umum';

        Gallery::query()
            ->where('category', $category->name)
            ->update(['category' => $fallbackName]);

        $category->delete();
        $this->forgetPublicCaches('gallery');

        return redirect()->back()->with('success', 'Kategori galeri berhasil dihapus (media dipindahkan ke "Umum")');
    }
}
