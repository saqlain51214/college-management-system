<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_outlines', function (Blueprint $table) {
            if (! Schema::hasColumn('course_outlines', 'file_paths')) {
                $table->json('file_paths')->nullable()->after('file_path');
            }
        });

        // Carry any existing single file over into the new multi-file column.
        DB::table('course_outlines')->whereNotNull('file_path')->orderBy('id')->each(function ($row) {
            DB::table('course_outlines')->where('id', $row->id)->update([
                'file_paths' => json_encode([$row->file_path]),
            ]);
        });

        Schema::table('course_outlines', function (Blueprint $table) {
            if (Schema::hasColumn('course_outlines', 'file_path')) {
                $table->dropColumn('file_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_outlines', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('title');
        });

        DB::table('course_outlines')->whereNotNull('file_paths')->orderBy('id')->each(function ($row) {
            $paths = json_decode($row->file_paths, true) ?: [];
            DB::table('course_outlines')->where('id', $row->id)->update([
                'file_path' => $paths[0] ?? null,
            ]);
        });

        Schema::table('course_outlines', function (Blueprint $table) {
            $table->dropColumn('file_paths');
        });
    }
};
