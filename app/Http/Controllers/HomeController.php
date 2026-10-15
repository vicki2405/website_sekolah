<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Program;
use App\Models\Slider;
use App\Models\Teacher;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\HomeSection;
use App\Models\ProfileSection;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(): Response
    {
        $payload = Cache::remember('public_home_payload_v1', now()->addMinutes(10), function () {
            return [
                'sliders'      => Slider::active()->get(['id', 'title', 'subtitle', 'image', 'button_text', 'button_link', 'order'])->toArray(),
                'achievements' => Achievement::active()->get(['id', 'title', 'image', 'order'])->toArray(),
                'programs'     => Program::active()->get(['id', 'name', 'slug', 'icon', 'description', 'image'])->toArray(),
                'news'         => News::published()->orderByDesc('published_at')->take(3)->get(['id', 'title', 'slug', 'excerpt', 'image', 'published_at'])->toArray(),
                'agendas'      => Agenda::upcoming()->get(['id', 'title', 'start_date', 'end_date'])->toArray(),
                'teachers'     => Teacher::active()->take(6)->get(['id', 'name', 'position', 'photo'])->toArray(),
                'gallery'      => Gallery::active()->take(8)->get(['id', 'image', 'description'])->toArray(),
                'partners'     => Partner::query()->orderBy('order')->get(['id', 'name', 'logo'])->toArray(),
                'testimonials' => Testimonial::active()->get(['id', 'name', 'message', 'photo', 'position', 'company'])->toArray(),
                'sections'     => HomeSection::query()
                    ->get()
                    ->keyBy('section_key')
                    ->map(fn (HomeSection $section) => $section->toArray())
                    ->toArray(),
            ];
        });

        return Inertia::render('Home', $payload);
    }

    public function about(): Response
    {
        $payload = Cache::remember('public_about_payload_v1', now()->addMinutes(10), function () {
            return [
                'achievements'    => Achievement::active()->get(['id', 'title', 'description', 'image', 'order'])->toArray(),
                'teachers'        => Teacher::active()->get(['id', 'name', 'position', 'photo'])->toArray(),
                'profileSections' => ProfileSection::query()
                    ->get()
                    ->keyBy('section_key')
                    ->map(fn (ProfileSection $section) => $section->toArray())
                    ->toArray(),
            ];
        });

        return Inertia::render('About', $payload);
    }
}
