<?php

namespace Tests\Feature;

use App\Models\Agenda;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_agenda_page_uses_active_agenda_data(): void
    {
        Agenda::create([
            'title' => 'Agenda Sinkron',
            'description' => 'Agenda dari admin harus tampil di halaman publik.',
            'start_date' => '2026-09-15',
            'end_date' => '2026-09-16',
            'category' => 'Akademik',
            'color' => 'brand-500',
            'icon' => '📅',
            'is_active' => true,
        ]);

        Agenda::create([
            'title' => 'Agenda Nonaktif',
            'description' => 'Tidak boleh tampil di publik.',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-01',
            'category' => 'Internal',
            'color' => 'brand-500',
            'icon' => '📅',
            'is_active' => false,
        ]);

        $response = $this->get('/event/agenda');

        $response->assertOk();
        $response->assertSee('Agenda Sinkron');
        $response->assertSee('Akademik');
        $response->assertDontSee('Agenda Nonaktif');
    }
}
