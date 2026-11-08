<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AppearanceManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Setup roles & permissions
        $permView = Permission::firstOrCreate(['name' => 'admin.appearance.view', 'guard_name' => 'web']);
        $permEdit = Permission::firstOrCreate(['name' => 'admin.appearance.edit', 'guard_name' => 'web']);
        $permAccess = Permission::firstOrCreate(['name' => 'admin.access.view', 'guard_name' => 'web']);

        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo([$permView, $permEdit, $permAccess]);

        $this->adminUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->adminUser->assignRole($role);
    }

    public function test_guest_cannot_access_appearance_page(): void
    {
        $response = $this->get(route('admin.appearance.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_appearance_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.appearance.index'));
        $response->assertStatus(200);
        $response->assertSee('Live Preview Header Website');
        $response->assertSee('Logo Aktif Saat Ini');
    }

    public function test_admin_can_update_branding_text(): void
    {
        $response = $this->actingAs($this->adminUser)->put(route('admin.appearance.update'), [
            'school_name_primary'   => 'SMK MUH 2',
            'school_name_secondary' => 'BANYUWANGI',
            'school_tagline'        => 'Sekolah Pusat Keunggulan',
        ]);

        $response->assertRedirect(route('admin.appearance.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('settings', [
            'key'   => 'school_name_primary',
            'value' => 'SMK MUH 2',
        ]);
        $this->assertDatabaseHas('settings', [
            'key'   => 'school_name_secondary',
            'value' => 'BANYUWANGI',
        ]);
        $this->assertDatabaseHas('settings', [
            'key'   => 'school_tagline',
            'value' => 'Sekolah Pusat Keunggulan',
        ]);
    }

    public function test_admin_can_upload_logo_with_auto_conversion_and_favicon(): void
    {
        // Create a fake high resolution PNG logo image
        $file = UploadedFile::fake()->image('large_logo.png', 1200, 1200);

        $response = $this->actingAs($this->adminUser)->put(route('admin.appearance.update'), [
            'school_name_primary'   => 'STEMDA',
            'school_name_secondary' => 'GENTENG',
            'school_tagline'        => 'Outstanding and Innovative Vocational School',
            'logo_file'             => $file,
            'auto_favicon'          => '1',
        ]);

        $response->assertRedirect(route('admin.appearance.index'));
        $response->assertSessionHas('success');

        $logoUrl = Setting::where('key', 'school_logo')->value('value');
        $this->assertNotNull($logoUrl);
        $this->assertStringContainsString('/storage/images/', $logoUrl);
        // Trait auto-converts to .webp
        $this->assertStringEndsWith('.webp', $logoUrl);

        $favUrl = Setting::where('key', 'school_favicon')->value('value');
        $this->assertNotNull($favUrl);
        $this->assertStringContainsString('/storage/images/', $favUrl);
        $this->assertStringEndsWith('.webp', $favUrl);
    }

    public function test_admin_can_reset_logo_and_favicon(): void
    {
        Setting::create(['key' => 'school_logo', 'value' => '/storage/images/dummy.webp']);
        Setting::create(['key' => 'school_favicon', 'value' => '/storage/images/dummy_fav.webp']);

        $resLogo = $this->actingAs($this->adminUser)->delete(route('admin.appearance.reset-logo'));
        $resLogo->assertRedirect(route('admin.appearance.index'));
        $this->assertDatabaseMissing('settings', ['key' => 'school_logo']);

        $resFav = $this->actingAs($this->adminUser)->delete(route('admin.appearance.reset-favicon'));
        $resFav->assertRedirect(route('admin.appearance.index'));
        $this->assertDatabaseMissing('settings', ['key' => 'school_favicon']);
    }

    public function test_public_pages_receive_site_settings_in_inertia(): void
    {
        Setting::create(['key' => 'school_name_primary', 'value' => 'CUSTOM_PRIMARY']);
        Setting::create(['key' => 'school_name_secondary', 'value' => 'CUSTOM_SECONDARY']);
        Setting::create(['key' => 'school_tagline', 'value' => 'CUSTOM_TAGLINE']);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->has('site_settings', fn ($settings) => $settings
                ->where('name_primary', 'CUSTOM_PRIMARY')
                ->where('name_secondary', 'CUSTOM_SECONDARY')
                ->where('tagline', 'CUSTOM_TAGLINE')
                ->etc()
            )
        );
    }
}
