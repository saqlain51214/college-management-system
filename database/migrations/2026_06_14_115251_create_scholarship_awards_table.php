<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 30)->default('applied');
            $table->decimal('amount_awarded', 10, 2)->nullable();
            $table->date('application_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('disbursement_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['student_id', 'status']);
            $table->index(['scholarship_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_awards');
    }
};
