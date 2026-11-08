<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ImageCompression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AppearanceController extends Controller
{
    use ImageCompression;

    public function __construct()
    {
        $this->middleware('permission:admin.appearance.view|admin.home.view')->only(['index']);
        $this->middleware('permission:admin.appearance.edit|admin.home.edit')->only(['update', 'resetLogo', 'resetFavicon']);
    }

    public function index()
    {
        $keys = [
            'school_logo',
            'school_favicon',
            'school_name_primary',
            'school_name_secondary',
            'school_tagline',
        ];

        $settings = Setting::query()->whereIn('key', $keys)->pluck('value', 'key')->toArray();

        return view('admin.appearance', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_name_primary'   => 'nullable|string|max:100',
            'school_name_secondary' => 'nullable|string|max:100',
            'school_tagline'        => 'nullable|string|max:255',
            'logo_file'             => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:10240', // Maks 10MB, auto-convert & auto-downscale ke WebP
            ],
            'favicon_file'          => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/webp,image/x-icon,image/vnd.microsoft.icon',
                'max:5120',
            ],
            'auto_favicon'          => 'nullable|boolean',
        ]);

        // Simpan identitas teks navbar & branding
        Setting::updateOrCreate(
            ['key' => 'school_name_primary'],
            ['value' => $request->input('school_name_primary', 'STEMDA')]
        );
        Setting::updateOrCreate(
            ['key' => 'school_name_secondary'],
            ['value' => $request->input('school_name_secondary', 'GENTENG')]
        );
        Setting::updateOrCreate(
            ['key' => 'school_tagline'],
            ['value' => $request->input('school_tagline', 'Outstanding and Innovative Vocational School')]
        );

        // Proses unggah logo sekolah
        if ($request->hasFile('logo_file')) {
            $logoFile = $request->file('logo_file');

            // Hapus berkas logo lama jika ada
            $oldLogo = Setting::where('key', 'school_logo')->value('value');
            if ($oldLogo) {
                $this->deleteStoredFile($oldLogo);
            }

            // Auto-convert ke WebP & downscale ke max width 600px dengan transparansi terjaga
            $storedPath = $this->compressAndStore($logoFile, 'images', 85, 600, 150000);
            $logoUrl = Storage::url($storedPath);

            Setting::updateOrCreate(
                ['key' => 'school_logo'],
                ['value' => $logoUrl]
            );

            // Opsi 1-klik: Jadikan logo sebagai favicon browser secara otomatis
            if ($request->boolean('auto_favicon')) {
                $oldFavicon = Setting::where('key', 'school_favicon')->value('value');
                if ($oldFavicon) {
                    $this->deleteStoredFile($oldFavicon);
                }

                $favPath = $this->compressAndStore($logoFile, 'images', 85, 128, 50000);
                $faviconUrl = Storage::url($favPath);

                Setting::updateOrCreate(
                    ['key' => 'school_favicon'],
                    ['value' => $faviconUrl]
                );
            }
        }

        // Proses unggah favicon kustom terpisah jika disediakan
        if ($request->hasFile('favicon_file')) {
            $favFile = $request->file('favicon_file');
            $oldFavicon = Setting::where('key', 'school_favicon')->value('value');
            if ($oldFavicon) {
                $this->deleteStoredFile($oldFavicon);
            }

            $favPath = $this->compressAndStore($favFile, 'images', 85, 128, 50000);
            $faviconUrl = Storage::url($favPath);

            Setting::updateOrCreate(
                ['key' => 'school_favicon'],
                ['value' => $faviconUrl]
            );
        }

        Cache::forget('site_settings');
        Cache::forget('public_site_settings');
        Cache::forget('public_site_favicon');
        Cache::forget('public_site_logo');
        Cache::forget('public_site_name');

        return redirect()->route('admin.appearance.index')->with('success', 'Tampilan dan identitas website berhasil diperbarui!');
    }

    public function resetLogo()
    {
        $oldLogo = Setting::where('key', 'school_logo')->value('value');
        if ($oldLogo) {
            $this->deleteStoredFile($oldLogo);
            Setting::where('key', 'school_logo')->delete();
        }

        Cache::forget('site_settings');
        Cache::forget('public_site_settings');
        Cache::forget('public_site_favicon');
        Cache::forget('public_site_logo');
        Cache::forget('public_site_name');

        return redirect()->route('admin.appearance.index')->with('success', 'Logo sekolah berhasil direset ke default.');
    }

    public function resetFavicon()
    {
        $oldFavicon = Setting::where('key', 'school_favicon')->value('value');
        if ($oldFavicon) {
            $this->deleteStoredFile($oldFavicon);
            Setting::where('key', 'school_favicon')->delete();
        }

        Cache::forget('site_settings');
        Cache::forget('public_site_settings');
        Cache::forget('public_site_favicon');
        Cache::forget('public_site_logo');
        Cache::forget('public_site_name');

        return redirect()->route('admin.appearance.index')->with('success', 'Favicon berhasil direset ke default.');
    }

    private function deleteStoredFile(?string $url): void
    {
        if (!$url) return;
        $path = str_replace('/storage/', '', $url);
        Storage::disk('public')->delete($path);
    }
}
