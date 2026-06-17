<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // System
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_program_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();

            // Enrollment identifiers
            $table->string('roll_number', 30)->unique();
            $table->string('registration_number', 50)->nullable()->unique();

            // Personal Info
            $table->string('name', 100);
            $table->string('name_urdu', 150)->nullable();
            $table->string('father_name', 100);
            $table->string('father_name_urdu', 150)->nullable();
            $table->string('mother_name', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('nationality', 50)->default('Pakistani');
            $table->string('domicile', 100)->nullable();

            // CNIC
            $table->string('cnic', 20)->nullable()->unique();
            $table->string('father_cnic', 20)->nullable();

            // Contact
            $table->string('email', 150)->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->string('father_phone', 20)->nullable();
            $table->string('guardian_name', 100)->nullable();
            $table->string('guardian_phone', 20)->nullable();
            $table->string('guardian_relation', 50)->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('city', 80)->nullable();
            $table->string('district', 80)->nullable();
            $table->string('province', 80)->nullable();
            $table->text('permanent_address')->nullable();

            // Photo
            $table->string('photo', 255)->nullable();

            // Academic Info
            $table->unsignedSmallInteger('batch_year')->nullable();
            $table->unsignedTinyInteger('current_semester')->default(1);
            $table->string('section', 10)->nullable();
            $table->date('admission_date')->nullable();
            $table->string('admission_type', 30)->default('regular');

            // Previous Education
            $table->string('previous_qualification', 100)->nullable();
            $table->decimal('previous_marks', 6, 2)->nullable();
            $table->string('previous_board', 100)->nullable();
            $table->unsignedSmallInteger('previous_year')->nullable();

            // Status & Flags
            $table->string('status', 30)->default('active');
            $table->string('disability', 100)->nullable();
            $table->boolean('is_hosteler')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['department_id', 'academic_program_id', 'status']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
