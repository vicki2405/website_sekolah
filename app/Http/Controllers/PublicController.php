<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\SchoolOrgNode;
use App\Models\Teacher;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Contact;
use App\Models\Agenda;
use App\Models\Achievement;
use App\Models\Setting;
use App\Models\Tefa;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PublicController extends Controller
{
    public function programs()
    {
        $programs = Cache::remember('public_programs_list_v1', now()->addMinutes(10), function () {
            return Program::active()->get(['id', 'name', 'slug', 'icon', 'description', 'image', 'duration', 'order'])->toArray();
        });

        return Inertia::render('Programs', [
            'programs' => $programs,
        ]);
    }

    public function agenda()
    {
        $agendas = Cache::remember('public_agendas_payload_v2', now()->addMinutes(10), function () {
            return Agenda::active()
                ->get(['id', 'title', 'description', 'start_date', 'end_date', 'category', 'color', 'icon'])
                ->toArray();
        });

        return Inertia::render('Agenda', [
            'agendas' => $agendas,
        ]);
    }

    public function prestasi()
    {
        $page = Cache::remember('prestasi_page_settings', now()->addHours(6), function () {
            $map = Setting::query()
                ->whereIn('key', ['prestasi.hero_badge', 'prestasi.hero_title', 'prestasi.hero_description', 'prestasi.stats'])
                ->pluck('value', 'key')
                ->toArray();

            $stats = [];
            if (!empty($map['prestasi.stats'])) {
                $decoded = json_decode($map['prestasi.stats'], true);
                if (is_array($decoded)) {
                    $stats = $decoded;
                }
            }

            return [
                'hero_badge' => $map['prestasi.hero_badge'] ?? 'Prestasi & Pencapaian',
                'hero_title' => $map['prestasi.hero_title'] ?? 'Prestasi Sekolah',
                'hero_description' => $map['prestasi.hero_description'] ?? 'Ringkasan pencapaian yang mencerminkan kualitas pembelajaran, budaya prestasi, dan perkembangan sekolah dari waktu ke waktu.',
                'stats' => $stats,
            ];
        });

        $achievements = Cache::remember('public_prestasi_achievements_v1', now()->addMinutes(10), function () {
            return Achievement::query()->where('is_active', true)->orderBy('order')->get()->toArray();
        });

        return Inertia::render('Prestasi', [
            'achievements' => $achievements,
            'page' => $page,
        ]);
    }

    public function programTKR()
    {
        return Inertia::render('Programs/TKR');
    }

    public function programTBSM()
    {
        return Inertia::render('Programs/TBSM');
    }

    public function programTEI()
    {
        return Inertia::render('Programs/TEI');
    }

    public function programAnimasi()
    {
        return Inertia::render('Programs/Animasi');
    }

    public function programKuliner()
    {
        return Inertia::render('Programs/Kuliner');
    }

    public function keahlianShow(string $slug)
    {
        $program = Cache::remember("public_program_by_slug_v1:{$slug}", now()->addMinutes(10), function () use ($slug) {
            return Program::query()
                ->where('slug', $slug)
                ->where('is_active', true)
                ->first()
                ?->toArray();
        });

        if (!$program) {
            $aliasMap = [
                'tbsm' => 'tsm',
            ];

            $normalizedSlug = $aliasMap[strtolower($slug)] ?? $slug;

            $program = Cache::remember("public_program_by_slug_v1:{$normalizedSlug}", now()->addMinutes(10), function () use ($normalizedSlug) {
                return Program::query()
                    ->where('slug', $normalizedSlug)
                    ->where('is_active', true)
                    ->first()
                    ?->toArray();
            });
        }

        return Inertia::render('Programs/Show', [
            'slug' => $slug,
            'program' => $program,
        ]);
    }

    public function wakaShow(string $slug)
    {
        $waka = Cache::remember("public_waka_by_slug_v1:{$slug}", now()->addMinutes(10), function () use ($slug) {
            return \App\Models\Waka::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail()
                ->toArray();
        });

        $allWaka = Cache::remember('public_all_waka_list_v1', now()->addMinutes(10), function () {
            return \App\Models\Waka::where('is_active', true)
                ->orderBy('order')
                ->get(['id', 'title', 'slug', 'image', 'photo_name'])
                ->toArray();
        });

        return Inertia::render('Waka/Show', [
            'waka' => $waka,
            'allWaka' => $allWaka,
        ]);
    }

    public function tefaShow(string $slug)
    {
        $tefa = Cache::remember("public_tefa_by_slug_v1:{$slug}", now()->addMinutes(10), function () use ($slug) {
            return Tefa::query()
                ->where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail()
                ->toArray();
        });

        return Inertia::render('TEFA/Show', [
            'tefa' => $tefa,
        ]);
    }

    public function facilities()
    {
        $payload = Cache::remember('public_facilities_payload_v1', now()->addMinutes(10), function () {
            $categories = FacilityCategory::query()
                ->where('is_active', true)
                ->orderBy('order')
                ->get(['id', 'name', 'slug'])
                ->toArray();

            return [
                'facilities' => Facility::active()->get(['id', 'title', 'description', 'image', 'gallery', 'icon', 'category'])->toArray(),
                'categories' => $categories,
            ];
        });

        return Inertia::render('Facilities', $payload);
    }

    public function facilityShow(int $id)
    {
        $facility = Facility::query()
            ->where('id', $id)
            ->where('is_active', true)
            ->firstOrFail()
            ->toArray();

        return Inertia::render('Facilities/Show', [
            'facility' => $facility,
        ]);
    }

    public function teachers()
    {
        $payload = Cache::remember('public_teachers_payload_v2', now()->addMinutes(10), function () {
            $orgNodes = SchoolOrgNode::query()
                ->where('is_active', true)
                ->select(['id', 'parent_id', 'title', 'order'])
                ->with([
                    'teachers' => fn ($q) => $q->where('teachers.is_active', true)
                        ->orderBy('teachers.order')
                        ->select(['teachers.id', 'teachers.name', 'teachers.position', 'teachers.category', 'teachers.photo', 'teachers.email', 'teachers.phone', 'teachers.order']),
                ])
                ->orderBy('order')
                ->orderBy('id')
                ->get();

            $orgImage = \App\Models\Setting::where('key', 'org_structure_image')->value('value');
            if ($orgImage && !str_starts_with($orgImage, 'http')) {
                $orgImage = asset($orgImage);
            }

            return [
                'teachers' => Teacher::active()
                    ->get(['id', 'name', 'subject', 'position', 'category', 'photo', 'bio', 'email', 'phone', 'order'])
                    ->toArray(),
                'categories' => \App\Models\TeacherCategory::query()->orderBy('order')->get(['id', 'name'])->toArray(),
                'orgFlat' => $this->flattenOrgNodes($orgNodes),
                'orgImage' => $orgImage,
            ];
        });

        return Inertia::render('Teachers', $payload);
    }

    public function gallery()
    {
        $payload = Cache::remember('public_gallery_payload_v1', now()->addMinutes(10), function () {
            $categories = GalleryCategory::query()
                ->where('is_active', true)
                ->orderBy('order')
                ->get(['id', 'name', 'slug'])
                ->toArray();

            return [
                'gallery'    => Gallery::active()->get(['id', 'image', 'title', 'description', 'category'])->toArray(),
                'categories' => $categories,
            ];
        });

        return Inertia::render('Gallery', $payload);
    }

    public function contact()
    {
        $ppdb = Cache::remember('public_ppdb_settings_v1', now()->addMinutes(10), function () {
            $keys = [
                'ppdb.hero_badge',
                'ppdb.hero_title',
                'ppdb.hero_subtitle',
                'ppdb.poster_image',
                'ppdb.poster_images',
                'ppdb.registration_link',
                'ppdb.registration_status',
                'ppdb.whatsapp_number',
                'ppdb.whatsapp_text',
                'ppdb.hotline_name',
                'ppdb.address',
                'ppdb.email',
                'ppdb.waves',
                'ppdb.requirements',
                'ppdb.steps',
            ];

            $map = Setting::query()->whereIn('key', $keys)->pluck('value', 'key')->toArray();

            $posters = [];
            if (!empty($map['ppdb.poster_images'])) {
                $decoded = json_decode($map['ppdb.poster_images'], true);
                if (is_array($decoded)) {
                    $posters = array_values(array_filter($decoded, fn($u) => is_string($u) && trim($u) !== ''));
                }
            }
            if (empty($posters) && !empty($map['ppdb.poster_image'])) {
                $posters = [$map['ppdb.poster_image']];
            }

            return [
                'hero_badge'          => $map['ppdb.hero_badge'] ?? 'PPDB 2026/2027',
                'hero_title'          => $map['ppdb.hero_title'] ?? 'Penerimaan Peserta Didik Baru',
                'hero_subtitle'       => $map['ppdb.hero_subtitle'] ?? 'Bergabunglah bersama SMKS Muhammadiyah 2 Genteng, sekolah vokasi pusat keunggulan siap kerja di era industri 4.0.',
                'poster_image'        => $posters[0] ?? $map['ppdb.poster_image'] ?? null,
                'poster_images'       => $posters,
                'registration_link'   => $map['ppdb.registration_link'] ?? null,
                'registration_status' => $map['ppdb.registration_status'] ?? 'Buka',
                'whatsapp_number'     => $map['ppdb.whatsapp_number'] ?? '081236453325',
                'whatsapp_text'       => $map['ppdb.whatsapp_text'] ?? 'Halo Panitia PPDB SMKS Muhammadiyah 2 Genteng, saya ingin bertanya mengenai pendaftaran siswa baru.',
                'hotline_name'        => $map['ppdb.hotline_name'] ?? 'Panitia PPDB STEMDA',
                'address'             => $map['ppdb.address'] ?? 'JL Hasanudin 103, Genteng Wetan - Genteng, Banyuwangi',
                'email'               => $map['ppdb.email'] ?? 'smkmudagenteng@gmail.com',
                'waves'               => $map['ppdb.waves'] ?? null,
                'requirements'        => $map['ppdb.requirements'] ?? null,
                'steps'               => $map['ppdb.steps'] ?? null,
            ];
        });

        return Inertia::render('Contact', [
            'ppdb' => $ppdb,
        ]);
    }

    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        Contact::create($validated);
        Cache::forget('admin_contacts_list');

        return redirect()->route('contact')->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
    }

    private function flattenOrgNodes(iterable $nodes): array
    {
        $byParent = [];
        foreach ($nodes as $n) {
            $node = $n instanceof \Illuminate\Database\Eloquent\Model ? $n->toArray() : (array) $n;
            $byParent[$node['parent_id'] ?? 0][] = $node;
        }

        foreach ($byParent as $parentId => $children) {
            usort($children, fn ($a, $b) => (($a['order'] ?? 0) <=> ($b['order'] ?? 0)) ?: (($a['id'] ?? 0) <=> ($b['id'] ?? 0)));
            $byParent[$parentId] = $children;
        }

        $out = [];
        $walk = function ($parentId, $depth) use (&$walk, &$out, $byParent) {
            foreach ($byParent[$parentId] ?? [] as $node) {
                $nodeId = (int) ($node['id'] ?? 0);
                $out[] = [
                    'node' => $node,
                    'depth' => $depth,
                ];
                $walk($nodeId, $depth + 1);
            }
        };

        $walk(0, 0);

        return $out;
    }
}
