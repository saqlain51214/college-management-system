<?php

namespace Database\Seeders;

use App\Enums\ScholarshipTypeEnum;
use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $scholarships = [
            [
                'name' => 'Prime Minister Youth Laptop Scheme',
                'type' => ScholarshipTypeEnum::Government->value,
                'source' => 'Federal Government',
                'amount' => null,
                'coverage' => null,
                'seats' => 5,
                'recurring' => false,
                'app_start' => '2024-11-01',
                'app_end' => '2024-12-31',
                'desc' => 'Merit-based laptop distribution for top-performing students selected by HEC.',
                'eligibility' => 'Minimum 70% marks in previous qualification. Currently enrolled in BS/BEd program.',
            ],
            [
                'name' => 'HEC Need-Based Scholarship',
                'type' => ScholarshipTypeEnum::NeedBased->value,
                'source' => 'HEC Pakistan',
                'amount' => 30000,
                'coverage' => null,
                'seats' => 10,
                'recurring' => true,
                'app_start' => '2024-09-01',
                'app_end' => '2024-10-15',
                'desc' => 'For financially deserving students with minimum 60% marks.',
                'eligibility' => 'Family income below PKR 45,000/month. Minimum 60% marks. Must maintain 2.5 CGPA.',
            ],
            [
                'name' => 'Merit Scholarship — Top 5%',
                'type' => ScholarshipTypeEnum::Merit->value,
                'source' => 'College',
                'amount' => null,
                'coverage' => 50,
                'seats' => 8,
                'recurring' => true,
                'app_start' => '2024-10-01',
                'app_end' => '2024-11-30',
                'desc' => 'Students securing top 5% marks in their program get 50% tuition fee waiver.',
                'eligibility' => 'Top 5% ranking in department. Minimum 3.5 CGPA. No back or repeat papers.',
            ],
            [
                'name' => 'Sports Excellence Award',
                'type' => ScholarshipTypeEnum::Sports->value,
                'source' => 'College Sports Fund',
                'amount' => 10000,
                'coverage' => null,
                'seats' => 4,
                'recurring' => false,
                'app_start' => '2024-09-01',
                'app_end' => '2024-09-30',
                'desc' => 'For students who represent the college at district, provincial or national level sports.',
                'eligibility' => 'Active sports participation. Represented college in official competitions. Minimum 2.0 CGPA.',
            ],
            [
                'name' => 'Special Persons Scholarship',
                'type' => ScholarshipTypeEnum::Disability->value,
                'source' => 'College Welfare Fund',
                'amount' => null,
                'coverage' => 100,
                'seats' => 3,
                'recurring' => true,
                'app_start' => '2024-09-01',
                'app_end' => '2024-10-31',
                'desc' => 'Full fee waiver for students with registered disabilities per NADRA/NCRC.',
                'eligibility' => 'Registered disability certificate from NCRC or NADRA. Any academic standing.',
            ],
            [
                'name' => 'Punjab Education Endowment Fund (PEEF)',
                'type' => ScholarshipTypeEnum::Government->value,
                'source' => 'Government of Punjab',
                'amount' => 25000,
                'coverage' => null,
                'seats' => 15,
                'recurring' => true,
                'app_start' => '2024-10-01',
                'app_end' => '2024-11-15',
                'desc' => 'Annual stipend for deserving students from underprivileged backgrounds in Punjab.',
                'eligibility' => 'Domicile of Punjab. Family income below PKR 30,000/month. Minimum 60% marks.',
            ],
            [
                'name' => 'College Academic Excellence Award',
                'type' => ScholarshipTypeEnum::Institutional->value,
                'source' => 'College',
                'amount' => null,
                'coverage' => 25,
                'seats' => 12,
                'recurring' => true,
                'app_start' => '2025-01-01',
                'app_end' => '2025-02-28',
                'desc' => 'Quarterly award for students with outstanding semester performance.',
                'eligibility' => 'Minimum 3.7 CGPA in most recent semester. No disciplinary record.',
            ],
            [
                'name' => 'Orphan / Single Parent Scholarship',
                'type' => ScholarshipTypeEnum::NeedBased->value,
                'source' => 'College Welfare Fund',
                'amount' => null,
                'coverage' => 75,
                'seats' => 5,
                'recurring' => true,
                'app_start' => '2024-09-01',
                'app_end' => '2024-10-31',
                'desc' => '75% fee concession for students who are orphans or from single-parent households.',
                'eligibility' => 'Death or disability certificate of parent. Household income below PKR 20,000.',
            ],
            [
                'name' => 'Teacher\'s Children Scholarship',
                'type' => ScholarshipTypeEnum::Institutional->value,
                'source' => 'College',
                'amount' => null,
                'coverage' => 50,
                'seats' => null,
                'recurring' => true,
                'app_start' => null,
                'app_end' => null,
                'desc' => '50% tuition fee waiver for children of college faculty and administrative staff.',
                'eligibility' => 'Parent must be a current full-time employee of the college. Academic standing: any.',
            ],
            [
                'name' => 'Daanish School Alumni Scholarship',
                'type' => ScholarshipTypeEnum::Government->value,
                'source' => 'Punjab Education Foundation',
                'amount' => 20000,
                'coverage' => null,
                'seats' => 6,
                'recurring' => true,
                'app_start' => '2024-09-15',
                'app_end' => '2024-10-15',
                'desc' => 'Scholarship for graduates from Punjab Daanish Schools to pursue higher education.',
                'eligibility' => 'Must have completed secondary education from a Daanish School. Minimum 65% marks.',
            ],
        ];

        foreach ($scholarships as $s) {
            Scholarship::firstOrCreate(
                ['name' => $s['name']],
                [
                    'slug'                => Str::slug($s['name']),
                    'scholarship_type'    => $s['type'],
                    'description'         => $s['desc'],
                    'eligibility_criteria'=> $s['eligibility'] ?? null,
                    'funding_source'      => $s['source'],
                    'amount'              => $s['amount'],
                    'coverage_percent'    => $s['coverage'],
                    'seats'               => $s['seats'],
                    'application_start'   => $s['app_start'],
                    'application_end'     => $s['app_end'],
                    'is_recurring'        => $s['recurring'],
                    'is_active'           => true,
                ]
            );
        }
    }
}
