<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wakas', function (Blueprint $table) {
            $table->string('photo_name')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('wakas', function (Blueprint $table) {
            $table->dropColumn('photo_name');
        });
    }
};
