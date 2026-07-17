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
        Schema::table('timetables', function (Blueprint $table) {
            if (Schema::hasColumn('timetables', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            //
        });
    }
};
