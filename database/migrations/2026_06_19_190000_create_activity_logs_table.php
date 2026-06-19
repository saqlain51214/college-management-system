<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type')->index();
            $table->unsignedTinyInteger('level')->index();
            $table->string('event', 80)->index();
            $table->string('actor_type', 40)->nullable()->index();
            $table->unsignedBigInteger('actor_id')->nullable()->index();
            $table->string('subject_type', 40)->nullable()->index();
            $table->unsignedBigInteger('subject_id')->nullable()->index();
            $table->string('message', 255)->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();

            $table->index(['type', 'level', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
            $table->index(['actor_type', 'actor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
