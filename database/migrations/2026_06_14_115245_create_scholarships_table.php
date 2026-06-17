<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('scholarship_type', 30)->default('merit');
            $table->text('description')->nullable();
            $table->text('eligibility_criteria')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('coverage_percent', 5, 2)->nullable();
            $table->string('funding_source', 100)->nullable();
            $table->unsignedSmallInteger('seats')->nullable();
            $table->date('application_start')->nullable();
            $table->date('application_end')->nullable();
            $table->boolean('is_recurring')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
