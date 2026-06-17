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
        Schema::create('academic_programs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('departments')
                  ->nullOnDelete();

            $table->string('name', 150);
            $table->string('short_name', 50)->nullable();
            $table->string('name_urdu', 200)->nullable();
            $table->string('slug', 150)->unique();
            $table->string('code', 30)->unique()->nullable();

            $table->string('degree_type', 20);

            $table->tinyInteger('duration_years')->default(4);
            $table->tinyInteger('total_semesters')->default(8);
            $table->smallInteger('total_credit_hours')->nullable();

            $table->text('description')->nullable();
            $table->text('eligibility')->nullable();
            $table->text('scope')->nullable();

            $table->string('banner_image')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_website')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_programs');
    }
};
