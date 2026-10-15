<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Traits\ImageCompression;

class WakaController extends Controller
{
    use ImageCompression;

    public function __construct()
    {
        $this->middleware('permission:admin.waka.view')->only(['index']);
        $this->middleware('permission:admin.waka.create')->only(['create', 'store']);
        $this->middleware('permission:admin.waka.edit')->only(['edit', 'update']);
        $this->middleware('permission:admin.waka.delete')->only(['destroy']);
    }

    private function clearCache(?string $slug = null)
    {
        Cache::forget('admin_wakas_list');
        $this->forgetPublicCaches('waka', ['slugs' => $slug !== null ? [$slug] : []]);
    }

    private function sanitizeHtml(string $html): string
    {
        $clean = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $html) ?? '';
        $clean = preg_replace('/\son\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean) ?? '';
        $clean = preg_replace('/\s(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '', $clean) ?? '';
        return trim($clean);
    }

    public function index()
    {
        $wakas = Waka::orderBy('order', 'asc')->get();
        return view('admin.waka.index', compact('wakas'));
    }

    public function create()
    {
        return view('admin.waka.form', [
            'waka' => new Waka(),
        ]);
    }

    public function edit(Waka $waka)
    {
        return view('admin.waka.form', [
            'waka' => $waka,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:wakas,slug'],
            'description' => ['nullable', 'string'],
            'photo_name' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
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
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        if (array_key_exists('description', $data) && $data['description'] !== null) {
            $data['description'] = $this->sanitizeHtml((string) $data['description']);
        }
        if (array_key_exists('content', $data) && $data['content'] !== null) {
            $data['content'] = $this->sanitizeHtml((string) $data['content']);
        }

        if ($request->hasFile('image_file')) {
            $path = $this->compressAndStore($request->file('image_file'), 'uploads/waka');
            $data['image'] = '/storage/'.$path;
        }

        $gallery = [];
        if ($request->hasFile('gallery_files')) {
            $captions = (array) $request->input('new_gallery_captions', []);
            foreach (array_values($request->file('gallery_files')) as $index => $file) {
                $path = $this->compressAndStore($file, 'uploads/waka/gallery');
                $gallery[] = [
                    'url' => '/storage/'.$path,
                    'caption' => trim(strip_tags((string) ($captions[$index] ?? ''))),
                ];
            }
        }
        $data['gallery'] = $gallery;

        Waka::create($data);
        $this->clearCache();

        return redirect()->route('admin.waka.index')->with('success', 'Menu WAKA berhasil ditambahkan.');
    }

    public function update(Request $request, Waka $waka)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('wakas', 'slug')->ignore($waka->id)],
            'description' => ['nullable', 'string'],
            'photo_name' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'gallery_files.*' => [
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
            'new_gallery_captions' => ['nullable', 'array'],
            'new_gallery_captions.*' => ['nullable', 'string', 'max:255'],
            'remove_gallery' => ['nullable', 'array'],
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        if (array_key_exists('description', $data) && $data['description'] !== null) {
            $data['description'] = $this->sanitizeHtml((string) $data['description']);
        }
        if (array_key_exists('content', $data) && $data['content'] !== null) {
            $data['content'] = $this->sanitizeHtml((string) $data['content']);
        }

        if ($request->hasFile('image_file')) {
            $this->deletePublicFileIfNeeded($waka->image);
            $path = $this->compressAndStore($request->file('image_file'), 'uploads/waka');
            $data['image'] = '/storage/'.$path;
        }

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
                $path = $this->compressAndStore($file, 'uploads/waka/gallery');
                $gallery[] = [
                    'url' => '/storage/'.$path,
                    'caption' => trim(strip_tags((string) ($newCaptions[$index] ?? ''))),
                ];
            }
        }

        $data['gallery'] = $gallery;

        $oldSlug = $waka->slug;

        unset(
            $data['existing_gallery_images'],
            $data['existing_gallery_captions'],
            $data['new_gallery_captions']
        );

        $waka->update($data);
        $this->clearCache($oldSlug);
        if ($waka->slug !== $oldSlug) {
            $this->clearCache($waka->slug);
        }

        return redirect()->route('admin.waka.index')->with('success', 'Menu WAKA berhasil diperbarui.');
    }

    public function destroy(Waka $waka)
    {
        $this->deletePublicFileIfNeeded($waka->image);
        foreach (Waka::normalizeGallery($waka->gallery) as $item) {
            $this->deletePublicFileIfNeeded($item['url']);
        }
        $this->clearCache($waka->slug);
        $waka->delete();
        return redirect()->route('admin.waka.index')->with('success', 'Menu WAKA berhasil dihapus.');
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
