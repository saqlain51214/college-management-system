<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structure_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_structure_id')->constrained('fee_structures')->cascadeOnDelete();
            $table->decimal('old_amount', 10, 2);
            $table->decimal('new_amount', 10, 2);
            $table->date('effective_from');
            $table->string('reason');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structure_revisions');
    }
};
