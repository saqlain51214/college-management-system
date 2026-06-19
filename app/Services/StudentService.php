<?php

namespace App\Services;

use App\Helpers\CollegeHelper;
use App\Mail\StudentPortalWelcomeMail;
use App\Models\Department;
use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class StudentService
{
    public function __construct(private StudentRepositoryInterface $repo) {}

    public function createStudent(array $data): Student
    {
        $data['roll_number'] = $this->generateRollNumber($data);
        $student = $this->repo->create($data);

        if (filled($student->email) && config('platform.notifications.send_student_welcome_email', true)) {
            Mail::to($student->email)->queue(new StudentPortalWelcomeMail($student));
        }

        return $student;
    }

    public function updateStudent(Student $student, array $data): Student
    {
        return $this->repo->update($student, $data);
    }

    public function deleteStudent(Student $student): bool
    {
        return $this->repo->delete($student);
    }

    private function generateRollNumber(array $data): string
    {
        $deptCode  = $this->resolveDeptCode($data);
        $batchYear = $data['batch_year'] ?? now()->year;
        $sequence  = $this->repo->nextSequence($deptCode, $batchYear);

        return CollegeHelper::generateRollNumber($deptCode, $batchYear, $sequence);
    }

    private function resolveDeptCode(array $data): string
    {
        if (! empty($data['department_id'])) {
            $code = Department::find($data['department_id'])?->code;
            if ($code) {
                return $code;
            }
        }

        return 'STD';
    }
}
