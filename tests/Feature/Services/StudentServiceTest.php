<?php

namespace Tests\Feature\Services;

use App\Enums\AdmissionTypeEnum;
use App\Enums\DepartmentTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\StudentStatusEnum;
use App\Models\Department;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentServiceTest extends TestCase
{
    use RefreshDatabase;

    private StudentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(StudentService::class);
    }

    private function makeData(array $overrides = []): array
    {
        return array_merge([
            'name'             => 'Test Student',
            'father_name'      => 'Test Father',
            'gender'           => GenderEnum::Male->value,
            'status'           => StudentStatusEnum::Active->value,
            'admission_type'   => AdmissionTypeEnum::Regular->value,
            'batch_year'       => 2024,
            'current_semester' => 1,
        ], $overrides);
    }

    public function test_create_student_auto_generates_roll_number(): void
    {
        $student = $this->service->createStudent($this->makeData());

        $this->assertNotEmpty($student->roll_number);
        $this->assertStringContainsString('2024', $student->roll_number);
    }

    public function test_roll_number_uses_department_code(): void
    {
        $dept = Department::create([
            'name' => 'Computer Science',
            'code' => 'DEPT-CS',
            'type' => DepartmentTypeEnum::Academic->value,
        ]);

        $student = $this->service->createStudent($this->makeData(['department_id' => $dept->id]));

        $this->assertStringStartsWith('DEPT-CS-', $student->roll_number);
    }

    public function test_roll_number_falls_back_to_std_without_department(): void
    {
        $student = $this->service->createStudent($this->makeData(['department_id' => null]));

        $this->assertStringStartsWith('STD-', $student->roll_number);
    }

    public function test_second_student_gets_incremented_sequence(): void
    {
        $dept = Department::create([
            'name' => 'Education',
            'code' => 'DEPT-EDU',
            'type' => DepartmentTypeEnum::Academic->value,
        ]);

        $data      = $this->makeData(['department_id' => $dept->id, 'batch_year' => 2024]);
        $student1  = $this->service->createStudent($data);
        $student2  = $this->service->createStudent($data);

        $this->assertNotSame($student1->roll_number, $student2->roll_number);
        // Both should be DEPT-EDU-2024-0001 and DEPT-EDU-2024-0002
        $this->assertStringEndsWith('0001', $student1->roll_number);
        $this->assertStringEndsWith('0002', $student2->roll_number);
    }

    public function test_update_student_persists_changes(): void
    {
        $student = $this->service->createStudent($this->makeData(['name' => 'Before Update']));

        $updated = $this->service->updateStudent($student, ['name' => 'After Update']);

        $this->assertSame('After Update', $updated->name);
        $this->assertDatabaseHas('students', ['id' => $student->id, 'name' => 'After Update']);
    }

    public function test_update_student_does_not_change_roll_number(): void
    {
        $student     = $this->service->createStudent($this->makeData());
        $originalRoll = $student->roll_number;

        $this->service->updateStudent($student, ['name' => 'New Name']);

        $this->assertDatabaseHas('students', ['id' => $student->id, 'roll_number' => $originalRoll]);
    }

    public function test_delete_student_soft_deletes(): void
    {
        $student = $this->service->createStudent($this->makeData());

        $result = $this->service->deleteStudent($student);

        $this->assertTrue($result);
        $this->assertSoftDeleted('students', ['id' => $student->id]);
    }

    public function test_students_from_different_batches_have_independent_sequences(): void
    {
        $dept = Department::create([
            'name' => 'Computer Science',
            'code' => 'DEPT-CS',
            'type' => DepartmentTypeEnum::Academic->value,
        ]);

        $s2024 = $this->service->createStudent($this->makeData(['department_id' => $dept->id, 'batch_year' => 2024]));
        $s2025 = $this->service->createStudent($this->makeData(['department_id' => $dept->id, 'batch_year' => 2025]));

        $this->assertStringContainsString('2024-0001', $s2024->roll_number);
        $this->assertStringContainsString('2025-0001', $s2025->roll_number);
    }

    public function test_roll_number_sequence_skips_over_soft_deleted_students(): void
    {
        $dept = Department::create([
            'name' => 'Computer Science',
            'code' => 'CS',
            'type' => DepartmentTypeEnum::Academic->value,
        ]);

        $data     = $this->makeData(['department_id' => $dept->id, 'batch_year' => 2024]);
        $student1 = $this->service->createStudent($data);
        $student2 = $this->service->createStudent($data);
        $student2->delete();

        $student3 = $this->service->createStudent($data);

        $this->assertStringEndsWith('0003', $student3->roll_number);
    }
}
