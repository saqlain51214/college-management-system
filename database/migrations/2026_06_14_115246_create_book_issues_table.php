<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->decimal('fine_amount', 8, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->string('condition_on_issue', 50)->default('good');
            $table->string('condition_on_return', 50)->nullable();
            $table->string('issued_by', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index(['book_id', 'return_date']);
            $table->index(['student_id', 'return_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_issues');
    }
};
