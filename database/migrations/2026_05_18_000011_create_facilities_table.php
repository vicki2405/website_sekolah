<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('category')->default('Umum');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('facilities')->insert([
            [
                'title' => 'Laboratorium Komputer',
                'description' => 'Dilengkapi dengan komputer spesifikasi tinggi untuk menunjang desain grafis dan pemrograman.',
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80&w=800',
                'icon' => 'monitor-play',
                'category' => 'Laboratorium',
                'order' => 0,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Workshop Otomotif',
                'description' => 'Bengkel standar industri dengan peralatan lengkap untuk praktek TKR dan TBSM.',
                'image' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&q=80&w=800',
                'icon' => 'wrench',
                'category' => 'Bengkel',
                'order' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Dapur Standar Hotel',
                'description' => 'Fasilitas memasak profesional untuk menunjang kreativitas siswa jurusan Kuliner.',
                'image' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&q=80&w=800',
                'icon' => 'chef-hat',
                'category' => 'Kuliner',
                'order' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Perpustakaan Digital',
                'description' => 'Koleksi buku lengkap dan akses jurnal digital untuk mendukung literasi siswa.',
                'image' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&q=80&w=800',
                'icon' => 'book-open',
                'category' => 'Perpustakaan',
                'order' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Lapangan Olahraga',
                'description' => 'Fasilitas olahraga outdoor untuk basket, voli, dan futsal.',
                'image' => 'https://images.unsplash.com/photo-1544919982-b61976f0ba43?auto=format&fit=crop&q=80&w=800',
                'icon' => 'dribbble',
                'category' => 'Olahraga',
                'order' => 4,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Masjid Sekolah',
                'description' => 'Pusat kegiatan keagamaan dan pembinaan karakter religius siswa.',
                'image' => 'https://images.unsplash.com/photo-1590076247866-963e6e87f34c?auto=format&fit=crop&q=80&w=800',
                'icon' => 'book-heart',
                'category' => 'Keagamaan',
                'order' => 5,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};

