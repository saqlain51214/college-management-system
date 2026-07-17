<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lms_assignments', function (Blueprint $table) {
            $table->string('reference_url', 500)->nullable()->after('attachment');
        });
    }

    public function down(): void
    {
        Schema::table('lms_assignments', function (Blueprint $table) {
            $table->dropColumn('reference_url');
        });
    }
};
