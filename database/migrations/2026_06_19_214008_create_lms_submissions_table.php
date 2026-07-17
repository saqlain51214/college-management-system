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
        Schema::create('lms_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('lms_assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->enum('status', ['pending', 'submitted', 'late', 'graded'])->default('pending');
            $table->string('file_path', 500)->nullable();
            $table->longText('text_content')->nullable();
            $table->string('submitted_url', 500)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('marks_obtained', 6, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('teachers')->nullOnDelete();
            $table->timestamps();

            $table->unique(['assignment_id', 'student_id']);
            $table->index(['student_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_submissions');
    }
};
