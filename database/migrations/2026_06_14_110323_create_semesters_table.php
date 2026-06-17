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
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->string('name', 50);                     // e.g. Fall 2024
            $table->string('type', 10);                     // SemesterTypeEnum
            $table->tinyInteger('number')->default(1);      // 1 or 2 within year
            $table->date('start_date');
            $table->date('end_date');
            $table->date('registration_start')->nullable();
            $table->date('registration_end')->nullable();
            $table->date('exam_start')->nullable();
            $table->date('exam_end')->nullable();
            $table->boolean('is_current')->default(false);  // only one globally
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
