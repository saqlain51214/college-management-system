<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('position');
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30);
            $table->string('education', 200);
            $table->string('experience', 200)->nullable();
            $table->text('message');
            $table->string('cv_path')->nullable();
            $table->string('status', 20)->default('new'); // new, reviewed, shortlisted, rejected, hired
            $table->boolean('is_read')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
