<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admission_inquiries', function (Blueprint $table) {
            $table->string('semester', 10)->nullable()->after('entry_path');
            $table->string('program_type', 10)->nullable()->after('semester');
            $table->string('father_occupation')->nullable()->after('father_name');
            $table->string('father_phone', 20)->nullable()->after('father_occupation');
            $table->string('guardian_name')->nullable()->after('father_phone');
            $table->string('guardian_phone', 20)->nullable()->after('guardian_name');
            $table->string('district')->nullable()->after('city');
            $table->string('tehsil')->nullable()->after('district');
            $table->string('village')->nullable()->after('tehsil');
            $table->string('post_office')->nullable()->after('village');
        });
    }

    public function down(): void
    {
        Schema::table('admission_inquiries', function (Blueprint $table) {
            $table->dropColumn([
                'semester', 'program_type',
                'father_occupation', 'father_phone',
                'guardian_name', 'guardian_phone',
                'district', 'tehsil', 'village', 'post_office',
            ]);
        });
    }
};
