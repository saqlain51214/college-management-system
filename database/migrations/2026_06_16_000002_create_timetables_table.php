<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('timetables')) {
            return;
        }
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_program_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('semester');
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('day_of_week', ['monday','tuesday','wednesday','thursday','friday','saturday','sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
