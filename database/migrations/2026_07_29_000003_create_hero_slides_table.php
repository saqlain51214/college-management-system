<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Formalizes the homepage hero slider, which previously lived only as a
 * hand-edited JSON blob on WebsitePage (or a hardcoded fallback in
 * hero.blade.php) — giving it its own table means a direct, dedicated
 * admin page instead of digging through a generic page-content editor.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('title', 255);
            $table->string('description', 500)->nullable();
            $table->string('primary_btn_text', 60)->nullable();
            $table->string('primary_btn_link', 100)->nullable();
            $table->string('secondary_btn_text', 60)->nullable();
            $table->string('secondary_btn_link', 100)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
