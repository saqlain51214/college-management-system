<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Splits the single `discount_amount` bucket into two tracked sub-amounts —
 * `manual_discount_amount` (admin-applied, via "Apply Discount") and
 * `scholarship_discount_amount` (from the student's active scholarship) —
 * so admin/portal/PDF can show a real "Original Fee - Scholarship - Discount
 * = Final Payable" breakdown instead of one undifferentiated discount line.
 * `discount_amount` itself is kept as the authoritative total (now derived as
 * manual + scholarship on every save) so nothing that reads it — net_amount,
 * balance, existing reports — changes behavior for a single existing row.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('fee_payments', 'manual_discount_amount')) {
                $table->decimal('manual_discount_amount', 10, 2)->default(0)->after('discount_amount');
            }
            if (! Schema::hasColumn('fee_payments', 'scholarship_discount_amount')) {
                $table->decimal('scholarship_discount_amount', 10, 2)->default(0)->after('manual_discount_amount');
            }
            if (! Schema::hasColumn('fee_payments', 'original_fee_amount')) {
                $table->decimal('original_fee_amount', 10, 2)->nullable()->after('scholarship_discount_amount');
            }
            if (! Schema::hasColumn('fee_payments', 'scholarship_name')) {
                $table->string('scholarship_name', 150)->nullable()->after('original_fee_amount');
            }
            if (! Schema::hasColumn('fee_payments', 'scholarship_percent')) {
                $table->decimal('scholarship_percent', 5, 2)->nullable()->after('scholarship_name');
            }
            if (! Schema::hasColumn('fee_payments', 'scholarship_baked_into_amount_due')) {
                // true  → amount_due was already reduced for scholarship at creation time
                //         (generateSlip()), so scholarship_discount_amount must NOT also be
                //         subtracted via discount_amount (that would double-count it).
                // false → amount_due predates the scholarship (legacy/reconciled challans),
                //         so scholarship_discount_amount is subtracted via discount_amount,
                //         same as the original reconcileScholarship() behavior.
                $table->boolean('scholarship_baked_into_amount_due')->default(false)->after('scholarship_percent');
            }
        });

        // Preserve existing totals exactly: whatever discount_amount already
        // held is a manual discount as far as we know (scholarship tracking
        // didn't exist before), so the derived total (manual + scholarship)
        // stays identical to today's discount_amount for every existing row.
        DB::table('fee_payments')->update(['manual_discount_amount' => DB::raw('discount_amount')]);
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropColumn(['manual_discount_amount', 'scholarship_discount_amount', 'original_fee_amount', 'scholarship_name', 'scholarship_percent']);
        });
    }
};
