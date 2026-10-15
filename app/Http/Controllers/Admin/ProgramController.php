<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Traits\ImageCompression;

class ProgramController extends Controller
{
    use ImageCompression;

    public function __construct()
    {
        $this->middleware('permission:admin.programs.view')->only(['index']);
        $this->middleware('permission:admin.programs.create')->only(['create', 'store']);
        $this->middleware('permission:admin.programs.edit')->only(['edit', 'update', 'editorImageStore']);
        $this->middleware('permission:admin.programs.delete')->only(['destroy']);
    }

    private function clearCache(array $slugs = [])
    {
        Cache::forget('admin_programs_list');
        $this->forgetPublicCaches('programs', ['slugs' => $slugs]);
    }

    private function getPrograms()
    {
        return Program::query()->orderBy('order', 'asc')
            ->get(['id', 'name', 'slug', 'icon', 'description', 'image', 'duration', 'order', 'is_active']);
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
        $programs = $this->getPrograms();

        return view('admin.programs', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs', [
            'programs' => collect(),
            'page' => ['action' => 'create'],
            'program' => null,
        ]);
    }

    public function edit(Program $program)
    {
        return view('admin.programs', [
            'programs' => collect(),
            'page' => ['action' => 'edit'],
            'program' => $program,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:programs,slug'],
            'description' => ['required', 'string'],
            'industry_class_content' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:20480',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'activity_images' => ['nullable', 'array'],
            'activity_images.*' => [
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:20480',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'new_activity_captions' => ['nullable', 'array'],
            'new_activity_captions.*' => ['nullable', 'string', 'max:255'],
            'competencies' => ['nullable', 'array'],
            'competencies.*.title' => ['nullable', 'string', 'max:255'],
            'competencies.*.desc' => ['nullable', 'string', 'max:500'],
            'competencies.*.icon' => ['nullable', 'string', 'max:100'],
            'careers' => ['nullable', 'array'],
            'careers.*.title' => ['nullable', 'string', 'max:255'],
            'careers.*.desc' => ['nullable', 'string', 'max:500'],
            'duration' => ['nullable', 'integer', 'min:1', 'max:6'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['description'] = $this->sanitizeHtml((string) ($data['description'] ?? ''));
        if (array_key_exists('industry_class_content', $data) && $data['industry_class_content'] !== null) {
            $data['industry_class_content'] = $this->sanitizeHtml((string) $data['industry_class_content']);
        }

        $data['competencies'] = $this->filterCompetencies($request->input('competencies'));
        $data['careers'] = $this->filterCareers($request->input('careers'));

        $data['duration'] = $data['duration'] ?? 3;
        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $baseSlug = $data['slug'] ?? \Illuminate\Support\Str::slug($data['name']);
        $data['slug'] = $this->uniqueSlug($baseSlug);

        if ($request->hasFile('image_file')) {
            $path = $this->compressAndStore($request->file('image_file'), 'uploads/programs', 70, 1200, 700 * 1024);
            $data['image'] = '/storage/'.$path;
        }

        $activityItems = null;
        if ($request->hasFile('activity_images')) {
            $activityItems = $this->buildUploadedActivityImageItems(
                $request->file('activity_images'),
                (array) $request->input('new_activity_captions', [])
            );
        }

        unset($data['image_file'], $data['activity_images'], $data['new_activity_captions']);

        $program = Program::create($data);

        if ($activityItems !== null) {
            $program->update(['activity_images' => $activityItems]);
        }

        $this->clearCache([$program->slug]);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil disimpan');
    }

    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('programs', 'slug')->ignore($program->id)],
            'description' => ['required', 'string'],
            'industry_class_content' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:20480',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'activity_images' => ['nullable', 'array'],
            'activity_images.*' => [
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:20480',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'existing_activity_images' => ['nullable', 'array'],
            'existing_activity_images.*' => ['nullable', 'string', 'max:2048'],
            'existing_activity_captions' => ['nullable', 'array'],
            'existing_activity_captions.*' => ['nullable', 'string', 'max:255'],
            'new_activity_captions' => ['nullable', 'array'],
            'new_activity_captions.*' => ['nullable', 'string', 'max:255'],
            'competencies' => ['nullable', 'array'],
            'competencies.*.title' => ['nullable', 'string', 'max:255'],
            'competencies.*.desc' => ['nullable', 'string', 'max:500'],
            'competencies.*.icon' => ['nullable', 'string', 'max:100'],
            'careers' => ['nullable', 'array'],
            'careers.*.title' => ['nullable', 'string', 'max:255'],
            'careers.*.desc' => ['nullable', 'string', 'max:500'],
            'duration' => ['nullable', 'integer', 'min:1', 'max:6'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['description'] = $this->sanitizeHtml((string) ($data['description'] ?? ''));
        if (array_key_exists('industry_class_content', $data) && $data['industry_class_content'] !== null) {
            $data['industry_class_content'] = $this->sanitizeHtml((string) $data['industry_class_content']);
        }

        $data['competencies'] = $this->filterCompetencies($request->input('competencies'));
        $data['careers'] = $this->filterCareers($request->input('careers'));

        $data['duration'] = $data['duration'] ?? 3;
        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $oldSlug = $program->slug;
        $baseSlug = $data['slug'] ?? \Illuminate\Support\Str::slug($data['name']);
        $data['slug'] = $this->uniqueSlug($baseSlug, $program->id);

        if ($request->hasFile('image_file')) {
            $this->deletePublicFileIfNeeded($program->image);
            $path = $this->compressAndStore($request->file('image_file'), 'uploads/programs', 70, 1200, 700 * 1024);
            $data['image'] = '/storage/'.$path;
        }

        $activityItems = $this->buildExistingActivityImageItems(
            (array) $request->input('existing_activity_images', $this->extractActivityImageUrls($program->activity_images)),
            (array) $request->input('existing_activity_captions', $this->extractActivityImageCaptions($program->activity_images))
        );

        if ($request->hasFile('activity_images')) {
            $activityItems = array_merge(
                $activityItems,
                $this->buildUploadedActivityImageItems(
                    $request->file('activity_images'),
                    (array) $request->input('new_activity_captions', [])
                )
            );
        }

        if ($request->filled('delete_activity_images')) {
            $deletedUrls = json_decode($request->input('delete_activity_images'), true) ?? [];
            if (is_array($deletedUrls) && count($deletedUrls) > 0) {
                $this->deletePublicFilesIfNeeded($deletedUrls);
                $activityItems = array_values(array_filter(
                    $activityItems,
                    fn ($item) => !in_array($item['url'] ?? null, $deletedUrls, true)
                ));
            }
        }

        unset(
            $data['image_file'],
            $data['activity_images'],
            $data['existing_activity_images'],
            $data['existing_activity_captions'],
            $data['new_activity_captions']
        );

        $program->update($data);
        $program->update(['activity_images' => $activityItems]);
        $this->clearCache([$oldSlug, $program->slug]);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui');
    }

    public function destroy(Program $program)
    {
        $this->deletePublicFileIfNeeded($program->image);
        $this->deletePublicFilesIfNeeded($program->activity_images);
        Program::destroy($program->id);
        $this->clearCache([$program->slug]);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dihapus');
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

        $path = $this->compressAndStore($data['image_file'], 'uploads/programs/content', 70, 1600, 700 * 1024);

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

    private function deletePublicFilesIfNeeded(array|string|null $urls): void
    {
        if (is_string($urls)) {
            $this->deletePublicFileIfNeeded($urls);
            return;
        }

        if (!is_array($urls)) return;

        foreach ($this->extractActivityImageUrls($urls) as $url) {
            $this->deletePublicFileIfNeeded($url);
        }
    }

    private function buildUploadedActivityImageItems(array $files, array $captions = []): array
    {
        $urls = $this->compressAndStoreMany($files, 'uploads/programs/activities', 75, 1600, 600 * 1024);

        return $this->combineActivityImageData($urls, $captions);
    }

    private function buildExistingActivityImageItems(array $urls, array $captions = []): array
    {
        return $this->combineActivityImageData($urls, $captions);
    }

    private function combineActivityImageData(array $urls, array $captions = []): array
    {
        $items = [];

        foreach (array_values($urls) as $index => $url) {
            $normalizedUrl = trim((string) $url);

            if ($normalizedUrl === '') {
                continue;
            }

            $items[] = [
                'url' => $normalizedUrl,
                'caption' => $this->normalizeActivityImageCaption($captions[$index] ?? ''),
            ];
        }

        return $items;
    }

    private function normalizeActivityImageCaption(mixed $caption): string
    {
        return trim(strip_tags((string) $caption));
    }

    private function extractActivityImageUrls(array|string|null $items): array
    {
        return array_map(
            fn (array $item) => $item['url'],
            Program::normalizeActivityImages($items)
        );
    }

    private function extractActivityImageCaptions(array|string|null $items): array
    {
        return array_map(
            fn (array $item) => $item['caption'] ?? '',
            Program::normalizeActivityImages($items)
        );
    }

    private function compressAndStoreMany(array $files, string $directory, int $quality, ?int $maxWidth, ?int $targetMaxBytes): array
    {
        $out = [];
        foreach ($files as $file) {
            if (!$file) continue;
            $path = $this->compressAndStore($file, $directory, $quality, $maxWidth, $targetMaxBytes);
            $out[] = '/storage/' . ltrim($path, '/');
        }
        return $out;
    }

    private function uniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $normalizedBase = $baseSlug !== '' ? $baseSlug : 'program';
        $slug = $normalizedBase;
        $i = 1;

        while (true) {
            $query = Program::query()->where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }

            if (!$query->exists()) {
                return $slug;
            }

            $i++;
            $slug = $normalizedBase . '-' . $i;
        }
    }

    private function filterCompetencies(?array $items): ?array
    {
        if (!$items) return null;
        $filtered = [];
        foreach ($items as $item) {
            $title = trim((string) ($item['title'] ?? ''));
            $desc = trim((string) ($item['desc'] ?? ''));
            $icon = trim((string) ($item['icon'] ?? 'Wrench'));
            if ($title !== '') {
                $filtered[] = [
                    'title' => $title,
                    'desc' => $desc,
                    'icon' => $icon !== '' ? $icon : 'Wrench',
                ];
            }
        }
        return !empty($filtered) ? $filtered : null;
    }

    private function filterCareers(?array $items): ?array
    {
        if (!$items) return null;
        $filtered = [];
        foreach ($items as $item) {
            $title = trim((string) ($item['title'] ?? ''));
            $desc = trim((string) ($item['desc'] ?? ''));
            if ($title !== '') {
                $filtered[] = [
                    'title' => $title,
                    'desc' => $desc,
                ];
            }
        }
        return !empty($filtered) ? $filtered : null;
    }
}
