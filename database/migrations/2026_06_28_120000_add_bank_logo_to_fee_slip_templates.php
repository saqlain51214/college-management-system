<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_slip_templates', function (Blueprint $table) {
            $table->string('bank_logo_path')->nullable()->after('bank_name');
        });
    }

    public function down(): void
    {
        Schema::table('fee_slip_templates', function (Blueprint $table) {
            $table->dropColumn('bank_logo_path');
        });
    }
};
