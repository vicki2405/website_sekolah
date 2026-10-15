<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Traits\ImageCompression;

class ContactController extends Controller
{
    use ImageCompression;

    public const PPDB_SETTING_KEYS = [
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

    public function __construct()
    {
        $this->middleware('permission:admin.contacts.view')->only(['index', 'show']);
        $this->middleware('permission:admin.contacts.delete')->only(['destroy']);
    }

    private function clearCache()
    {
        Cache::forget('admin_contacts_list');
        Cache::forget('public_ppdb_settings_v1');
    }

    public function index()
    {
        $contacts = Cache::remember('admin_contacts_list', now()->addMinutes(10), function () {
            return Contact::query()->orderBy('created_at', 'desc')
                ->get(['id', 'name', 'email', 'subject', 'message', 'created_at', 'is_read']);
        });

        $ppdbSettings = Setting::query()
            ->whereIn('key', self::PPDB_SETTING_KEYS)
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.contacts', compact('contacts', 'ppdbSettings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'hero_badge'          => ['nullable', 'string', 'max:255'],
            'hero_title'          => ['nullable', 'string', 'max:255'],
            'hero_subtitle'       => ['nullable', 'string'],
            'poster_image'        => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/gif,image/webp', 'max:5120'],
            'poster_images'       => ['nullable', 'array'],
            'poster_images.*'     => ['file', 'mimetypes:image/jpeg,image/png,image/gif,image/webp', 'max:5120'],
            'existing_posters'    => ['nullable', 'array'],
            'existing_posters.*'  => ['string'],
            'registration_link'   => ['nullable', 'string', 'max:500'],
            'registration_status' => ['nullable', 'string', 'max:50'],
            'whatsapp_number'     => ['nullable', 'string', 'max:50'],
            'whatsapp_text'       => ['nullable', 'string', 'max:500'],
            'hotline_name'        => ['nullable', 'string', 'max:255'],
            'address'             => ['nullable', 'string', 'max:500'],
            'email'               => ['nullable', 'email', 'max:255'],
            'waves'               => ['nullable', 'string'],
            'requirements'        => ['nullable', 'string'],
            'steps'               => ['nullable', 'string'],
        ]);

        // Manage existing posters
        $currentPosters = [];
        if ($request->has('posters_submitted')) {
            $currentPosters = (array) $request->input('existing_posters', []);
        } else {
            $savedSetting = Setting::where('key', 'ppdb.poster_images')->value('value');
            if ($savedSetting) {
                $decoded = json_decode($savedSetting, true);
                if (is_array($decoded)) {
                    $currentPosters = $decoded;
                }
            } elseif ($savedSingle = Setting::where('key', 'ppdb.poster_image')->value('value')) {
                $currentPosters = [$savedSingle];
            }
        }

        // Clean up array
        $currentPosters = array_values(array_filter($currentPosters, fn ($url) => is_string($url) && trim($url) !== ''));

        // Handle multiple new poster files
        if ($request->hasFile('poster_images')) {
            foreach ($request->file('poster_images') as $file) {
                if ($file && $file->isValid()) {
                    $path = $this->compressAndStore($file, 'uploads/ppdb');
                    $currentPosters[] = '/storage/' . $path;
                }
            }
        }

        // Handle single poster file (backward compatibility)
        if ($request->hasFile('poster_image')) {
            $file = $request->file('poster_image');
            if ($file && $file->isValid()) {
                $path = $this->compressAndStore($file, 'uploads/ppdb');
                $currentPosters[] = '/storage/' . $path;
            }
        }

        $currentPosters = array_values(array_unique($currentPosters));

        $map = [
            'ppdb.hero_badge'          => $validated['hero_badge'] ?? 'PPDB 2026/2027',
            'ppdb.hero_title'          => $validated['hero_title'] ?? 'Penerimaan Peserta Didik Baru',
            'ppdb.hero_subtitle'       => $validated['hero_subtitle'] ?? 'Bergabunglah bersama SMKS Muhammadiyah 2 Genteng, sekolah vokasi pusat keunggulan siap kerja di era industri 4.0.',
            'ppdb.registration_link'   => $validated['registration_link'] ?? null,
            'ppdb.registration_status' => $validated['registration_status'] ?? 'Buka',
            'ppdb.whatsapp_number'     => $validated['whatsapp_number'] ?? '081236453325',
            'ppdb.whatsapp_text'       => $validated['whatsapp_text'] ?? 'Halo Panitia PPDB SMKS Muhammadiyah 2 Genteng, saya ingin bertanya mengenai pendaftaran siswa baru.',
            'ppdb.hotline_name'        => $validated['hotline_name'] ?? 'Panitia PPDB STEMDA',
            'ppdb.address'             => $validated['address'] ?? 'JL Hasanudin 103, Genteng Wetan - Genteng, Banyuwangi',
            'ppdb.email'               => $validated['email'] ?? 'smkmudagenteng@gmail.com',
            'ppdb.waves'               => $validated['waves'] ?? null,
            'ppdb.requirements'        => $validated['requirements'] ?? null,
            'ppdb.steps'               => $validated['steps'] ?? null,
            'ppdb.poster_images'       => json_encode($currentPosters),
            'ppdb.poster_image'        => $currentPosters[0] ?? null,
        ];

        foreach ($map as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->clearCache();

        return redirect()->route('admin.contacts.index')->with('success', 'Pengaturan Informasi PPDB berhasil diperbarui!');
    }

    public function show(Contact $contact)
    {
        if (!$contact->is_read) {
            $contact->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
            $this->clearCache();
        }

        return view('admin.contacts.show', ['contact' => $contact->fresh()]);
    }

    public function destroy(Contact $contact)
    {
        Contact::destroy($contact->id);
        $this->clearCache();

        return redirect()->route('admin.contacts.index');
    }
}
