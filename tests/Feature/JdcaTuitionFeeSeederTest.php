<?php

namespace Tests\Feature;

use App\Models\AcademicProgram;
use App\Models\FeeStructure;
use Database\Seeders\JdcaProgramsSeeder;
use Database\Seeders\JdcaTuitionFeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * One real Tuition Fee structure per JDCA programme, Rs. 10,000/semester,
 * for the current academic year — fixes new admissions seeing "Rs. 0
 * available" because no Fee Structure existed for their programme at all.
 */
class JdcaTuitionFeeSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_one_tuition_fee_structure_per_real_programme(): void
    {
        $this->seed(JdcaProgramsSeeder::class);

        $this->seed(JdcaTuitionFeeSeeder::class);

        $programmeCount = count(JdcaProgramsSeeder::programmes());
        $this->assertSame($programmeCount, FeeStructure::where('fee_type', 'tuition')->count());

        $program = AcademicProgram::where('slug', 'associate-degree-in-computer-science')->first();
        $fee = FeeStructure::where('academic_program_id', $program->id)->first();

        $this->assertNotNull($fee);
        $this->assertSame(10000.0, (float) $fee->amount);
        $this->assertTrue($fee->is_mandatory);
        $this->assertTrue($fee->is_active);
        $this->assertSame('semester', $fee->frequency);
        $this->assertNull($fee->semester_number);
        $this->assertNull($fee->academic_year_id);
    }

    public function test_running_it_twice_does_not_create_duplicates(): void
    {
        $this->seed(JdcaProgramsSeeder::class);

        $this->seed(JdcaTuitionFeeSeeder::class);
        $this->seed(JdcaTuitionFeeSeeder::class);

        $programmeCount = count(JdcaProgramsSeeder::programmes());
        $this->assertSame($programmeCount, FeeStructure::where('fee_type', 'tuition')->count());
    }
}
