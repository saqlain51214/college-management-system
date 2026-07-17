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
        Schema::create('lms_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->foreignId('assignment_id')->nullable()->constrained('lms_assignments')->nullOnDelete();
            $table->foreignId('submission_id')->nullable()->constrained('lms_submissions')->nullOnDelete();
            $table->enum('type', ['grade_dispute', 'deadline_extension', 'assignment_question', 'general'])->default('general');
            $table->string('subject', 200);
            $table->text('message');
            $table->enum('status', ['open', 'replied', 'resolved'])->default('open');
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('teachers')->nullOnDelete();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['teacher_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_queries');
    }
};
