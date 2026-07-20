<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Remove the "Eidgah Astore" branch / "(EIDGAH ASTORE)" subtitle text that was
 * seeded onto the fee-slip templates and settings, so it no longer prints under
 * the logo on fee challans.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fee_slip_templates')) {
            DB::table('fee_slip_templates')
                ->where('bank_branch', 'like', '%idgah%')
                ->update(['bank_branch' => null]);

            DB::table('fee_slip_templates')
                ->where('college_subtitle', 'like', '%idgah%')
                ->update(['college_subtitle' => null]);
        }

        if (Schema::hasTable('college_settings')) {
            DB::table('college_settings')
                ->whereIn('key', ['fee_bank_branch', 'college_subtitle'])
                ->where('value', 'like', '%idgah%')
                ->update(['value' => '']);
        }
    }

    public function down(): void
    {
        // No-op.
    }
};
