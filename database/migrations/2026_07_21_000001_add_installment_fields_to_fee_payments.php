<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the columns needed for flexible, self-chosen-amount fee slips:
 *   - installment_no: which installment this challan is, within the group of
 *     challans for the same student/fee_type/semester/academic_year.
 *   - late_fine_per_day: a snapshot of the applicable late-fee rate at the
 *     time the challan was created, so the overdue cron works even when the
 *     challan has no fee_structure_id link.
 *   - proof_claimed_amount / proof_claimed_date: what the student says they
 *     paid and when, captured alongside their proof upload. The challan is
 *     only marked Paid after an admin reviews and confirms this claim.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('fee_payments', 'installment_no')) {
                $table->unsignedTinyInteger('installment_no')->default(1)->after('semester_number');
            }
            if (! Schema::hasColumn('fee_payments', 'late_fine_per_day')) {
                $table->decimal('late_fine_per_day', 8, 2)->nullable()->after('fine_amount');
            }
            if (! Schema::hasColumn('fee_payments', 'proof_claimed_amount')) {
                $table->decimal('proof_claimed_amount', 10, 2)->nullable()->after('proof_uploaded_at');
            }
            if (! Schema::hasColumn('fee_payments', 'proof_claimed_date')) {
                $table->date('proof_claimed_date')->nullable()->after('proof_claimed_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropColumn(['installment_no', 'late_fine_per_day', 'proof_claimed_amount', 'proof_claimed_date']);
        });
    }
};
