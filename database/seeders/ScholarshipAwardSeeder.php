<?php

namespace Database\Seeders;

use App\Enums\ScholarshipStatusEnum;
use App\Models\AcademicYear;
use App\Models\Scholarship;
use App\Models\ScholarshipAward;
use App\Models\Student;
use Illuminate\Database\Seeder;

class ScholarshipAwardSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::whereNotNull('roll_number')->get()->keyBy('roll_number');
        $scholarships = Scholarship::all()->keyBy('name');
        $year2024 = AcademicYear::where('name', '2024-2025')->value('id');
        $year2023 = AcademicYear::where('name', '2023-2024')->value('id');

        $merit   = $scholarships['Merit Scholarship — Top 5%']?->id;
        $hec     = $scholarships['HEC Need-Based Scholarship']?->id;
        $pm      = $scholarships['Prime Minister Youth Laptop Scheme']?->id;
        $sports  = $scholarships['Sports Excellence Award']?->id;
        $special = $scholarships['Special Persons Scholarship']?->id;
        $peef    = $scholarships['Punjab Education Endowment Fund (PEEF)']?->id;
        $excel   = $scholarships['College Academic Excellence Award']?->id;
        $orphan  = $scholarships['Orphan / Single Parent Scholarship']?->id;
        $teacher = $scholarships["Teacher's Children Scholarship"]?->id;

        $applied  = ScholarshipStatusEnum::Applied->value;
        $review   = ScholarshipStatusEnum::UnderReview->value;
        $approved = ScholarshipStatusEnum::Approved->value;
        $rejected = ScholarshipStatusEnum::Rejected->value;
        $disbursed= ScholarshipStatusEnum::Disbursed->value;

        $awards = [
            // Merit awards
            ['sch' => $merit,   'roll' => 'CS-2024-0002', 'year' => $year2024, 'status' => $approved, 'amount' => null, 'app_date' => '2024-10-05', 'appr_date' => '2024-10-20', 'disb_date' => null, 'reason' => '98% marks in matriculation — top 5% applicant.'],
            ['sch' => $merit,   'roll' => 'CS-2024-0006', 'year' => $year2024, 'status' => $approved, 'amount' => null, 'app_date' => '2024-10-05', 'appr_date' => '2024-10-20', 'disb_date' => null, 'reason' => '96.5% marks — top 5% batch 2024.'],
            ['sch' => $merit,   'roll' => 'CS-2022-0005', 'year' => $year2023, 'status' => $disbursed,'amount' => null, 'app_date' => '2023-10-01', 'appr_date' => '2023-10-15', 'disb_date' => '2023-11-01', 'reason' => 'CGPA 3.85 — ranked 1st in BS-CS Sem 3.'],

            // HEC Need-Based
            ['sch' => $hec,     'roll' => 'CS-2024-0005', 'year' => $year2024, 'status' => $approved, 'amount' => 30000, 'app_date' => '2024-09-10', 'appr_date' => '2024-10-30', 'disb_date' => null, 'reason' => 'Family income PKR 28,000/month. Single earning parent.'],
            ['sch' => $hec,     'roll' => 'CS-2023-0006', 'year' => $year2023, 'status' => $disbursed,'amount' => 30000, 'app_date' => '2023-09-12', 'appr_date' => '2023-10-25', 'disb_date' => '2023-11-15', 'reason' => 'Verified by HEC. Father disabled. Income PKR 18,000.'],
            ['sch' => $hec,     'roll' => 'EDU-2024-0004', 'year' => $year2024, 'status' => $review,  'amount' => null, 'app_date' => '2024-09-20', 'appr_date' => null, 'disb_date' => null, 'reason' => 'Under HEC verification. Income documents submitted.'],

            // PM Laptop Scheme
            ['sch' => $pm,      'roll' => 'CS-2024-0002', 'year' => $year2024, 'status' => $approved, 'amount' => null, 'app_date' => '2024-11-10', 'appr_date' => '2024-12-01', 'disb_date' => null, 'reason' => 'Selected by HEC based on academic merit.'],
            ['sch' => $pm,      'roll' => 'CS-2023-0005', 'year' => $year2023, 'status' => $disbursed,'amount' => null, 'app_date' => '2023-11-01', 'appr_date' => '2023-12-01', 'disb_date' => '2024-01-15', 'reason' => 'Laptop distributed January 2024 ceremony.'],

            // Sports Award
            ['sch' => $sports,  'roll' => 'CS-2023-0001', 'year' => $year2024, 'status' => $approved, 'amount' => 10000, 'app_date' => '2024-09-15', 'appr_date' => '2024-09-30', 'disb_date' => null, 'reason' => 'Represented college at Punjab-level cricket competition.'],
            ['sch' => $sports,  'roll' => 'CS-2024-0007', 'year' => $year2024, 'status' => $applied,  'amount' => null,  'app_date' => '2024-09-20', 'appr_date' => null, 'disb_date' => null, 'reason' => 'Applied — participated in district athletics.'],

            // PEEF
            ['sch' => $peef,    'roll' => 'EDU-2024-0002', 'year' => $year2024, 'status' => $approved, 'amount' => 25000, 'app_date' => '2024-10-05', 'appr_date' => '2024-11-10', 'disb_date' => null, 'reason' => 'PEEF-eligible. Income below threshold. Domicile Punjab.'],
            ['sch' => $peef,    'roll' => 'CS-2023-0006', 'year' => $year2024, 'status' => $applied,   'amount' => null,  'app_date' => '2024-10-08', 'appr_date' => null, 'disb_date' => null, 'reason' => 'Submitted income proof and domicile.'],

            // Academic Excellence
            ['sch' => $excel,   'roll' => 'CS-2023-0002', 'year' => $year2023, 'status' => $disbursed, 'amount' => null, 'app_date' => '2024-01-10', 'appr_date' => '2024-01-25', 'disb_date' => '2024-02-05', 'reason' => 'CGPA 3.82 — Semester 3 top scorer.'],
            ['sch' => $excel,   'roll' => 'CS-2022-0005', 'year' => $year2023, 'status' => $disbursed, 'amount' => null, 'app_date' => '2024-01-10', 'appr_date' => '2024-01-25', 'disb_date' => '2024-02-05', 'reason' => 'CGPA 3.90 — top in Semester 5.'],

            // Orphan Scholarship
            ['sch' => $orphan,  'roll' => 'EDU-2024-0004', 'year' => $year2024, 'status' => $review,   'amount' => null, 'app_date' => '2024-09-25', 'appr_date' => null, 'disb_date' => null, 'reason' => 'Death certificate of father submitted. Under verification.'],

            // Rejected
            ['sch' => $merit,   'roll' => 'CS-2024-0007', 'year' => $year2024, 'status' => $rejected,  'amount' => null, 'app_date' => '2024-10-05', 'appr_date' => '2024-10-20', 'disb_date' => null, 'reason' => null, 'remarks' => 'Marks below top 5% threshold.'],
            ['sch' => $hec,     'roll' => 'CS-2024-0001', 'year' => $year2024, 'status' => $rejected,  'amount' => null, 'app_date' => '2024-09-12', 'appr_date' => '2024-10-05', 'disb_date' => null, 'reason' => null, 'remarks' => 'Income above threshold. Application rejected.'],
        ];

        foreach ($awards as $a) {
            $student = $students[$a['roll']] ?? null;
            if (! $student || ! $a['sch']) {
                continue;
            }

            ScholarshipAward::updateOrCreate(
                [
                    'scholarship_id'  => $a['sch'],
                    'student_id'      => $student->id,
                    'academic_year_id'=> $a['year'],
                ],
                [
                    'scholarship_id'   => $a['sch'],
                    'student_id'       => $student->id,
                    'academic_year_id' => $a['year'],
                    'status'           => $a['status'],
                    'amount_awarded'   => $a['amount'],
                    'application_date' => $a['app_date'],
                    'approval_date'    => $a['appr_date'],
                    'disbursement_date'=> $a['disb_date'],
                    'reason'           => $a['reason'],
                    'remarks'          => $a['remarks'] ?? null,
                ]
            );
        }
    }
}
