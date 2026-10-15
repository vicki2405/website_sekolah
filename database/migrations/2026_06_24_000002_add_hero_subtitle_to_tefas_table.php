<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tefas', function (Blueprint $table) {
            $table->string('hero_subtitle')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('tefas', function (Blueprint $table) {
            $table->dropColumn('hero_subtitle');
        });
    }
};
