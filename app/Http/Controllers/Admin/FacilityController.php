<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Traits\ImageCompression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    use ImageCompression;

    private function clearCache(): void
    {
        $this->forgetPublicCaches('facilities');
    }

    public function __construct()
    {
        $this->middleware('permission:admin.facilities.view')->only(['index']);
        $this->middleware('permission:admin.facilities.create')->only(['create', 'store']);
        $this->middleware('permission:admin.facilities.edit')->only(['edit', 'update']);
        $this->middleware('permission:admin.facilities.delete')->only(['destroy']);
    }

    public function index()
    {
        $facilities = Facility::query()->orderBy('order')
            ->get(['id', 'title', 'image', 'icon', 'category', 'order', 'is_active', 'description']);
        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        $categories = FacilityCategory::query()->where('is_active', true)->orderBy('order')->get();
        return view('admin.facilities.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:2048', 'required_without:image_file'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
                'required_without:image',
            ],
            'gallery_files.*' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'new_gallery_captions' => ['nullable', 'array'],
            'new_gallery_captions.*' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['category'] = $data['category'] ?? 'Umum';
        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        if ($request->hasFile('image_file')) {
            $path = $this->compressAndStore($request->file('image_file'), 'uploads/facilities');
            $data['image'] = '/storage/' . $path;
        }

        unset($data['image_file']);

        $gallery = [];
        if ($request->hasFile('gallery_files')) {
            $captions = (array) $request->input('new_gallery_captions', []);
            foreach (array_values($request->file('gallery_files')) as $index => $file) {
                $path = $this->compressAndStore($file, 'uploads/facilities/gallery');
                $gallery[] = [
                    'url' => '/storage/' . $path,
                    'caption' => trim(strip_tags((string) ($captions[$index] ?? ''))),
                ];
            }
        }
        $data['gallery'] = $gallery;

        Facility::create($data);
        $this->clearCache();

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil ditambahkan');
    }

    public function edit(Facility $facility)
    {
        $categories = FacilityCategory::query()->where('is_active', true)->orderBy('order')->get();
        return view('admin.facilities.edit', compact('facility', 'categories'));
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'existing_gallery_images' => ['nullable', 'array'],
            'existing_gallery_images.*' => ['nullable', 'string', 'max:2048'],
            'existing_gallery_captions' => ['nullable', 'array'],
            'existing_gallery_captions.*' => ['nullable', 'string', 'max:255'],
            'gallery_files.*' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'new_gallery_captions' => ['nullable', 'array'],
            'new_gallery_captions.*' => ['nullable', 'string', 'max:255'],
            'remove_gallery' => ['nullable', 'array'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['category'] = $data['category'] ?? 'Umum';
        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if ($request->hasFile('image_file')) {
            $this->deletePublicFileIfNeeded($facility->image);
            $path = $this->compressAndStore($request->file('image_file'), 'uploads/facilities');
            $data['image'] = '/storage/' . $path;
        }

        unset($data['image_file']);

        // Build existing gallery items with updated captions
        $existingUrls = (array) $request->input('existing_gallery_images', []);
        $existingCaptions = (array) $request->input('existing_gallery_captions', []);
        $gallery = [];

        foreach (array_values($existingUrls) as $index => $url) {
            $normalizedUrl = trim((string) $url);
            if ($normalizedUrl === '') continue;
            $gallery[] = [
                'url' => $normalizedUrl,
                'caption' => trim(strip_tags((string) ($existingCaptions[$index] ?? ''))),
            ];
        }

        // Remove checked items
        if ($request->has('remove_gallery')) {
            $removeUrls = (array) $request->input('remove_gallery');
            foreach ($removeUrls as $removeUrl) {
                $this->deletePublicFileIfNeeded($removeUrl);
                $gallery = array_values(array_filter(
                    $gallery,
                    fn ($item) => $item['url'] !== $removeUrl
                ));
            }
        }

        // Add new uploads
        if ($request->hasFile('gallery_files')) {
            $newCaptions = (array) $request->input('new_gallery_captions', []);
            foreach (array_values($request->file('gallery_files')) as $index => $file) {
                $path = $this->compressAndStore($file, 'uploads/facilities/gallery');
                $gallery[] = [
                    'url' => '/storage/' . $path,
                    'caption' => trim(strip_tags((string) ($newCaptions[$index] ?? ''))),
                ];
            }
        }

        $data['gallery'] = $gallery;

        unset(
            $data['existing_gallery_images'],
            $data['existing_gallery_captions'],
            $data['new_gallery_captions']
        );

        $facility->update($data);
        $this->clearCache();

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui');
    }

    public function destroy(Facility $facility)
    {
        $this->deletePublicFileIfNeeded($facility->image);
        foreach (Facility::normalizeGallery($facility->gallery) as $item) {
            $this->deletePublicFileIfNeeded($item['url']);
        }
        Facility::destroy($facility->id);
        $this->clearCache();

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus');
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
