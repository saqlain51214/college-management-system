<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('fee_payments', 'due_reminder_sent_at')) {
                $table->timestamp('due_reminder_sent_at')->nullable()->after('proof_claimed_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropColumn('due_reminder_sent_at');
        });
    }
};
