<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->decimal('marks_obtained', 6, 2)->nullable();
            $table->string('grade', 5)->nullable();
            $table->decimal('grade_points', 4, 2)->nullable();
            $table->boolean('is_absent')->default(false);
            $table->boolean('is_exempted')->default(false);
            $table->string('remarks', 200)->nullable();
            $table->foreignId('entered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['exam_id', 'student_id']);
            $table->index(['student_id', 'grade']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
