<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VisitorLog;
use App\Traits\HtmlSanitizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;
    use HtmlSanitizer;

    protected function setUp(): void
    {
        parent::setUp();

        // Create core permissions
        Permission::findOrCreate('admin.access.view', 'web');
        Permission::findOrCreate('admin.visitors.view', 'web');
        Permission::findOrCreate('admin.visitors.delete', 'web');
        Permission::findOrCreate('admin.system_information.view', 'web');
        Permission::findOrCreate('admin.system_information.edit', 'web');

        Role::findOrCreate('superadmin', 'web');
        Role::findOrCreate('admin', 'web');
    }

    public function test_html_sanitizer_removes_dangerous_tags_and_protocols(): void
    {
        // 1. Script tags
        $input = '<p>Normal text</p><script>alert("xss")</script>';
        $cleaned = $this->sanitizeHtml($input);
        $this->assertStringNotContainsString('<script>', $cleaned);
        $this->assertStringNotContainsString('alert', $cleaned);
        $this->assertStringContainsString('<p>Normal text</p>', $cleaned);

        // 2. Inline event handlers
        $input = '<img src="valid.jpg" onerror="alert(1)">';
        $cleaned = $this->sanitizeHtml($input);
        $this->assertStringNotContainsString('onerror', $cleaned);

        // 3. Javascript protocol in href
        $input = '<a href="javascript:alert(1)">Click me</a>';
        $cleaned = $this->sanitizeHtml($input);
        $this->assertStringNotContainsString('javascript:', $cleaned);

        // 4. Encoded javascript protocol
        $input = '<a href="jav&#x09;ascript:alert(1)">Click me</a>';
        $cleaned = $this->sanitizeHtml($input);
        $this->assertStringNotContainsString('alert(1)', $cleaned);

        // 5. Preserves legitimate rich text elements
        $input = '<p><strong>Bold</strong> and <em>Italic</em></p><ul><li>List Item</li></ul>';
        $cleaned = $this->sanitizeHtml($input);
        $this->assertEquals($input, $cleaned);
    }

    public function test_non_authorized_user_cannot_view_visitor_analytics(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo('admin.access.view'); // Has basic admin access, but NOT admin.visitors.view

        $response = $this->actingAs($user)->get(route('admin.visitors.index'));
        $response->assertStatus(403);
    }

    public function test_non_superadmin_cannot_reset_visitor_logs(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['admin.access.view', 'admin.visitors.view', 'admin.visitors.delete']);

        VisitorLog::create([
            'ip_address' => '1.2.3.4',
            'url' => '/',
            'page_title' => 'Home',
            'method' => 'GET',
            'device' => 'Desktop',
            'browser' => 'Chrome',
            'platform' => 'Windows',
            'session_id' => 'abc',
        ]);

        $this->assertDatabaseCount('visitor_logs', 1);

        $response = $this->actingAs($user)->post(route('admin.visitors.reset'));
        $response->assertRedirect(route('admin.visitors.index'));
        $response->assertSessionHas('error');

        // Logs must still exist because user is not superadmin
        $this->assertDatabaseCount('visitor_logs', 1);
    }

    public function test_superadmin_can_reset_visitor_logs(): void
    {
        $superadmin = User::factory()->create();
        $superadminRole = Role::findByName('superadmin', 'web');
        $superadminRole->givePermissionTo(['admin.access.view', 'admin.visitors.view', 'admin.visitors.delete']);
        $superadmin->assignRole('superadmin');

        VisitorLog::create([
            'ip_address' => '1.2.3.4',
            'url' => '/',
            'page_title' => 'Home',
            'method' => 'GET',
            'device' => 'Desktop',
            'browser' => 'Chrome',
            'platform' => 'Windows',
            'session_id' => 'abc',
        ]);

        $this->assertDatabaseCount('visitor_logs', 1);

        $response = $this->actingAs($superadmin)->post(route('admin.visitors.reset'));
        $response->assertRedirect(route('admin.visitors.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('visitor_logs', 0);
    }

    public function test_system_information_menu_rejects_unsafe_urls(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['admin.access.view', 'admin.system_information.view', 'admin.system_information.edit']);

        $response = $this->actingAs($user)->post(route('admin.system-information.store'), [
            'name' => 'Malicious Link',
            'url' => 'javascript:alert(1)',
        ]);

        $response->assertSessionHasErrors('url');
        $this->assertDatabaseMissing('system_information_menus', [
            'name' => 'Malicious Link',
        ]);
    }

    public function test_system_information_menu_accepts_valid_urls(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['admin.access.view', 'admin.system_information.view', 'admin.system_information.edit']);

        $response = $this->actingAs($user)->post(route('admin.system-information.store'), [
            'name' => 'Portal Siswa',
            'url' => 'https://siswa.smkm2genteng.sch.id',
        ]);

        $response->assertRedirect(route('admin.system-information.index'));
        $this->assertDatabaseHas('system_information_menus', [
            'name' => 'Portal Siswa',
            'url' => 'https://siswa.smkm2genteng.sch.id',
        ]);
    }

    public function test_dashboard_hides_unauthorized_action_buttons(): void
    {
        Permission::findOrCreate('admin.dashboard.view', 'web');

        $user = User::factory()->create();
        // User only has view access to dashboard, but NO permission to create news or view visitors
        $user->givePermissionTo(['admin.access.view', 'admin.dashboard.view']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // "Tulis Berita Baru" button should NOT be rendered
        $response->assertDontSee('Tulis Berita Baru');

        // Shortcut to Pengunjung should NOT be rendered
        $response->assertDontSee(route('admin.visitors.index'));
    }

    public function test_dashboard_displays_authorized_action_buttons(): void
    {
        Permission::findOrCreate('admin.dashboard.view', 'web');
        Permission::findOrCreate('admin.news.create', 'web');
        Permission::findOrCreate('admin.visitors.view', 'web');

        $user = User::factory()->create();
        $user->givePermissionTo([
            'admin.access.view',
            'admin.dashboard.view',
            'admin.news.create',
            'admin.visitors.view',
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // "Tulis Berita Baru" button MUST be rendered
        $response->assertSee('Tulis Berita Baru');

        // Shortcut to Pengunjung MUST be rendered
        $response->assertSee(route('admin.visitors.index'));
    }
}
