<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Formalizes the "Current Openings" list on the public Jobs page, which
 * previously lived only as a hardcoded PHP array inside jobs.blade.php —
 * giving it its own table lets admins add/edit/close vacancies without a
 * code deploy.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('employment_type', 30)->default('full_time');
            $table->string('department', 150);
            $table->text('qualification');
            $table->text('description')->nullable();
            $table->date('closing_date')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
