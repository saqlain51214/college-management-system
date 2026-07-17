<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admission_inquiries', function (Blueprint $table) {
            $table->string('program_name')->nullable()->after('program_id');
        });
    }

    public function down(): void
    {
        Schema::table('admission_inquiries', function (Blueprint $table) {
            $table->dropColumn('program_name');
        });
    }
};
