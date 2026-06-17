<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('accession_number', 30)->unique();
            $table->string('isbn', 30)->nullable();
            $table->string('title', 250);
            $table->string('author', 200)->nullable();
            $table->string('publisher', 150)->nullable();
            $table->unsignedSmallInteger('publication_year')->nullable();
            $table->string('edition', 30)->nullable();
            $table->string('language', 50)->default('English');
            $table->unsignedSmallInteger('total_copies')->default(1);
            $table->unsignedSmallInteger('available_copies')->default(1);
            $table->string('rack_location', 50)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('subject', 150)->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('status', 30)->default('available');
            $table->string('cover_image', 255)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_reference_only')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['department_id', 'status']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
