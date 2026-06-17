<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->longText('content')->nullable();
            $table->string('meta_title', 200)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('featured_image', 255)->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('in_menu')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_pages');
    }
};
