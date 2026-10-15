<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Program;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Achievement;
use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $guard = 'web';

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionGroups = [
            'admin.access' => ['view'],
            'admin.dashboard' => ['view'],
            'admin.home' => ['view', 'create', 'edit', 'delete'],
            'admin.profile' => ['view', 'edit'],
            'admin.backup' => ['view', 'export', 'import'],
            'admin.prestasi' => ['view', 'create', 'edit', 'delete'],
            'admin.waka' => ['view', 'create', 'edit', 'delete'],
            'admin.programs' => ['view', 'create', 'edit', 'delete'],
            'admin.news' => ['view', 'create', 'edit', 'delete'],
            'admin.agendas' => ['view', 'create', 'edit', 'delete'],
            'admin.teachers' => ['view', 'create', 'edit', 'delete'],
            'admin.teacher_categories' => ['view', 'create', 'edit', 'delete'],
            'admin.system_information' => ['view', 'edit', 'delete'],
            'admin.facilities' => ['view', 'create', 'edit', 'delete'],
            'admin.facility_categories' => ['view', 'create', 'edit', 'delete'],
            'admin.gallery' => ['view', 'create', 'edit', 'delete'],
            'admin.gallery_categories' => ['view', 'create', 'edit', 'delete'],
            'admin.contacts' => ['view', 'delete'],
            'admin.tefa' => ['view', 'create', 'edit', 'delete'],
            'admin.users' => ['view', 'create', 'edit', 'delete'],
            'admin.roles' => ['view', 'create', 'edit', 'delete'],
        ];

        $permissions = [];
        foreach ($permissionGroups as $group => $actions) {
            foreach ($actions as $action) {
                $permissions[] = "{$group}.{$action}";
            }
        }

        foreach ($permissions as $p) {
            Permission::findOrCreate($p, $guard);
        }

        $adminRole = Role::findOrCreate('admin', $guard);
        $superAdminRole = Role::findOrCreate('superadmin', $guard);

        $allPermissions = Permission::query()->whereIn('name', $permissions)->get();
        $adminRole->syncPermissions($allPermissions);
        $superAdminRole->syncPermissions($allPermissions);

        if (app()->environment('local')) {
            $defaultPassword = Hash::make('password');

            $adminUser = User::query()->updateOrCreate(
                ['email' => 'admin@gmail.com'],
                ['name' => 'Admin Sekolah', 'password' => $defaultPassword, 'email_verified_at' => now()],
            );
            $adminUser->syncRoles([$adminRole]);

            $superAdminUser = User::query()->updateOrCreate(
                ['email' => 'superadmin@gmail.com'],
                ['name' => 'Super Admin', 'password' => $defaultPassword, 'email_verified_at' => now()],
            );
            $superAdminUser->syncRoles([$superAdminRole]);
        }

        if (!Slider::query()->exists()) {
            $sliders = [
                [
                    'title' => 'Selamat Datang di SMKS Muhammadiyah 2 Genteng',
                    'subtitle' => 'Sekolah vokasi yang membentuk karakter, kompetensi, dan kesiapan kerja melalui pembelajaran berorientasi industri.',
                    'image' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1600',
                    'button_text' => 'Lihat Profil',
                    'button_link' => '/profil',
                    'order' => 1,
                    'is_active' => true,
                ],
                [
                    'title' => 'Program Keahlian Unggulan',
                    'subtitle' => 'TKR, TBSM, TEI, Animasi Digital, dan Kuliner dengan fasilitas praktik yang lengkap dan modern.',
                    'image' => 'https://images.unsplash.com/photo-1562774053-701939374585?w=1600',
                    'button_text' => 'Lihat Keahlian',
                    'button_link' => '/keahlian/tkr',
                    'order' => 2,
                    'is_active' => true,
                ],
                [
                    'title' => 'Kolaborasi dengan Dunia Industri',
                    'subtitle' => 'Membangun jejaring mitra untuk magang, sertifikasi, dan penempatan kerja lulusan.',
                    'image' => 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=1600',
                    'button_text' => 'Hubungi Kami',
                    'button_link' => '/kontak',
                    'order' => 3,
                    'is_active' => true,
                ],
            ];
            foreach ($sliders as $s) {
                Slider::create($s);
            }
        }

        if (!Achievement::query()->exists()) {
            $achievements = [
                ['title' => 'Siswa Aktif', 'value' => '1.200+', 'icon' => 'users', 'description' => 'Siswa terdaftar aktif', 'order' => 1],
                ['title' => 'Tenaga Pengajar', 'value' => '85+', 'icon' => 'chalkboard-teacher', 'description' => 'Guru berpengalaman & bersertifikat', 'order' => 2],
                ['title' => 'Tahun Berdiri', 'value' => '25+', 'icon' => 'school', 'description' => 'Pengalaman mendidik generasi bangsa', 'order' => 3],
                ['title' => 'Prestasi Nasional', 'value' => '300+', 'icon' => 'trophy', 'description' => 'Penghargaan akademik & non-akademik', 'order' => 4],
                ['title' => 'Alumni Sukses', 'value' => '10.000+', 'icon' => 'graduation-cap', 'description' => 'Alumni berkarier di berbagai bidang', 'order' => 5],
                ['title' => 'Kelulusan 100%', 'value' => '100%', 'icon' => 'check-circle', 'description' => 'Tingkat kelulusan setiap tahun', 'order' => 6],
            ];
            foreach ($achievements as $a) {
                Achievement::create(array_merge($a, ['is_active' => true]));
            }
        }

        if (!Program::query()->exists()) {
            $programs = [
            [
                'name' => 'Teknik Kendaraan Ringan',
                'slug' => 'tkr',
                'description' => 'Mempelajari pemeliharaan dan perbaikan mesin otomotif, sistem sasis, pemindah tenaga, dan kelistrikan kendaraan ringan.',
                'icon' => 'car',
                'image' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=800',
                'duration' => 3,
                'order' => 1,
            ],
            [
                'name' => 'Teknik Bisnis Sepeda Motor',
                'slug' => 'tbsm',
                'description' => 'Kompetensi keahlian yang memfokuskan pada pemeliharaan dan perbaikan sepeda motor serta manajemen bisnis bengkel.',
                'icon' => 'motorbike',
                'image' => 'https://images.unsplash.com/photo-1558981403-c5f91cbba527?w=800',
                'duration' => 3,
                'order' => 2,
            ],
            [
                'name' => 'Teknik Elektronika Industri',
                'slug' => 'tei',
                'description' => 'Mempelajari sistem kontrol industri berbasis PLC, mikroprosesor, dan pneumatik untuk otomasi industri modern.',
                'icon' => 'electronics',
                'image' => 'https://images.unsplash.com/photo-1517077304055-6e89abbf09b0?w=800',
                'duration' => 3,
                'order' => 3,
            ],
            [
                'name' => 'Animasi Digital',
                'slug' => 'animasi',
                'description' => 'Penguasaan teknik pembuatan animasi 2D dan 3D, desain karakter, serta efek visual untuk industri kreatif.',
                'icon' => 'animation',
                'image' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800',
                'duration' => 3,
                'order' => 4,
            ],
            [
                'name' => 'Kuliner (Tata Boga)',
                'slug' => 'kuliner',
                'description' => 'Mempelajari teknik pengolahan makanan nusantara dan internasional, manajemen restoran, serta seni penyajian hidangan.',
                'icon' => 'culinary',
                'image' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800',
                'duration' => 3,
                'order' => 5,
            ],
            ];
            foreach ($programs as $p) {
                Program::create(array_merge($p, ['is_active' => true]));
            }
        }

        if (!Teacher::query()->exists()) {
            $teachers = [
            ['name' => 'Dr. Budi Santoso, M.Pd', 'subject' => 'Matematika', 'position' => 'Kepala Sekolah', 'photo' => 'https://randomuser.me/api/portraits/men/1.jpg', 'bio' => 'Doktor Pendidikan Matematika dengan pengalaman 20 tahun.', 'email' => 'budi@smkm2genteng.sch.id', 'order' => 1],
            ['name' => 'Siti Rahayu, S.Pd, M.Si', 'subject' => 'Fisika', 'position' => 'Wakil Kepala Bidang Kurikulum', 'photo' => 'https://randomuser.me/api/portraits/women/2.jpg', 'bio' => 'Spesialis Fisika Modern dengan riset di bidang energi terbarukan.', 'email' => 'siti@smkm2genteng.sch.id', 'order' => 2],
            ['name' => 'Ahmad Fauzi, S.T, M.Kom', 'subject' => 'Informatika', 'position' => 'Ketua Program TI', 'photo' => 'https://randomuser.me/api/portraits/men/3.jpg', 'bio' => 'Insinyur Software dengan 10 tahun pengalaman industri sebelum mengajar.', 'email' => 'ahmad@smkm2genteng.sch.id', 'order' => 3],
            ['name' => 'Dewi Lestari, S.S, M.Hum', 'subject' => 'Bahasa Inggris', 'position' => 'Guru Senior', 'photo' => 'https://randomuser.me/api/portraits/women/4.jpg', 'bio' => 'Lulusan Master Linguistik Universitas Indonesia, aktif dalam program pertukaran budaya.', 'email' => 'dewi@smkm2genteng.sch.id', 'order' => 4],
            ['name' => 'Hendra Wijaya, S.Pd', 'subject' => 'Kimia', 'position' => 'Guru Senior', 'photo' => 'https://randomuser.me/api/portraits/men/5.jpg', 'bio' => 'Kimia Organik dan anorganik, pembimbing olimpiade kimia nasional.', 'email' => 'hendra@smkm2genteng.sch.id', 'order' => 5],
            ['name' => 'Rina Marlina, S.Pd', 'subject' => 'Biologi', 'position' => 'Koordinator Lab IPA', 'photo' => 'https://randomuser.me/api/portraits/women/6.jpg', 'bio' => 'Pakar biologi molekuler, aktif dalam penelitian lingkungan hidup.', 'email' => 'rina@smkm2genteng.sch.id', 'order' => 6],
            ];
            foreach ($teachers as $t) {
                Teacher::create(array_merge($t, ['is_active' => true]));
            }
        }

        if (!News::query()->exists()) {
            $news = [
            ['title' => 'SMKS Muhammadiyah 2 Genteng Raih Juara 1 LKS Tingkat Provinsi 2026', 'slug' => 'juara-lks-provinsi-2026', 'excerpt' => 'Siswa SMKS Muhammadiyah 2 Genteng berhasil meraih medali emas pada ajang Lomba Kompetensi Siswa (LKS) tingkat provinsi.', 'content' => '<p>Siswa SMKS Muhammadiyah 2 Genteng berhasil meraih prestasi gemilang dengan meraih medali emas pada ajang LKS 2026 bidang Otomotif.</p><p>Keberhasilan ini tidak terlepas dari bimbingan intensif dari para guru pembimbing dan fasilitas praktik yang modern.</p>', 'category' => 'Prestasi', 'image' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=800', 'is_published' => true, 'published_at' => now()->subDays(2)],
            ['title' => 'Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027 Dibuka', 'slug' => 'ppdb-2026-2027-dibuka', 'excerpt' => 'Pendaftaran siswa baru untuk tahun ajaran 2026/2027 resmi dibuka mulai 1 Juni 2026. Segera daftarkan putra-putri Anda!', 'content' => '<p>SMKS Muhammadiyah 2 Genteng dengan bangga mengumumkan pembukaan Penerimaan Peserta Didik Baru (PPDB) untuk Tahun Ajaran 2026/2027.</p><p>Pendaftaran dapat dilakukan secara online melalui website resmi sekolah atau datang langsung ke kantor sekretariat.</p><h3>Persyaratan Pendaftaran:</h3><ul><li>Ijazah/Surat Keterangan Lulus SMP/MTs</li><li>Raport SMP/MTs semester 1-5</li><li>Kartu Keluarga</li><li>Akta Kelahiran</li><li>Pas Foto 3x4 sebanyak 4 lembar</li></ul><p>Untuk informasi lebih lanjut, hubungi kami di (0333) 846292.</p>', 'category' => 'Pengumuman', 'image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800', 'is_published' => true, 'published_at' => now()->subDays(5)],
            ['title' => 'Workshop Coding dan Artificial Intelligence untuk Siswa', 'slug' => 'workshop-coding-ai-siswa', 'excerpt' => 'SMKS Muhammadiyah 2 Genteng mengadakan workshop intensif tentang pemrograman dan kecerdasan buatan bagi seluruh siswa program Animasi.', 'content' => '<p>Program Animasi Digital SMKS Muhammadiyah 2 Genteng menggelar Workshop Coding dan Artificial Intelligence selama 3 hari penuh pada tanggal 20-22 April 2026.</p><p>Workshop ini menghadirkan praktisi industri teknologi terkemuka dari berbagai perusahaan startup dan multinasional sebagai narasumber.</p>', 'category' => 'Kegiatan', 'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800', 'is_published' => true, 'published_at' => now()->subDays(10)],
            ['title' => 'Peringatan Hari Pendidikan Nasional 2026 di SMKS Muhammadiyah 2 Genteng', 'slug' => 'hardiknas-2026-smk-muhammadiyah-2', 'excerpt' => 'SMKS Muhammadiyah 2 Genteng merayakan Hari Pendidikan Nasional dengan serangkaian kegiatan inspiratif.', 'content' => '<p>Dalam rangka memperingati Hari Pendidikan Nasional 2026, SMKS Muhammadiyah 2 Genteng menyelenggarakan berbagai kegiatan...</p>', 'category' => 'Kegiatan', 'image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800', 'is_published' => true, 'published_at' => now()->subDays(15)],
            ];
            foreach ($news as $n) {
                News::create($n);
            }
        }

        if (!Gallery::query()->exists()) {
            $gallery = [
            ['title' => 'Laboratorium Komputer', 'category' => 'Fasilitas', 'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800', 'order' => 1],
            ['title' => 'Perpustakaan Modern', 'category' => 'Fasilitas', 'image' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=800', 'order' => 2],
            ['title' => 'Lapangan Olahraga', 'category' => 'Fasilitas', 'image' => 'https://images.unsplash.com/photo-1553778263-73a83bab9b0c?w=800', 'order' => 3],
            ['title' => 'Kegiatan Pramuka', 'category' => 'Kegiatan', 'image' => 'https://images.unsplash.com/photo-1526958977630-ea4025d7ac73?w=800', 'order' => 4],
            ['title' => 'Upacara Bendera', 'category' => 'Kegiatan', 'image' => 'https://images.unsplash.com/photo-1544717297-fa95b6ee9643?w=800', 'order' => 5],
            ['title' => 'Olimpiade Sains', 'category' => 'Prestasi', 'image' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=800', 'order' => 6],
            ['title' => 'Pentas Seni Budaya', 'category' => 'Kegiatan', 'image' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800', 'order' => 7],
            ['title' => 'Bengkel Otomotif', 'category' => 'Fasilitas', 'image' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=800', 'order' => 8],
            ];
            foreach ($gallery as $g) {
                Gallery::create(array_merge($g, ['is_active' => true]));
            }
        }

        // Call AgendaSeeder
        if (!\App\Models\Agenda::query()->exists()) {
            $this->call(AgendaSeeder::class);
        }
    }
}
