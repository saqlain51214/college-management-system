<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 200);
            $table->text('content');
            $table->string('audience', 30)->default('all');
            $table->string('priority', 20)->default('normal');
            $table->date('publish_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('send_email')->default(false);
            $table->boolean('send_sms')->default(false);
            $table->string('attachment', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['audience', 'is_published', 'expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
