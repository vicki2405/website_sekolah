<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(Request $request): Response
    {
        $category = $request->query('category');
        $search = $request->query('q');

        $query = News::published()
            ->select('id', 'title', 'slug', 'excerpt', 'image', 'category', 'published_at')
            ->orderByDesc('published_at');

        if ($category && $category !== 'Semua') {
            $query->where('category', $category);
        }

        if ($search && trim($search) !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $news = $query->paginate(9)->withQueryString();

        $categories = Cache::remember('public_news_categories', now()->addHours(6), function () {
            return News::published()->whereNotNull('category')->distinct()->pluck('category')->toArray();
        });

        $featured = null;
        if ((!$request->has('page') || $request->query('page') == 1) && (!$category || $category === 'Semua') && (!$search)) {
            $featured = News::published()
                ->select('id', 'title', 'slug', 'excerpt', 'image', 'category', 'published_at')
                ->orderByDesc('published_at')
                ->first();
        }

        return Inertia::render('News/Index', [
            'news' => $news,
            'categories' => $categories,
            'currentCategory' => $category ?: 'Semua',
            'searchQuery' => $search ?: '',
            'featured' => $featured,
        ]);
    }

    public function show(string $slug): Response
    {
        $article = Cache::remember("public_news_show_v3:{$slug}", now()->addMinutes(10), function () use ($slug) {
            $item = News::where('slug', $slug)->first();
            if (!$item) {
                abort(404);
            }
            return $item->toArray();
        });

        $related = Cache::remember("public_news_related_v3:{$slug}", now()->addMinutes(10), function () use ($article) {
            return News::published()
                ->where('id', '!=', $article['id'] ?? 0)
                ->where('category', $article['category'] ?? 'Berita')
                ->orderByDesc('published_at')
                ->take(3)
                ->get(['id', 'title', 'slug', 'image', 'published_at', 'category'])
                ->toArray();
        });

        return Inertia::render('News/Show', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}
