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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                  ->nullable()->constrained('departments')->nullOnDelete();

            $table->foreignId('academic_program_id')
                  ->nullable()->constrained('academic_programs')->nullOnDelete();

            $table->string('name', 150);
            $table->string('name_urdu', 200)->nullable();
            $table->string('code', 30)->unique();          // e.g. CS-101
            $table->string('slug', 150)->unique();

            $table->string('course_type', 20);             // CourseTypeEnum
            $table->tinyInteger('semester_number')->nullable();  // 1-8

            $table->decimal('credit_hours', 4, 1)->default(3);
            $table->decimal('theory_hours', 4, 1)->nullable();
            $table->decimal('lab_hours', 4, 1)->nullable();
            $table->tinyInteger('contact_hours_per_week')->nullable();

            $table->text('description')->nullable();
            $table->text('objectives')->nullable();
            $table->text('outcomes')->nullable();          // CLOs
            $table->text('pre_requisites')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_website')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
