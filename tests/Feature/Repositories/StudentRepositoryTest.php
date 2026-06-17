<?php

namespace Tests\Feature\Repositories;

use App\Enums\AdmissionTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\StudentStatusEnum;
use App\Models\Student;
use App\Repositories\StudentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private StudentRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new StudentRepository();
    }

    private function makeStudent(array $overrides = []): Student
    {
        return Student::create(array_merge([
            'name'             => 'Test Student',
            'father_name'      => 'Test Father',
            'roll_number'      => 'CS-2024-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'gender'           => GenderEnum::Male->value,
            'status'           => StudentStatusEnum::Active->value,
            'admission_type'   => AdmissionTypeEnum::Regular->value,
            'batch_year'       => 2024,
            'current_semester' => 1,
        ], $overrides));
    }

    public function test_next_sequence_returns_1_when_no_students_exist(): void
    {
        $seq = $this->repo->nextSequence('CS', 2024);

        $this->assertSame(1, $seq);
    }

    public function test_next_sequence_returns_2_when_one_student_exists(): void
    {
        $this->makeStudent(['roll_number' => 'CS-2024-0001']);

        $seq = $this->repo->nextSequence('CS', 2024);

        $this->assertSame(2, $seq);
    }

    public function test_next_sequence_increments_from_highest_number(): void
    {
        $this->makeStudent(['roll_number' => 'CS-2024-0001']);
        $this->makeStudent(['roll_number' => 'CS-2024-0005']);
        $this->makeStudent(['roll_number' => 'CS-2024-0003']);

        $seq = $this->repo->nextSequence('CS', 2024);

        $this->assertSame(6, $seq);
    }

    public function test_next_sequence_is_isolated_by_department(): void
    {
        $this->makeStudent(['roll_number' => 'EDU-2024-0001']);
        $this->makeStudent(['roll_number' => 'EDU-2024-0002']);

        $seq = $this->repo->nextSequence('CS', 2024);

        $this->assertSame(1, $seq);
    }

    public function test_next_sequence_is_isolated_by_year(): void
    {
        $this->makeStudent(['roll_number' => 'CS-2023-0001', 'batch_year' => 2023]);
        $this->makeStudent(['roll_number' => 'CS-2023-0002', 'batch_year' => 2023]);

        $seq = $this->repo->nextSequence('CS', 2024);

        $this->assertSame(1, $seq);
    }

    public function test_next_sequence_includes_soft_deleted_students(): void
    {
        $student = $this->makeStudent(['roll_number' => 'CS-2024-0003']);
        $student->delete();

        $seq = $this->repo->nextSequence('CS', 2024);

        $this->assertSame(4, $seq);
    }

    public function test_create_persists_student_to_database(): void
    {
        $student = $this->repo->create([
            'name'             => 'Ali Hassan',
            'father_name'      => 'Hassan Raza',
            'roll_number'      => 'CS-2024-0001',
            'gender'           => GenderEnum::Male->value,
            'status'           => StudentStatusEnum::Active->value,
            'admission_type'   => AdmissionTypeEnum::Regular->value,
            'batch_year'       => 2024,
            'current_semester' => 1,
        ]);

        $this->assertDatabaseHas('students', ['roll_number' => 'CS-2024-0001']);
        $this->assertSame('Ali Hassan', $student->name);
    }

    public function test_find_by_roll_number_returns_correct_student(): void
    {
        $this->makeStudent(['roll_number' => 'CS-2024-0001', 'name' => 'Target Student']);
        $this->makeStudent(['roll_number' => 'CS-2024-0002', 'name' => 'Other Student']);

        $found = $this->repo->findByRollNumber('CS-2024-0001');

        $this->assertNotNull($found);
        $this->assertSame('Target Student', $found->name);
    }

    public function test_find_by_roll_number_returns_null_when_not_found(): void
    {
        $result = $this->repo->findByRollNumber('NONEXISTENT-0000');

        $this->assertNull($result);
    }

    public function test_update_changes_student_fields(): void
    {
        $student = $this->makeStudent(['name' => 'Old Name']);

        $updated = $this->repo->update($student, ['name' => 'New Name']);

        $this->assertSame('New Name', $updated->name);
        $this->assertDatabaseHas('students', ['id' => $student->id, 'name' => 'New Name']);
    }

    public function test_delete_soft_deletes_the_student(): void
    {
        $student = $this->makeStudent();

        $this->repo->delete($student);

        $this->assertSoftDeleted('students', ['id' => $student->id]);
    }
}
