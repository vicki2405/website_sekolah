<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Traits\ImageCompression;

class ProfileController extends Controller
{
    use ImageCompression;

    public function __construct()
    {
        $this->middleware('permission:admin.profile.view')->only(['index']);
        $this->middleware('permission:admin.profile.edit')->only(['update']);
    }

    public function index()
    {
        $sections = ProfileSection::all()->keyBy('section_key');
        return view('admin.profile', compact('sections'));
    }

    private function sanitizeHtml(string $html): string
    {
        $clean = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $html) ?? '';
        $clean = preg_replace('/\son\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean) ?? '';
        $clean = preg_replace('/\s(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '', $clean) ?? '';
        return trim($clean);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'section_key' => 'required|string',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'content' => 'nullable|string',
            'principal_name' => 'nullable|string|max:255',
            'items' => 'nullable|array',
            'items.*.year' => 'nullable|string|max:50',
            'items.*.title' => 'nullable|string|max:255',
            'items.*.desc' => 'nullable|string|max:500',
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
        ]);

        if (array_key_exists('content', $data) && $data['content'] !== null) {
            $data['content'] = $this->sanitizeHtml((string) $data['content']);
        }

        if ($request->has('items')) {
            $filteredItems = [];
            foreach ((array) $request->input('items', []) as $item) {
                $year = trim((string) ($item['year'] ?? ''));
                $title = trim((string) ($item['title'] ?? ''));
                $desc = trim((string) ($item['desc'] ?? ''));
                if ($year !== '' || $title !== '') {
                    $filteredItems[] = [
                        'year' => $year,
                        'title' => $title,
                        'desc' => $desc,
                    ];
                }
            }
            $data['items'] = !empty($filteredItems) ? $filteredItems : null;
        }

        $section = ProfileSection::where('section_key', $request->section_key)->first();

        if ($request->hasFile('image_file')) {
            if ($section && $section->image) {
                $this->deleteFile($section->image);
            }
            $path = $this->compressAndStore($request->file('image_file'), 'profile', 70, 800);
            $data['image'] = Storage::url($path);
        }

        ProfileSection::updateOrCreate(
            ['section_key' => $request->section_key],
            $data
        );

        $this->forgetPublicCaches('profile');
        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    private function deleteFile(?string $url): void
    {
        if (!$url) return;
        $path = str_replace('/storage/', '', $url);
        Storage::disk('public')->delete($path);
    }
}
