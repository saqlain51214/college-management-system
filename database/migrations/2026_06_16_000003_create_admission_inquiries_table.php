<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admission_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->foreignId('program_id')->nullable()->constrained('academic_programs')->nullOnDelete();
            $table->string('qualification')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['new','contacted','enrolled','rejected'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_inquiries');
    }
};
