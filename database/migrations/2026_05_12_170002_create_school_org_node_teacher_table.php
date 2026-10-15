<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_org_node_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_org_node_id')->constrained('school_org_nodes')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['school_org_node_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_org_node_teacher');
    }
};
