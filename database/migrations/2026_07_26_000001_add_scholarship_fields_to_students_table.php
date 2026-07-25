<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'scholarship_type')) {
                $table->string('scholarship_type', 20)->nullable()->after('previous_year');
            }
            if (! Schema::hasColumn('students', 'scholarship_value')) {
                $table->decimal('scholarship_value', 10, 2)->nullable()->after('scholarship_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['scholarship_type', 'scholarship_value']);
        });
    }
};
