<?php

namespace Database\Seeders;

use App\Models\Agenda;
use Illuminate\Database\Seeder;

class AgendaSeeder extends Seeder
{
    public function run(): void
    {
        $agendas = [
            [
                'title' => 'Ujian Tengah Semester',
                'description' => 'Pelaksanaan ujian tengah semester untuk semua kelas dimulai pukul 07:00 WIB.',
                'start_date' => '2026-05-25',
                'end_date' => '2026-05-30',
                'category' => 'exam',
                'color' => 'brand-500',
                'icon' => '📝',
                'is_active' => true,
            ],
            [
                'title' => 'Praktik Industri',
                'description' => 'Mahasiswa melaksanakan praktik industri di perusahaan mitra selama 3 bulan.',
                'start_date' => '2026-06-10',
                'end_date' => '2026-09-10',
                'category' => 'event',
                'color' => 'blue-500',
                'icon' => '🏭',
                'is_active' => true,
            ],
            [
                'title' => 'Pameran Karya Siswa',
                'description' => 'Pameran hasil karya dan proyek siswa dari berbagai jurusan keahlian.',
                'start_date' => '2026-07-20',
                'end_date' => '2026-07-25',
                'category' => 'event',
                'color' => 'purple-500',
                'icon' => '🎨',
                'is_active' => true,
            ],
            [
                'title' => 'Wisata Edukasi',
                'description' => 'Kunjungan ke industri dan institusi pendidikan untuk menambah wawasan siswa.',
                'start_date' => '2026-08-15',
                'end_date' => '2026-08-20',
                'category' => 'event',
                'color' => 'green-500',
                'icon' => '🚌',
                'is_active' => true,
            ],
            [
                'title' => 'Ujian Akhir Semester',
                'description' => 'Ujian akhir semester untuk evaluasi pencapaian pembelajaran siswa.',
                'start_date' => '2026-11-10',
                'end_date' => '2026-11-25',
                'category' => 'exam',
                'color' => 'red-500',
                'icon' => '📚',
                'is_active' => true,
            ],
        ];

        foreach ($agendas as $agenda) {
            Agenda::create($agenda);
        }
    }
}
