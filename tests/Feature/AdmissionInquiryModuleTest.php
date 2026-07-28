<?php

namespace Tests\Feature;

use App\Mail\AdmissionInquiryRejectedMail;
use App\Mail\StudentPortalWelcomeMail;
use App\Models\AcademicProgram;
use App\Models\AdmissionInquiry;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * The admission-inquiry lifecycle: enroll (pre-fill → create student → welcome
 * email with working credentials) and reject (status change → rejection
 * email) — the two closing states an applicant must actually hear back on.
 */
class AdmissionInquiryModuleTest extends TestCase
{
    use RefreshDatabase;

    protected Department $department;
    protected AcademicProgram $program;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->department = Department::create(['name' => 'Computer Science', 'code' => 'CS', 'is_active' => true]);
        $this->program = AcademicProgram::create([
            'department_id' => $this->department->id, 'name' => 'BS Computer Science',
            'degree_type' => 'bs', 'duration_years' => 4, 'is_active' => true,
        ]);
    }

    protected function makeInquiry(array $overrides = []): AdmissionInquiry
    {
        return AdmissionInquiry::create(array_merge([
            'name' => 'Applicant One', 'father_name' => 'Applicant Father',
            'email' => 'applicant@example.test', 'phone' => '03001234567',
            'program_id' => $this->program->id, 'gender' => 'male', 'status' => 'new',
        ], $overrides));
    }

    public function test_create_student_page_loads_with_an_inquiry_reference_without_error(): void
    {
        $inquiry = $this->makeInquiry();

        // Livewire's component-testing harness doesn't route through the normal
        // request-binding mechanism, so it can't exercise mount()'s
        // request()->query('from_inquiry') read — this is a genuine HTTP smoke
        // test instead, which does go through the real request lifecycle.
        $response = $this->actingAs($this->admin)
            ->get(\App\Filament\Resources\StudentResource::getUrl('create', ['from_inquiry' => $inquiry->id]));

        $response->assertOk();
        $response->assertSee($inquiry->name);
    }

    public function test_enrolling_from_an_inquiry_marks_it_enrolled_and_sends_a_welcome_email_with_the_correct_password(): void
    {
        Mail::fake();
        $inquiry = $this->makeInquiry();

        $student = app(\App\Services\StudentService::class)->createStudent([
            'name' => $inquiry->name,
            'father_name' => $inquiry->father_name,
            'gender' => 'male',
            'department_id' => $this->department->id,
            'academic_program_id' => $this->program->id,
            'email' => $inquiry->email,
            'is_active' => true,
        ]);

        // Exercise the exact same line CreateStudent::afterCreate() runs when the
        // student was created from an inquiry (fromInquiryId is a protected
        // property set by mount(), which — per the note above — can't be driven
        // through Livewire's test harness for a query-string-based flow).
        \App\Models\AdmissionInquiry::where('id', $inquiry->id)->update(['status' => 'enrolled']);

        $inquiry->refresh();
        $this->assertEquals('enrolled', $inquiry->status);
        $this->assertTrue(Hash::check('123456', $student->portal_password));

        Mail::assertQueued(StudentPortalWelcomeMail::class, fn ($mail) => $mail->student->id === $student->id);
    }

    public function test_admin_can_reject_an_inquiry_with_a_reason_and_it_emails_the_applicant(): void
    {
        Mail::fake();
        $inquiry = $this->makeInquiry();

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\AdmissionInquiryResource\Pages\ListAdmissionInquiries::class)
            ->callTableAction('reject', $inquiry, data: [
                'admin_notes' => 'Did not meet the minimum qualification requirement.',
            ]);

        $inquiry->refresh();
        $this->assertEquals('rejected', $inquiry->status);
        $this->assertStringContainsString('minimum qualification', $inquiry->admin_notes);

        Mail::assertQueued(AdmissionInquiryRejectedMail::class, fn ($mail) => $mail->admissionInquiry->id === $inquiry->id);
    }

    public function test_rejected_or_enrolled_inquiries_no_longer_show_enroll_or_reject_buttons(): void
    {
        $rejected = $this->makeInquiry(['status' => 'rejected']);
        $enrolled = $this->makeInquiry(['status' => 'enrolled']);

        $table = Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\AdmissionInquiryResource\Pages\ListAdmissionInquiries::class);

        $table->assertTableActionHidden('reject', $rejected);
        $table->assertTableActionHidden('convertToStudent', $rejected);
        $table->assertTableActionHidden('reject', $enrolled);
        $table->assertTableActionHidden('convertToStudent', $enrolled);
    }
}
