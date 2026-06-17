<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lms_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('material_type', 50)->default('document');
            $table->string('file_path', 255)->nullable();
            $table->string('external_url', 500)->nullable();
            $table->unsignedTinyInteger('week_number')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_materials');
    }
};
