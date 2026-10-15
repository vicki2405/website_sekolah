<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Traits\ImageCompression;

class SliderController extends Controller
{
    use ImageCompression;

    private function clearCache()
    {
        Cache::forget('admin_sliders_list');
    }

    public function index()
    {
        $sliders = Cache::remember('admin_sliders_list', now()->addMinutes(10), function () {
            return Slider::query()->orderBy('order', 'asc')->get();
        });

        return view('admin.sliders', compact('sliders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048', 'required_without:image_file'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
                'required_without:image',
            ],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        if ($request->hasFile('image_file')) {
            $path = $this->compressAndStore($request->file('image_file'), 'uploads/sliders', 70, 1920);
            $data['image'] = '/storage/'.$path;
        }

        unset($data['image_file']);

        Slider::create($data);
        $this->clearCache();

        return redirect()->route('admin.sliders.index');
    }

    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if ($request->hasFile('image_file')) {
            $this->deletePublicFileIfNeeded($slider->image);
            $path = $this->compressAndStore($request->file('image_file'), 'uploads/sliders', 70, 1920);
            $data['image'] = '/storage/'.$path;
        }

        unset($data['image_file']);

        $slider->update($data);
        $this->clearCache();

        return redirect()->route('admin.sliders.index');
    }

    public function destroy(Slider $slider)
    {
        $this->deletePublicFileIfNeeded($slider->image);
        Slider::destroy($slider->id);
        $this->clearCache();

        return redirect()->route('admin.sliders.index');
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
