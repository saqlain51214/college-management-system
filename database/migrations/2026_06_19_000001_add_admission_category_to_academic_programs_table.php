<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_programs', function (Blueprint $table) {
            $table->string('admission_category', 20)
                ->default('other')
                ->after('degree_type');
        });

        DB::table('academic_programs')
            ->where(function ($query) {
                $query->where('name', 'like', '%FA%')
                    ->orWhere('name', 'like', '%FSc%')
                    ->orWhere('name', 'like', '%ICS%')
                    ->orWhere('name', 'like', '%I.Com%')
                    ->orWhere('name', 'like', '%ICom%')
                    ->orWhere('name', 'like', '%Intermediate%')
                    ->orWhere('short_name', 'like', '%FA%')
                    ->orWhere('short_name', 'like', '%FSc%')
                    ->orWhere('short_name', 'like', '%ICS%')
                    ->orWhere('short_name', 'like', '%I.Com%')
                    ->orWhere('short_name', 'like', '%ICom%')
                    ->orWhere('short_name', 'like', '%Intermediate%')
                    ->orWhere('code', 'like', '%FA%')
                    ->orWhere('code', 'like', '%FSC%')
                    ->orWhere('code', 'like', '%ICS%')
                    ->orWhere('code', 'like', '%ICOM%')
                    ->orWhere('code', 'like', '%INT%');
            })
            ->update(['admission_category' => 'intermediate']);

        DB::table('academic_programs')
            ->where('admission_category', 'other')
            ->whereIn('degree_type', ['bs', 'bed'])
            ->update(['admission_category' => 'undergraduate']);
    }

    public function down(): void
    {
        Schema::table('academic_programs', function (Blueprint $table) {
            $table->dropColumn('admission_category');
        });
    }
};
