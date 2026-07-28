<?php

namespace Database\Seeders;

use App\Enums\ScholarshipStatusEnum;
use App\Models\AcademicProgram;
use App\Models\Department;
use App\Models\Scholarship;
use App\Models\ScholarshipAward;
use App\Models\Student;
use Illuminate\Database\Seeder;

/**
 * Creates 1-2 sample scholarships matching what's already shown on the public
 * website, plus one testing student with a scholarship assigned — requested
 * so the admin can see the full scholarship → discount → challan flow with
 * real data instead of only reading about it. Idempotent: safe to re-run.
 */
class DemoScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $merit = Scholarship::updateOrCreate(
            ['slug' => 'merit-scholarship'],
            [
                'name'                => 'Merit Scholarship',
                'scholarship_type'    => 'merit',
                'description'         => 'Awarded to students with outstanding academic performance in their previous qualification.',
                'eligibility_criteria'=> 'Minimum 80% marks in the last qualifying examination.',
                'coverage_percent'    => 20,
                'funding_source'      => 'College Fund',
                'seats'               => 10,
                'is_recurring'        => true,
                'is_active'           => true,
            ]
        );

        Scholarship::updateOrCreate(
            ['slug' => 'need-based-scholarship'],
            [
                'name'                => 'Need-Based Scholarship',
                'scholarship_type'    => 'need_based',
                'description'         => 'Financial assistance for students from low-income households.',
                'eligibility_criteria'=> 'Verified household income below the district poverty line.',
                'amount'              => 5000,
                'funding_source'      => 'College Fund',
                'seats'               => 15,
                'is_recurring'        => true,
                'is_active'           => true,
            ]
        );

        $department = Department::active()->first();
        $program    = AcademicProgram::active()->when($department, fn ($q) => $q->where('department_id', $department->id))->first();

        $student = Student::updateOrCreate(
            ['email' => 'srlaravel+010@gmail.com'],
            [
                'name'                => 'Test Student (Scholarship Demo)',
                'father_name'         => 'Demo Father',
                'gender'              => 'male',
                'roll_number'         => $department ? strtoupper($department->code) . '-DEMO-0010' : 'DEMO-0010',
                'department_id'       => $department?->id,
                'academic_program_id' => $program?->id,
                'current_semester'    => 1,
                'is_active'           => true,
            ]
        );

        if (! $student->scholarshipAwards()->where('scholarship_id', $merit->id)->exists()) {
            ScholarshipAward::create([
                'scholarship_id'    => $merit->id,
                'student_id'        => $student->id,
                'status'            => ScholarshipStatusEnum::Approved,
                'amount_awarded'    => $merit->coverage_percent,
                'application_date'  => now()->toDateString(),
                'approval_date'     => now()->toDateString(),
                'reason'            => 'Demo data — seeded for testing the scholarship/discount flow.',
            ]);
        }

        $this->command?->info("Demo scholarships seeded. Test student: {$student->email} / roll {$student->roll_number} / password 123456 (portal login).");
    }
}
