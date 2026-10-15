<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Traits\ImageCompression;

class NewsController extends Controller
{
    use ImageCompression;

    private function clearCache(?string $slug = null): void
    {
        $context = $slug ? ['slugs' => [$slug]] : [];
        $this->forgetPublicCaches('news', $context);
        \Illuminate\Support\Facades\Cache::forget('public_news_categories');
    }

    public function __construct()
    {
        $this->middleware('permission:admin.news.view')->only(['index']);
        $this->middleware('permission:admin.news.create')->only(['create', 'store']);
        $this->middleware('permission:admin.news.edit')->only(['edit', 'update', 'editorImageStore']);
        $this->middleware('permission:admin.news.delete')->only(['destroy']);
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
        $news = News::query()->orderBy('created_at', 'desc')
            ->get(['id', 'title', 'image', 'is_published', 'created_at', 'category']);

        return view('admin.news', compact('news'));
    }

    public function create()
    {
        return view('admin.news-form', [
            'news' => new News(),
        ]);
    }

    public function edit(News $news)
    {
        return view('admin.news-form', [
            'news' => $news,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:news,slug'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'category' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['category'] = $data['category'] ?? 'Berita';
        $data['is_published'] = (bool) ($data['is_published'] ?? false);
        $data['user_id'] = Auth::id();
        $data['content'] = $this->sanitizeHtml((string) ($data['content'] ?? ''));

        if ($request->hasFile('image_file')) {
            $path = $this->compressAndStore($request->file('image_file'), 'uploads/news');
            $data['image'] = '/storage/'.$path;
        }

        unset($data['image_file']);

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        News::create($data);
        $this->clearCache($data['slug'] ?? null);

        return redirect()->route('admin.news.index');
    }

    public function update(Request $request, News $news)
    {
        $oldSlug = $news->slug;
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('news', 'slug')->ignore($news->id)],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'category' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['category'] = $data['category'] ?? 'Berita';
        $data['is_published'] = (bool) ($data['is_published'] ?? false);
        $data['user_id'] = $news->user_id ?? Auth::id();
        $data['content'] = $this->sanitizeHtml((string) ($data['content'] ?? ''));

        if ($request->hasFile('image_file')) {
            $this->deletePublicFileIfNeeded($news->image);
            $path = $this->compressAndStore($request->file('image_file'), 'uploads/news');
            $data['image'] = '/storage/'.$path;
        }

        unset($data['image_file']);

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (!$data['is_published']) {
            $data['published_at'] = null;
        }

        $news->update($data);
        $this->clearCache($news->slug);
        if ($oldSlug !== $news->slug) {
            $this->clearCache($oldSlug);
        }

        return redirect()->route('admin.news.index');
    }

    public function editorImageStore(Request $request)
    {
        $data = $request->validate([
            'image_file' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
        ]);

        $path = $this->compressAndStore($data['image_file'], 'uploads/news/content');

        return response()->json([
            'url' => '/storage/' . $path,
        ]);
    }

    public function destroy(News $news)
    {
        $slug = $news->slug;
        $this->deletePublicFileIfNeeded($news->image);
        News::destroy($news->id);
        $this->clearCache($slug);

        return redirect()->route('admin.news.index');
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
