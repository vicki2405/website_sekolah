<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Traits\ImageCompression;

class HomeController extends Controller
{
    use ImageCompression;

    public function __construct()
    {
        $this->middleware('permission:admin.home.view')->only(['index']);
        $this->middleware('permission:admin.home.create')->only([
            'sliderCreate',
            'sliderStore',
            'partnerCreate',
            'partnerStore',
            'testimonialCreate',
            'testimonialStore',
        ]);
        $this->middleware('permission:admin.home.edit')->only([
            'sliderEdit',
            'sliderUpdate',
            'partnerEdit',
            'partnerUpdate',
            'testimonialEdit',
            'testimonialUpdate',
            'sectionUpdate',
        ]);
        $this->middleware('permission:admin.home.delete')->only([
            'sliderDestroy',
            'partnerDestroy',
            'testimonialDestroy',
        ]);
    }

    private function loadHomeData(): array
    {
        return [
            'sliders'     => Slider::orderBy('order', 'asc')->get(['id', 'title', 'subtitle', 'image', 'order', 'is_active']),
            'partners'    => Partner::orderBy('order', 'asc')->get(['id', 'name', 'logo', 'category', 'link', 'order']),
            'testimonials' => Testimonial::orderBy('order', 'asc')->get(['id', 'name', 'message', 'photo', 'position', 'company', 'graduation_year']),
            'sections'    => HomeSection::all()->keyBy('section_key'),
        ];
    }

    public function index()
    {
        return view('admin.home', $this->loadHomeData());
    }

    private function clearCache()
    {
        Cache::forget('admin_home_data');
        $this->forgetPublicCaches('home');
    }

    public function sliderCreate()
    {
        return view('admin.home', [
            'page'    => ['type' => 'slider', 'action' => 'create'],
            'slider'  => null,
        ]);
    }

    public function sliderEdit(Slider $slider)
    {
        return view('admin.home', [
            'page'    => ['type' => 'slider', 'action' => 'edit'],
            'slider'  => $slider,
        ]);
    }

    public function sliderStore(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image_file' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $this->compressAndStore($request->file('image_file'), 'sliders', 70, 1920);
            $data['image'] = Storage::url($path);
        }

        $data['is_active'] = (bool)($data['is_active'] ?? true);
        Slider::create($data);
        $this->clearCache();

        return redirect()->route('admin.home.index', ['tab' => 'sliders'])->with('success', 'Slider berhasil ditambahkan');
    }

    public function sliderUpdate(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $this->deleteFile($slider->image);
            $path = $this->compressAndStore($request->file('image_file'), 'sliders', 70, 1920);
            $data['image'] = Storage::url($path);
        }

        $data['is_active'] = (bool)($data['is_active'] ?? false);
        $slider->update($data);
        $this->clearCache();

        return redirect()->route('admin.home.index', ['tab' => 'sliders'])->with('success', 'Slider berhasil diperbarui');
    }

    public function sliderDestroy(Slider $slider)
    {
        $this->deleteFile($slider->image);
        Slider::destroy($slider->getKey());
        $this->clearCache();
        return redirect()->route('admin.home.index', ['tab' => 'sliders'])->with('success', 'Slider dihapus');
    }

    // --- PARTNERS ---
    public function partnerCreate()
    {
        return view('admin.home', [
            'page'    => ['type' => 'partner', 'action' => 'create'],
            'partner' => null,
        ]);
    }

    public function partnerEdit(Partner $partner)
    {
        return view('admin.home', [
            'page'    => ['type' => 'partner', 'action' => 'edit'],
            'partner' => $partner,
        ]);
    }

    public function partnerStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'logo_file' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:2048',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'link' => 'nullable|url',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('logo_file')) {
            $path = $this->compressAndStore($request->file('logo_file'), 'partners', 80, 400);
            $data['logo'] = Storage::url($path);
        }

        Partner::create($data);
        $this->clearCache();
        return redirect()->route('admin.home.index', ['tab' => 'partners'])->with('success', 'Mitra berhasil ditambahkan');
    }

    public function partnerUpdate(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'logo_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:2048',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'link' => 'nullable|url',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('logo_file')) {
            $this->deleteFile($partner->logo);
            $path = $this->compressAndStore($request->file('logo_file'), 'partners', 80, 400);
            $data['logo'] = Storage::url($path);
        }

        $partner->update($data);
        $this->clearCache();
        return redirect()->route('admin.home.index', ['tab' => 'partners'])->with('success', 'Mitra diperbarui');
    }

    public function partnerDestroy(Partner $partner)
    {
        $this->deleteFile($partner->logo);
        Partner::destroy($partner->getKey());
        $this->clearCache();
        return redirect()->route('admin.home.index', ['tab' => 'partners'])->with('success', 'Mitra dihapus');
    }

    public function testimonialCreate()
    {
        return view('admin.home', [
            'page'        => ['type' => 'testimonial', 'action' => 'create'],
            'testimonial' => null,
        ]);
    }

    public function testimonialEdit(Testimonial $testimonial)
    {
        return view('admin.home', [
            'page'        => ['type' => 'testimonial', 'action' => 'edit'],
            'testimonial' => $testimonial,
        ]);
    }

    public function testimonialStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'graduation_year' => 'nullable|string',
            'company' => 'nullable|string',
            'position' => 'nullable|string',
            'message' => 'required|string',
            'photo_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:2048',
                'dimensions:max_width=6000,max_height=6000',
            ],
        ]);

        if ($request->hasFile('photo_file')) {
            $path = $this->compressAndStore($request->file('photo_file'), 'testimonials', 60, 400);
            $data['photo'] = Storage::url($path);
        }

        Testimonial::create($data);
        $this->clearCache();
        return redirect()->route('admin.home.index', ['tab' => 'testimonials'])->with('success', 'Testimoni ditambahkan');
    }

    public function testimonialUpdate(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string',
            'photo_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:2048',
                'dimensions:max_width=6000,max_height=6000',
            ],
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('photo_file')) {
            $this->deleteFile($testimonial->photo);
            $path = $this->compressAndStore($request->file('photo_file'), 'testimonials', 60, 400);
            $data['photo'] = Storage::url($path);
        }

        $testimonial->update($data);
        $this->clearCache();
        return redirect()->route('admin.home.index', ['tab' => 'testimonials'])->with('success', 'Testimoni diperbarui');
    }

    public function testimonialDestroy(Testimonial $testimonial)
    {
        $this->deleteFile($testimonial->photo);
        Testimonial::destroy($testimonial->getKey());
        $this->clearCache();
        return redirect()->route('admin.home.index', ['tab' => 'testimonials'])->with('success', 'Testimoni dihapus');
    }

    // --- SECTIONS ---
    public function sectionUpdate(Request $request)
    {
        $request->validate([
            'section_key' => 'required|string',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
        ]);

        HomeSection::updateOrCreate(
            ['section_key' => $request->section_key],
            ['title' => $request->title, 'subtitle' => $request->subtitle]
        );

        $this->clearCache();
        return redirect()->back()->with('success', 'Bagian halaman diperbarui');
    }

    private function deleteFile(?string $url): void
    {
        if (!$url) return;
        $path = str_replace('/storage/', '', $url);
        Storage::disk('public')->delete($path);
    }
}
