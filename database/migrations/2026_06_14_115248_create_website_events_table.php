<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->string('venue', 150)->nullable();
            $table->datetime('start_datetime');
            $table->datetime('end_datetime')->nullable();
            $table->string('organizer', 100)->nullable();
            $table->string('featured_image', 255)->nullable();
            $table->string('registration_link', 500)->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_published', 'start_datetime']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_events');
    }
};
