<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_program_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 150);
            $table->string('fee_type', 30)->default('tuition');
            $table->unsignedTinyInteger('semester_number')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('late_fine_per_day', 8, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->string('frequency', 30)->default('semester');
            $table->boolean('is_mandatory')->default(true);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['academic_program_id', 'academic_year_id', 'fee_type'], 'fee_structures_prog_year_type_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
