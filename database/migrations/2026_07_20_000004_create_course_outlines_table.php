<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Department- and semester-wise course outlines (PDF or link), shown on the
 * public site (Academics → Course Outlines and each department page) and
 * managed from the admin panel.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_outlines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_program_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('semester_number')->nullable();
            $table->string('title');
            $table->string('file_path')->nullable();     // uploaded PDF
            $table->string('external_url')->nullable();   // or a link
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['department_id', 'semester_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_outlines');
    }
};
