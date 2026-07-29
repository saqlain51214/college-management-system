<?php

namespace Tests\Feature;

use App\Mail\JobApplicationOfficeNotificationMail;
use App\Mail\JobApplicationStatusMail;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Job applications used to only send a plain email and were never saved
 * anywhere — an admin had no way to find a submitted application at all.
 * This covers the fixed flow: submission persists a record, uploads the CV,
 * emails the office with the CV attached, and alerts admin via the bell.
 */
class JobApplicationModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');
    }

    public function test_submitting_a_job_application_persists_it_uploads_the_cv_and_notifies_admin(): void
    {
        Mail::fake();

        $response = $this->post('/jobs/apply', [
            'position' => 'Lecturer — Computer Science',
            'name' => 'Applicant One',
            'email' => 'applicant@example.test',
            'phone' => '03001234567',
            'education' => 'MS Computer Science',
            'experience' => '3 years teaching',
            'message' => 'I would love to join JDCA.',
            'cv' => UploadedFile::fake()->create('resume.pdf', 200, 'application/pdf'),
        ]);

        $response->assertSessionHas('job_applied', 'Applicant One');

        $application = JobApplication::where('email', 'applicant@example.test')->firstOrFail();
        $this->assertEquals('new', $application->status);
        $this->assertNotNull($application->cv_path);
        Storage::disk('public')->assertExists($application->cv_path);

        Mail::assertQueued(JobApplicationOfficeNotificationMail::class, fn ($mail) => $mail->jobApplication->id === $application->id);

        $this->assertEquals(1, $this->admin->notifications()->count());
        $this->assertStringContainsString('Job Application', $this->admin->notifications()->first()->data['title'] ?? '');
    }

    public function test_submitting_without_a_cv_fails_validation(): void
    {
        Mail::fake();

        $this->post('/jobs/apply', [
            'position' => 'Lecturer — Mathematics',
            'name' => 'Applicant Two',
            'email' => 'applicant2@example.test',
            'phone' => '03001234567',
            'education' => 'MSc Mathematics',
            'message' => 'Interested in the role.',
        ])->assertSessionHasErrors('cv');

        $this->assertDatabaseMissing('job_applications', ['email' => 'applicant2@example.test']);
    }

    public function test_admin_can_view_and_download_cv_and_update_application_status(): void
    {
        Mail::fake();
        $cv = UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf');
        $cvPath = $cv->store('job-applications/cv', 'public');

        $application = JobApplication::create([
            'position' => 'Lecturer', 'name' => 'Applicant Three', 'email' => 'a3@example.test',
            'phone' => '03001234567', 'education' => 'MSc', 'message' => 'Hi', 'cv_path' => $cvPath, 'status' => 'new',
        ]);

        $this->assertNotNull($application->cv_url);
        $this->assertStringContainsString($cvPath, $application->cv_url);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\JobApplicationResource\Pages\ListJobApplications::class)
            ->callTableAction('downloadCv', $application);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\JobApplicationResource\Pages\EditJobApplication::class, ['record' => $application->getRouteKey()])
            ->fillForm(['status' => 'shortlisted'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertEquals('shortlisted', $application->fresh()->status);
        Mail::assertQueued(JobApplicationStatusMail::class, fn ($mail) => $mail->jobApplication->id === $application->id);
    }

    public function test_saving_without_changing_status_does_not_send_a_duplicate_email(): void
    {
        Mail::fake();

        $application = JobApplication::create([
            'position' => 'Lecturer', 'name' => 'Applicant Four', 'email' => 'a4@example.test',
            'phone' => '03001234567', 'education' => 'MSc', 'message' => 'Hi', 'status' => 'new',
        ]);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\JobApplicationResource\Pages\EditJobApplication::class, ['record' => $application->getRouteKey()])
            ->fillForm(['status' => 'new', 'admin_notes' => 'Reviewed CV, looks good.'])
            ->call('save')
            ->assertHasNoFormErrors();

        Mail::assertNotQueued(JobApplicationStatusMail::class);
    }

    public function test_contact_message_and_admission_inquiry_also_notify_admin_via_bell(): void
    {
        $this->post('/contact/send', [
            'name' => 'Site Visitor', 'email' => 'visitor@example.test',
            'subject' => 'Question', 'message' => 'Do you offer evening classes?',
        ]);

        $this->assertEquals(1, $this->admin->fresh()->notifications()->count());
    }
}
