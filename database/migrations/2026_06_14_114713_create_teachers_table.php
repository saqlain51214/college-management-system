<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            // System
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();

            // Identity
            $table->string('employee_id', 30)->unique();
            $table->string('name', 100);
            $table->string('name_urdu', 150)->nullable();
            $table->string('father_name', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('nationality', 50)->default('Pakistani');

            // CNIC
            $table->string('cnic', 20)->nullable()->unique();

            // Contact
            $table->string('email', 150)->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->string('alternative_phone', 20)->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('city', 80)->nullable();
            $table->string('province', 80)->nullable();

            // Photo
            $table->string('photo', 255)->nullable();

            // Academic Qualification
            $table->string('highest_qualification', 100)->nullable();
            $table->string('specialization', 150)->nullable();
            $table->string('qualification_institution', 200)->nullable();
            $table->unsignedSmallInteger('qualification_year')->nullable();

            // Employment
            $table->string('designation', 100)->nullable();
            $table->string('employment_type', 30)->default('permanent');
            $table->unsignedSmallInteger('experience_years')->default(0);
            $table->date('joining_date')->nullable();
            $table->date('leaving_date')->nullable();

            // Salary
            $table->string('salary_grade', 20)->nullable();
            $table->decimal('basic_salary', 10, 2)->nullable();

            // Status
            $table->string('status', 30)->default('active');
            $table->boolean('is_active')->default(true);
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['department_id', 'status', 'is_active']);
            $table->index('employment_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
