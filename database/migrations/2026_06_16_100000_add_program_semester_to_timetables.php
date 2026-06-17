<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            if (!Schema::hasColumn('timetables', 'academic_program_id')) {
                $table->unsignedBigInteger('academic_program_id')->nullable()->after('id');
                $table->foreign('academic_program_id')
                      ->references('id')->on('academic_programs')
                      ->nullOnDelete();
            }
            if (!Schema::hasColumn('timetables', 'semester')) {
                $table->unsignedTinyInteger('semester')->nullable()->after('academic_program_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            if (Schema::hasColumn('timetables', 'semester')) {
                $table->dropColumn('semester');
            }
            if (Schema::hasColumn('timetables', 'academic_program_id')) {
                $table->dropForeign(['academic_program_id']);
                $table->dropColumn('academic_program_id');
            }
        });
    }
};
