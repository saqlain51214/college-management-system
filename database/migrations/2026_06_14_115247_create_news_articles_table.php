<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 250);
            $table->string('slug', 250)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('category', 50)->default('news');
            $table->string('featured_image', 255)->nullable();
            $table->date('published_date')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_published', 'category', 'published_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
