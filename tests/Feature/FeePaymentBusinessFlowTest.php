<?php

namespace Tests\Feature;

use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\FeePayment;
use App\Models\Student;
use Database\Seeders\JdcaProgramsSeeder;
use Database\Seeders\JdcaTuitionFeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * End-to-end walk of the real admin flow: create a student, generate a fee
 * challan, and check the amount that lands on it — for a plain student, a
 * percentage-scholarship student, and a flat-discount student. Also proves
 * the bug an admin actually hit: a Tuition Fee Structure existed for the
 * student's program, but the challan form still said "No active Fee
 * Structure exists" because the structure was pinned to one academic year
 * and the challan was for a different one.
 */
class FeePaymentBusinessFlowTest extends TestCase
{
    use RefreshDatabase;

    protected AcademicProgram $program;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(JdcaProgramsSeeder::class);
        $this->seed(JdcaTuitionFeeSeeder::class);

        $this->program = AcademicProgram::where('slug', 'associate-degree-in-computer-science')->first();
    }

    public function test_a_year_agnostic_fee_structure_matches_every_academic_year_not_just_one(): void
    {
        $oldYear = AcademicYear::updateOrCreate(['name' => '2024-2025'], ['start_date' => '2024-09-01', 'end_date' => '2025-08-31', 'is_active' => true]);
        $newYear = AcademicYear::updateOrCreate(['name' => '2026-2027'], ['start_date' => '2026-09-01', 'end_date' => '2027-08-31', 'is_active' => true]);

        $student = Student::factory()->create(['academic_program_id' => $this->program->id]);

        // Regression: this used to fail with "No active Fee Structure exists"
        // whenever the challan's academic year didn't match the one the
        // seeder happened to pin the structure to.
        foreach ([$oldYear, $newYear] as $year) {
            $summary = FeePayment::invoiceSummary($student, 'tuition', null, $year->id);
            $this->assertTrue($summary['has_fee_structure'], "Fee Structure should match academic year {$year->name}.");
            $this->assertSame(10000.0, $summary['available']);
        }
    }

    public function test_a_plain_student_challan_amount_matches_the_fee_structure_exactly(): void
    {
        $student = Student::factory()->create(['academic_program_id' => $this->program->id]);

        $slip = FeePayment::generateSlip($student, [
            'fee_type' => 'tuition',
            'amount'   => 10000,
        ]);

        $this->assertSame(10000.0, (float) $slip->amount_due);
        $this->assertSame(10000.0, $slip->net_amount);
        $this->assertSame(0.0, (float) $slip->discount_amount);
        $this->assertFalse($slip->scholarship_applied);
    }

    public function test_a_percentage_scholarship_student_challan_reflects_the_discount(): void
    {
        $student = Student::factory()->create([
            'academic_program_id' => $this->program->id,
            'scholarship_type'    => 'percentage',
            'scholarship_value'   => 50,
        ]);

        // The form suggests this same amount via suggestedAmount()/invoiceSummary() —
        // already net of the scholarship — mirroring what an admin would actually submit.
        $summary = FeePayment::invoiceSummary($student, 'tuition', null, null);
        $this->assertSame(5000.0, $summary['available']);

        $slip = FeePayment::generateSlip($student, [
            'fee_type' => 'tuition',
            'amount'   => $summary['available'],
        ]);

        $this->assertSame(5000.0, (float) $slip->amount_due);
        $this->assertSame(5000.0, $slip->net_amount, 'Scholarship is baked into amount_due, so net_amount must not double-discount it.');
        $this->assertSame(10000.0, (float) $slip->original_fee_amount);
        $this->assertSame(5000.0, (float) $slip->scholarship_discount_amount);
        $this->assertSame(50.0, (float) $slip->scholarship_percent);
        $this->assertTrue($slip->scholarship_applied);
    }

    public function test_a_flat_amount_scholarship_student_challan_reflects_the_discount(): void
    {
        $student = Student::factory()->create([
            'academic_program_id' => $this->program->id,
            'scholarship_type'    => 'flat',
            'scholarship_value'   => 3000,
        ]);

        $summary = FeePayment::invoiceSummary($student, 'tuition', null, null);
        $this->assertSame(7000.0, $summary['available']);

        $slip = FeePayment::generateSlip($student, [
            'fee_type' => 'tuition',
            'amount'   => $summary['available'],
        ]);

        $this->assertSame(7000.0, (float) $slip->amount_due);
        $this->assertSame(10000.0, (float) $slip->original_fee_amount);
        $this->assertSame(3000.0, (float) $slip->scholarship_discount_amount);
    }

    public function test_a_manual_discount_stacks_on_top_of_the_challan_without_touching_amount_due(): void
    {
        $student = Student::factory()->create(['academic_program_id' => $this->program->id]);

        $slip = FeePayment::generateSlip($student, [
            'fee_type' => 'tuition',
            'amount'   => 10000,
        ]);

        // Mirrors the "Apply Discount" table action (FeePaymentResource) —
        // manual_discount_amount is set directly, and the model's saving()
        // hook derives discount_amount from it.
        $slip->manual_discount_amount = 1000;
        $slip->save();

        $this->assertSame(1000.0, (float) $slip->refresh()->discount_amount);
        $this->assertSame(9000.0, $slip->net_amount);
    }
}
