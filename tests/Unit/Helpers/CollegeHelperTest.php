<?php

namespace Tests\Unit\Helpers;

use App\Helpers\CollegeHelper;
use PHPUnit\Framework\TestCase;

class CollegeHelperTest extends TestCase
{
    // ─── generateSlug ─────────────────────────────────────────────────────────

    public function test_generate_slug_converts_spaces_to_hyphens(): void
    {
        $this->assertSame('department-of-computer-science', CollegeHelper::generateSlug('Department of Computer Science'));
    }

    public function test_generate_slug_lowercases_input(): void
    {
        $this->assertSame('bs-computer-science', CollegeHelper::generateSlug('BS Computer Science'));
    }

    // ─── generateCode ─────────────────────────────────────────────────────────

    public function test_generate_code_creates_acronym_from_words(): void
    {
        $this->assertSame('CS', CollegeHelper::generateCode('Computer Science'));
    }

    public function test_generate_code_with_prefix(): void
    {
        $this->assertSame('DEPT-CS', CollegeHelper::generateCode('Computer Science', 'DEPT'));
    }

    public function test_generate_code_four_word_name(): void
    {
        // 'Department of Computer Science' → 4 words → D, O, C, S → 'DOCS'
        $this->assertSame('DOCS', CollegeHelper::generateCode('Department of Computer Science'));
    }

    // ─── formatPhone ──────────────────────────────────────────────────────────

    public function test_format_phone_strips_non_numeric(): void
    {
        $this->assertSame('03001234567', CollegeHelper::formatPhone('0300-123-4567'));
    }

    public function test_format_phone_preserves_plus(): void
    {
        $this->assertSame('+923001234567', CollegeHelper::formatPhone('+92-300-1234567'));
    }

    // ─── formatCnic ───────────────────────────────────────────────────────────

    public function test_format_cnic_formats_13_digit_string(): void
    {
        $this->assertSame('35202-1234567-1', CollegeHelper::formatCnic('3520212345671'));
    }

    public function test_format_cnic_already_formatted_passes_through(): void
    {
        $this->assertSame('35202-1234567-1', CollegeHelper::formatCnic('35202-1234567-1'));
    }

    public function test_format_cnic_short_returns_as_is(): void
    {
        $this->assertSame('12345', CollegeHelper::formatCnic('12345'));
    }

    // ─── gradeFromPercentage ──────────────────────────────────────────────────

    public function test_grade_a_for_85_and_above(): void
    {
        $result = CollegeHelper::gradeFromPercentage(90);
        $this->assertSame('A', $result['grade']);
        $this->assertSame(4.00, $result['points']);
    }

    public function test_grade_a_minus_for_80_to_84(): void
    {
        $result = CollegeHelper::gradeFromPercentage(82);
        $this->assertSame('A-', $result['grade']);
        $this->assertSame(3.67, $result['points']);
    }

    public function test_grade_b_plus_for_75_to_79(): void
    {
        $result = CollegeHelper::gradeFromPercentage(77);
        $this->assertSame('B+', $result['grade']);
    }

    public function test_grade_b_for_70_to_74(): void
    {
        $result = CollegeHelper::gradeFromPercentage(72);
        $this->assertSame('B', $result['grade']);
        $this->assertSame(3.00, $result['points']);
    }

    public function test_grade_d_for_45_to_49(): void
    {
        $result = CollegeHelper::gradeFromPercentage(47);
        $this->assertSame('D', $result['grade']);
        $this->assertSame(1.00, $result['points']);
    }

    public function test_grade_f_for_below_45(): void
    {
        $result = CollegeHelper::gradeFromPercentage(40);
        $this->assertSame('F', $result['grade']);
        $this->assertSame(0.00, $result['points']);
    }

    public function test_grade_f_for_zero(): void
    {
        $result = CollegeHelper::gradeFromPercentage(0);
        $this->assertSame('F', $result['grade']);
    }

    public function test_grade_a_for_exactly_85(): void
    {
        $result = CollegeHelper::gradeFromPercentage(85);
        $this->assertSame('A', $result['grade']);
    }

    public function test_grade_a_minus_for_exactly_80(): void
    {
        $result = CollegeHelper::gradeFromPercentage(80);
        $this->assertSame('A-', $result['grade']);
    }

    // ─── cgpaToPercentage ─────────────────────────────────────────────────────

    public function test_cgpa_to_percentage_4_0_gives_100(): void
    {
        $this->assertSame(100.0, CollegeHelper::cgpaToPercentage(4.0));
    }

    public function test_cgpa_to_percentage_3_0_gives_75(): void
    {
        $this->assertSame(75.0, CollegeHelper::cgpaToPercentage(3.0));
    }

    public function test_cgpa_to_percentage_2_0_gives_50(): void
    {
        $this->assertSame(50.0, CollegeHelper::cgpaToPercentage(2.0));
    }

    // ─── calculateGpa ─────────────────────────────────────────────────────────

    public function test_calculate_gpa_with_equal_credit_hours(): void
    {
        $courses = [
            ['grade_points' => 4.00, 'credit_hours' => 3],
            ['grade_points' => 3.00, 'credit_hours' => 3],
        ];
        $this->assertSame(3.5, CollegeHelper::calculateGpa($courses));
    }

    public function test_calculate_gpa_with_unequal_credit_hours(): void
    {
        $courses = [
            ['grade_points' => 4.00, 'credit_hours' => 4],
            ['grade_points' => 2.00, 'credit_hours' => 2],
        ];
        // (16 + 4) / 6 = 3.33
        $this->assertSame(3.33, CollegeHelper::calculateGpa($courses));
    }

    public function test_calculate_gpa_returns_zero_for_empty_courses(): void
    {
        $this->assertSame(0.00, CollegeHelper::calculateGpa([]));
    }

    // ─── generateRollNumber ───────────────────────────────────────────────────

    public function test_generate_roll_number_format(): void
    {
        $this->assertSame('CS-2024-0001', CollegeHelper::generateRollNumber('CS', 2024, 1));
    }

    public function test_generate_roll_number_pads_sequence_to_4_digits(): void
    {
        $this->assertSame('DEPT-CS-2025-0042', CollegeHelper::generateRollNumber('DEPT-CS', 2025, 42));
    }

    public function test_generate_roll_number_uppercases_dept_code(): void
    {
        $roll = CollegeHelper::generateRollNumber('edu', 2024, 1);
        $this->assertStringStartsWith('EDU-', $roll);
    }
}
