<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\AdmissionInquiry;
use App\Services\StudentService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    /** Set when this student is being enrolled from an admission inquiry. */
    protected ?int $fromInquiryId = null;

    public function mount(): void
    {
        parent::mount();

        $inquiryId = request()->query('from_inquiry');
        if (! $inquiryId) {
            return;
        }

        $inquiry = AdmissionInquiry::find($inquiryId);
        if (! $inquiry) {
            return;
        }

        $this->fromInquiryId = (int) $inquiry->id;

        // Map the application fields onto the student form (staff review + complete the rest).
        $this->form->fill([
            'name'                  => $inquiry->name,
            'father_name'           => $inquiry->father_name,
            'father_phone'          => $inquiry->father_phone,
            'guardian_name'         => $inquiry->guardian_name,
            'guardian_phone'        => $inquiry->guardian_phone,
            'email'                 => $inquiry->email,
            'phone'                 => $inquiry->student_phone ?: $inquiry->phone,
            'cnic'                  => $inquiry->cnic,
            'gender'                => $inquiry->gender,
            'date_of_birth'         => $inquiry->dob,
            'address'               => $inquiry->address,
            'city'                  => $inquiry->city,
            'district'              => $inquiry->district,
            // department_id is required on Student but not stored on the inquiry —
            // derive it from the chosen programme so admin isn't left to guess it.
            'department_id'         => $inquiry->program?->department_id,
            'academic_program_id'   => $inquiry->program_id,
            'previous_qualification'=> $inquiry->qualification,
            'is_active'             => true,
        ]);

        Notification::make()
            ->title('Application loaded')
            ->body('Review the pre-filled details, complete the remaining fields, then Create.')
            ->info()->send();
    }

    /** Captured from the form's scholarship-catalog picker (not a students-table column). */
    protected ?int $scholarshipId = null;

    protected function afterCreate(): void
    {
        if ($this->fromInquiryId) {
            AdmissionInquiry::where('id', $this->fromInquiryId)->update(['status' => 'enrolled']);
        }

        if ($this->scholarshipId) {
            $scholarship = \App\Models\Scholarship::find($this->scholarshipId);
            if ($scholarship) {
                \App\Models\ScholarshipAward::create([
                    'scholarship_id'  => $scholarship->id,
                    'student_id'      => $this->record->id,
                    'status'          => \App\Enums\ScholarshipStatusEnum::Approved,
                    'amount_awarded'  => filled($scholarship->coverage_percent) ? $scholarship->coverage_percent : $scholarship->amount,
                    'application_date'=> now()->toDateString(),
                    'approval_date'   => now()->toDateString(),
                    'approved_by'     => auth()->id(),
                    'reason'          => 'Assigned during student creation.',
                ]);
            }
        }
    }

    protected function handleRecordCreation(array $data): Model
    {
        $this->scholarshipId = $data['scholarship_id'] ?? null;
        unset($data['scholarship_id']);

        try {
            return app(StudentService::class)->createStudent($data);
        } catch (UniqueConstraintViolationException $e) {
            $msg  = $e->getMessage();
            $body = match(true) {
                str_contains($msg, '_roll_number_') => 'A student with this roll number already exists.',
                str_contains($msg, '_cnic_')        => 'A student with this CNIC is already registered.',
                str_contains($msg, '_email_')       => 'This email address is already in use.',
                default                             => 'A duplicate entry was detected. Please check the data.',
            };
            Notification::make()->title('Duplicate — Cannot Save')->body($body)->danger()->persistent()->send();
            $this->halt();
            return new \App\Models\Student();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
