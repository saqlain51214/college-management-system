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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->string('name_urdu')->nullable();
            $table->string('slug')->unique();
            $table->string('code')->unique()->nullable();
            $table->string('type')->default('academic');

            // Head of Department
            $table->string('hod_name')->nullable();
            $table->string('hod_designation')->nullable();
            $table->string('hod_photo')->nullable();
            $table->text('hod_message')->nullable();

            // Content
            $table->text('description')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('banner_image')->nullable();

            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // Display
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_website')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
