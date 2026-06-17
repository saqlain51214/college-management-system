<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_program_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 150);
            $table->string('exam_type', 30)->default('midterm');
            $table->unsignedTinyInteger('semester_number')->nullable();
            $table->date('exam_date')->nullable();
            $table->time('start_time')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->decimal('total_marks', 6, 2)->default(100);
            $table->decimal('passing_marks', 6, 2)->default(50);
            $table->decimal('weightage_percent', 5, 2)->nullable();
            $table->string('venue', 150)->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('results_published')->default(false);
            $table->text('instructions')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_id', 'exam_type']);
            $table->index(['academic_program_id', 'semester_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
