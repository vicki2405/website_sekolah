<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tefa;
use App\Traits\ImageCompression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TefaController extends Controller
{
    use ImageCompression;

    public function __construct()
    {
        $this->middleware('permission:admin.tefa.view')->only(['index']);
        $this->middleware('permission:admin.tefa.create')->only(['create', 'store']);
        $this->middleware('permission:admin.tefa.edit')->only(['edit', 'update']);
        $this->middleware('permission:admin.tefa.delete')->only(['destroy']);
        $this->middleware('permission:admin.tefa.create|admin.tefa.edit')->only(['editorImageStore']);
    }

    private function clearCache(array $slugs = []): void
    {
        $this->forgetPublicCaches('tefa', ['slugs' => $slugs]);
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
        $items = Tefa::query()->orderBy('order')->orderBy('id')->get();

        return view('admin.tefa.index', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('admin.tefa.form', [
            'tefa' => new Tefa(),
        ]);
    }

    public function edit(Tefa $tefa)
    {
        return view('admin.tefa.form', [
            'tefa' => $tefa,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:tefas,slug'],
            'content' => ['nullable', 'string'],
            'background_image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
            ],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'gallery_files.*' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
            ],
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        if (array_key_exists('content', $data) && $data['content'] !== null) {
            $data['content'] = $this->sanitizeHtml((string) $data['content']);
        }

        if ($request->hasFile('background_image_file')) {
            $path = $this->compressAndStore($request->file('background_image_file'), 'uploads/tefa/backgrounds');
            $data['background_image'] = '/storage/' . $path;
        }

        $gallery = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $path = $this->compressAndStore($file, 'uploads/tefa/gallery');
                $gallery[] = '/storage/' . $path;
            }
        }
        $data['gallery'] = $gallery;

        $tefa = Tefa::create($data);
        $this->clearCache([$tefa->slug]);

        return redirect()->route('admin.tefa.index')->with('success', 'Halaman TEFA berhasil ditambahkan.');
    }

    public function update(Request $request, Tefa $tefa)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('tefas', 'slug')->ignore($tefa->id)],
            'content' => ['nullable', 'string'],
            'background_image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
            ],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'gallery_files.*' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
            ],
            'remove_gallery' => ['nullable', 'array'],
        ]);

        $oldSlug = $tefa->slug;
        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if (array_key_exists('content', $data) && $data['content'] !== null) {
            $data['content'] = $this->sanitizeHtml((string) $data['content']);
        }

        if ($request->hasFile('background_image_file')) {
            $this->deletePublicFileIfNeeded($tefa->background_image);
            $path = $this->compressAndStore($request->file('background_image_file'), 'uploads/tefa/backgrounds');
            $data['background_image'] = '/storage/' . $path;
        }

        $gallery = $tefa->gallery ?? [];

        if ($request->has('remove_gallery')) {
            foreach ($request->remove_gallery as $removePath) {
                if (($key = array_search($removePath, $gallery, true)) !== false) {
                    unset($gallery[$key]);
                    $this->deletePublicFileIfNeeded($removePath);
                }
            }
            $gallery = array_values($gallery);
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $path = $this->compressAndStore($file, 'uploads/tefa/gallery');
                $gallery[] = '/storage/' . $path;
            }
        }

        $data['gallery'] = $gallery;

        $tefa->update($data);
        $this->clearCache([$oldSlug, $tefa->slug]);

        return redirect()->route('admin.tefa.index')->with('success', 'Halaman TEFA berhasil diperbarui.');
    }

    public function destroy(Tefa $tefa)
    {
        $this->deletePublicFileIfNeeded($tefa->background_image);

        foreach (($tefa->gallery ?? []) as $img) {
            $this->deletePublicFileIfNeeded($img);
        }

        $this->clearCache([$tefa->slug]);
        $tefa->delete();

        return redirect()->route('admin.tefa.index')->with('success', 'Halaman TEFA berhasil dihapus.');
    }

    public function editorImageStore(Request $request)
    {
        $data = $request->validate([
            'image_file' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
            ],
        ]);

        $path = $this->compressAndStore($data['image_file'], 'uploads/tefa/content');

        return response()->json([
            'url' => '/storage/' . $path,
        ]);
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
