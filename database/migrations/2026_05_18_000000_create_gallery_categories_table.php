<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('gallery_categories')->insert([
            'name' => 'Umum',
            'slug' => 'umum',
            'order' => 0,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if (!Schema::hasTable('gallery')) {
            return;
        }

        $existing = DB::table('gallery')
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter(fn ($c) => is_string($c) && trim($c) !== '')
            ->map(fn ($c) => trim($c))
            ->unique()
            ->values();

        $order = 1;
        foreach ($existing as $name) {
            if (mb_strtolower($name) === 'umum') {
                continue;
            }

            $base = Str::slug($name);
            $slug = $base !== '' ? $base : 'kategori';
            $suffix = 1;
            while (DB::table('gallery_categories')->where('slug', $slug)->exists()) {
                $slug = $base . '-' . $suffix;
                $suffix++;
            }

            DB::table('gallery_categories')->insert([
                'name' => $name,
                'slug' => $slug,
                'order' => $order,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $order++;
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_categories');
    }
};

