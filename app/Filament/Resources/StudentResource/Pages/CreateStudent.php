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
            'academic_program_id'   => $inquiry->program_id,
            'previous_qualification'=> $inquiry->qualification,
            'is_active'             => true,
        ]);

        Notification::make()
            ->title('Application loaded')
            ->body('Review the pre-filled details, complete the remaining fields, then Create.')
            ->info()->send();
    }

    protected function afterCreate(): void
    {
        if ($this->fromInquiryId) {
            AdmissionInquiry::where('id', $this->fromInquiryId)->update(['status' => 'enrolled']);
        }
    }

    protected function handleRecordCreation(array $data): Model
    {
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
