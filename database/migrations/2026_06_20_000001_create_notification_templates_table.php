<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key', 80)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('channel', ['mail', 'database', 'both'])->default('both');
            $table->string('subject');
            $table->longText('body');
            $table->string('action_label', 80)->nullable();
            $table->string('action_url')->nullable();
            $table->string('in_app_icon', 50)->default('bell');
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
