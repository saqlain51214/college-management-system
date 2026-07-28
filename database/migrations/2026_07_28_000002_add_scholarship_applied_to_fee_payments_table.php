<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tracks, per challan, whether the student's current scholarship has actually
 * been reflected in the amount — previously there was no way to tell "this
 * amount is already scholarship-adjusted" from "this student has a
 * scholarship but this particular challan predates it and was never fixed."
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('fee_payments', 'scholarship_applied')) {
                $table->boolean('scholarship_applied')->default(false)->after('discount_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropColumn('scholarship_applied');
        });
    }
};
