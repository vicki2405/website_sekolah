<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    use \App\Traits\ImageCompression;

    public function __construct()
    {
        $this->middleware('permission:admin.prestasi.view')->only(['index']);
        $this->middleware('permission:admin.prestasi.create')->only(['create', 'store']);
        $this->middleware('permission:admin.prestasi.edit')->only(['edit', 'update', 'updatePageSettings']);
        $this->middleware('permission:admin.prestasi.delete')->only(['destroy']);
    }

    private const PAGE_SETTING_KEYS = [
        'prestasi.hero_badge',
        'prestasi.hero_title',
        'prestasi.hero_description',
        'prestasi.stats',
    ];

    private function clearCache()
    {
        $this->forgetPublicCaches('prestasi');
        Cache::forget('prestasi_page_settings');
        Cache::forget('public_prestasi_achievements_v1');
    }

    private function getAchievements()
    {
        return Achievement::query()
            ->orderBy('order', 'asc')
            ->get(['id', 'title', 'value', 'description', 'icon', 'image', 'order', 'is_active']);
    }

    public function index()
    {
        $achievements = $this->getAchievements();

        $pageSettings = Cache::remember('prestasi_page_settings', now()->addHours(6), function () {
            return Setting::query()
                ->whereIn('key', self::PAGE_SETTING_KEYS)
                ->pluck('value', 'key')
                ->toArray();
        });

        return view('admin.achievements', [
            'achievements' => $achievements,
            'pageSettings' => $pageSettings,
        ]);
    }

    public function create()
    {
        return view('admin.achievements', [
            'page' => ['type' => 'achievement', 'action' => 'create'],
            'achievement' => new Achievement(),
            'achievements' => $this->getAchievements(),
            'pageSettings' => Cache::remember('prestasi_page_settings', now()->addHours(6), function () {
                return Setting::query()
                    ->whereIn('key', self::PAGE_SETTING_KEYS)
                    ->pluck('value', 'key')
                    ->toArray();
            }),
        ]);
    }

    public function edit(Achievement $achievement)
    {
        return view('admin.achievements', [
            'page' => ['type' => 'achievement', 'action' => 'edit'],
            'achievement' => $achievement,
            'achievements' => $this->getAchievements(),
            'pageSettings' => Cache::remember('prestasi_page_settings', now()->addHours(6), function () {
                return Setting::query()
                    ->whereIn('key', self::PAGE_SETTING_KEYS)
                    ->pluck('value', 'key')
                    ->toArray();
            }),
        ]);
    }

    public function updatePageSettings(Request $request)
    {
        $data = $request->validate([
            'hero_badge' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
            'stats' => ['nullable', 'array'],
            'stats.*.value' => ['nullable', 'string', 'max:100'],
            'stats.*.label' => ['nullable', 'string', 'max:100'],
            'stats.*.sublabel' => ['nullable', 'string', 'max:150'],
            'stats.*.icon' => ['nullable', 'string', 'max:50'],
            'stats.*.color' => ['nullable', 'string', 'max:50'],
        ]);

        $filteredStats = [];
        if (!empty($data['stats']) && is_array($data['stats'])) {
            foreach ($data['stats'] as $item) {
                if (!empty($item['value']) || !empty($item['label'])) {
                    $filteredStats[] = [
                        'value' => trim($item['value'] ?? ''),
                        'label' => trim($item['label'] ?? ''),
                        'sublabel' => trim($item['sublabel'] ?? ''),
                        'icon' => trim($item['icon'] ?? 'trophy'),
                        'color' => trim($item['color'] ?? 'amber'),
                    ];
                }
            }
        }

        $map = [
            'prestasi.hero_badge' => $data['hero_badge'] ?? null,
            'prestasi.hero_title' => $data['hero_title'] ?? null,
            'prestasi.hero_description' => $data['hero_description'] ?? null,
            'prestasi.stats' => !empty($filteredStats) ? json_encode($filteredStats) : null,
        ];

        foreach ($map as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->clearCache();

        return redirect()->route('admin.prestasi.index')->with('success', 'Pengaturan halaman Prestasi berhasil diperbarui');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        if ($request->hasFile('image_file')) {
            $path = $this->compressAndStore($request->file('image_file'), 'achievements', 70, 900);
            $data['image'] = Storage::url($path);
        }

        unset($data['image_file']);

        Achievement::create($data);
        $this->clearCache();

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil ditambahkan');
    }

    public function update(Request $request, Achievement $achievement)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if ($request->hasFile('image_file')) {
            $this->deletePublicFileIfNeeded($achievement->image);
            $path = $this->compressAndStore($request->file('image_file'), 'achievements', 70, 900);
            $data['image'] = Storage::url($path);
        }

        unset($data['image_file']);

        $achievement->update($data);
        $this->clearCache();

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil diperbarui');
    }

    public function destroy(Achievement $achievement)
    {
        $this->deletePublicFileIfNeeded($achievement->image);
        Achievement::destroy($achievement->id);
        $this->clearCache();

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil dihapus');
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
