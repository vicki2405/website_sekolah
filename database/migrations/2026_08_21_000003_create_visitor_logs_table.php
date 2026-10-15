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
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('url', 500)->nullable();
            $table->string('page_title', 255)->nullable();
            $table->string('method', 10)->default('GET');
            $table->string('device', 30)->default('Desktop')->index(); // Mobile, Desktop, Tablet, Bot
            $table->string('browser', 50)->nullable()->index(); // Chrome, Safari, Firefox, Edge, etc.
            $table->string('platform', 50)->nullable()->index(); // Windows, Android, iOS, MacOS, Linux, etc.
            $table->text('user_agent')->nullable();
            $table->string('referer', 500)->nullable();
            $table->string('session_id', 100)->nullable()->index();
            $table->timestamps();

            // Composite index for fast daily queries
            $table->index(['created_at', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
