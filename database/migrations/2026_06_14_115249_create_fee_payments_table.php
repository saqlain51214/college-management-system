<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_structure_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->string('challan_number', 50)->unique();
            $table->string('fee_type', 30)->default('tuition');
            $table->unsignedTinyInteger('semester_number')->nullable();
            $table->decimal('amount_due', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('fine_amount', 8, 2)->default(0);
            $table->decimal('discount_amount', 8, 2)->default(0);
            $table->string('payment_status', 30)->default('pending');
            $table->string('payment_method', 30)->nullable();
            $table->date('due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['student_id', 'payment_status', 'fee_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
