<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Traits\ImageCompression;

class GalleryController extends Controller
{
    use ImageCompression;

    public function __construct()
    {
        $this->middleware('permission:admin.gallery.view')->only(['index']);
        $this->middleware('permission:admin.gallery.create')->only(['store']);
        $this->middleware('permission:admin.gallery.edit')->only(['update']);
        $this->middleware('permission:admin.gallery.delete')->only(['destroy']);
    }

    private function clearCache()
    {
        Cache::forget('admin_gallery_list');
        $this->forgetPublicCaches('gallery');
    }

    public function index(Request $request)
    {
        Cache::forget('admin_gallery_list');

        $categories = GalleryCategory::query()
            ->orderBy('order')
            ->get(['id', 'name', 'slug', 'is_active']);

        // Jika sedang membuka album tertentu atau semua foto
        if ($request->has('category')) {
            $selectedCategory = trim((string) $request->query('category'));

            if ($selectedCategory === 'all' || $selectedCategory === '') {
                $photos = Gallery::query()
                    ->orderBy('order', 'asc')
                    ->orderBy('id', 'desc')
                    ->paginate(16)
                    ->withQueryString();
                $categoryObj = null;
            } else {
                $photos = Gallery::query()
                    ->where('category', $selectedCategory)
                    ->orderBy('order', 'asc')
                    ->orderBy('id', 'desc')
                    ->paginate(16)
                    ->withQueryString();
                $categoryObj = GalleryCategory::where('name', $selectedCategory)->first();
            }

            return view('admin.gallery', [
                'viewMode' => 'photos',
                'selectedCategory' => $selectedCategory,
                'categoryObj' => $categoryObj,
                'photos' => $photos,
                'categories' => $categories,
            ]);
        }

        // Mode Tampilan Album Kategori (Default)
        $albums = [];
        $categoryNames = $categories->pluck('name')->toArray();
        if (!in_array('Umum', $categoryNames, true)) {
            $categoryNames[] = 'Umum';
        }

        // Ambil semua kategori yang ada di tabel gallery juga (jika ada kategori legacy)
        $existingGalleryCats = Gallery::query()->distinct()->pluck('category')->filter()->toArray();
        $allUniqueCats = array_values(array_unique(array_merge($categoryNames, $existingGalleryCats)));

        foreach ($allUniqueCats as $catName) {
            $catObj = $categories->firstWhere('name', $catName);
            $count = Gallery::query()->where('category', $catName)->count('id');
            $previews = Gallery::query()
                ->where('category', $catName)
                ->orderBy('order', 'asc')
                ->orderBy('id', 'desc')
                ->take(4)
                ->get(['id', 'image', 'title']);

            $albums[] = [
                'name' => $catName,
                'slug' => $catObj?->slug ?? \Illuminate\Support\Str::slug($catName),
                'is_active' => $catObj?->is_active ?? true,
                'count' => $count,
                'previews' => $previews,
                'category_id' => $catObj?->id ?? null,
            ];
        }

        $totalCount = Gallery::query()->count('id');
        $allPreviews = Gallery::query()->orderBy('order', 'asc')->orderBy('id', 'desc')->take(4)->get(['id', 'image', 'title']);

        return view('admin.gallery', [
            'viewMode' => 'albums',
            'categories' => $categories,
            'albums' => $albums,
            'totalCount' => $totalCount,
            'allPreviews' => $allPreviews,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_files' => ['required', 'array', 'min:1'],
            'image_files.*' => [
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category = trim((string) ($data['category'] ?? 'Umum'));
        if ($category === '') {
            $category = 'Umum';
        }
        $order = $data['order'] ?? 0;
        $is_active = (bool) ($data['is_active'] ?? true);

        $files = $request->file('image_files') ?? [];
        $uploadedCount = 0;

        foreach ($files as $file) {
            $path = $this->compressAndStore($file, 'uploads/gallery');
            $title = !empty($data['title']) ? $data['title'] : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            Gallery::create([
                'title' => $title,
                'category' => $category,
                'description' => $data['description'] ?? null,
                'image' => '/storage/'.$path,
                'order' => $order,
                'is_active' => $is_active,
            ]);
            $uploadedCount++;
        }

        $this->clearCache();

        return redirect()->route('admin.gallery.index', ['category' => $category])
            ->with('success', $uploadedCount . ' foto berhasil diunggah ke album: "' . $category . '"');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_files' => ['nullable', 'array'],
            'image_files.*' => [
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category = trim((string) ($data['category'] ?? 'Umum'));
        if ($category === '') {
            $category = 'Umum';
        }
        $data['category'] = $category;
        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if ($request->hasFile('image_files')) {
            $this->deletePublicFileIfNeeded($gallery->image);
            $path = $this->compressAndStore($request->file('image_files')[0], 'uploads/gallery');
            $data['image'] = '/storage/'.$path;
        }

        unset($data['image_files']);

        $gallery->update($data);
        $this->clearCache();

        return back()->with('success', 'Media galeri "' . $gallery->title . '" berhasil diperbarui');
    }

    public function destroy(Gallery $gallery)
    {
        $title = $gallery->title;
        $this->deletePublicFileIfNeeded($gallery->image);
        Gallery::destroy($gallery->id);
        $this->clearCache();

        return back()->with('success', 'Media galeri "' . $title . '" berhasil dihapus');
    }

    private function deletePublicFileIfNeeded(?string $url): void
    {
        if (!$url) return;
        if (!str_starts_with($url, '/storage/')) return;

        $relative = ltrim(substr($url, strlen('/storage/')), '/');
        if ($relative !== '') {
            Storage::disk('public')->delete($relative);
        }
    }
}
