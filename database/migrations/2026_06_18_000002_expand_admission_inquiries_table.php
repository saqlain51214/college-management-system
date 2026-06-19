<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('admission_inquiries', function (Blueprint $table) {
            $table->string('reference_no')->nullable()->after('id')->unique();
            $table->string('entry_path')->nullable()->after('phone');
            $table->string('gender')->nullable()->after('entry_path');
            $table->string('campus')->nullable()->after('gender');
            $table->string('city')->nullable()->after('campus');
            $table->string('student_phone')->nullable()->after('email');
            $table->string('cnic')->nullable()->after('student_phone');
            $table->date('dob')->nullable()->after('cnic');
            $table->text('address')->nullable()->after('dob');
            $table->json('academic_details')->nullable()->after('qualification');
            $table->boolean('declare_true')->default(false)->after('academic_details');
        });
    }

    public function down(): void
    {
        Schema::table('admission_inquiries', function (Blueprint $table) {
            $table->dropColumn([
                'reference_no',
                'entry_path',
                'gender',
                'campus',
                'city',
                'student_phone',
                'cnic',
                'dob',
                'address',
                'academic_details',
                'declare_true',
            ]);
        });
    }
};
