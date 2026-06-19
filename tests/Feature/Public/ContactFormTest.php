<?php

namespace Tests\Feature\Public;

use App\Mail\AdmissionInquiryAcknowledgementMail;
use App\Mail\AdmissionInquiryOfficeNotificationMail;
use App\Mail\ContactMessageAcknowledgementMail;
use App\Mail\ContactMessageOfficeNotificationMail;
use App\Models\AdmissionInquiry;
use App\Models\AcademicProgram;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    // ── Contact form ──────────────────────────────────────────────────────────

    public function test_contact_form_requires_all_fields(): void
    {
        $response = $this->post('/contact/send', []);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_contact_form_rejects_invalid_email(): void
    {
        $response = $this->post('/contact/send', [
            'name'    => 'Test User',
            'email'   => 'not-an-email',
            'subject' => 'Hello',
            'message' => 'Test message',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_contact_form_saves_message_and_redirects(): void
    {
        Mail::fake();

        $response = $this->post('/contact/send', [
            'name'    => 'Arif Ali',
            'email'   => 'arif@test.com',
            'subject' => 'Enquiry',
            'message' => 'I have a question about admissions.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name'    => 'Arif Ali',
            'email'   => 'arif@test.com',
            'subject' => 'Enquiry',
        ]);

        Mail::assertQueued(ContactMessageAcknowledgementMail::class);
        Mail::assertQueued(ContactMessageOfficeNotificationMail::class);
    }

    public function test_contact_form_is_rate_limited_after_threshold(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->post('/contact/send', [
                'name' => 'Rate Test',
                'email' => 'rate@test.com',
                'subject' => 'Attempt ' . $attempt,
                'message' => 'Contact rate limit test.',
            ]);
        }

        $this->post('/contact/send', [
            'name' => 'Rate Test',
            'email' => 'rate@test.com',
            'subject' => 'Blocked',
            'message' => 'Should be blocked.',
        ])->assertStatus(429);
    }

    // ── Admission inquiry ─────────────────────────────────────────────────────

    public function test_admission_inquiry_requires_core_fields(): void
    {
        $response = $this->post('/admissions/inquiry', []);

        $response->assertSessionHasErrors(['entry_path', 'program_id', 'name', 'phone', 'declare_true']);
    }

    public function test_admission_inquiry_saves_and_redirects(): void
    {
        Mail::fake();

        $program = AcademicProgram::factory()->create([
            'is_active' => true,
            'show_on_website' => true,
            'admission_category' => 'intermediate',
        ]);

        $response = $this->post('/admissions/inquiry', [
            'entry_path'     => 'intermediate',
            'gender'         => 'male',
            'campus'         => 'main',
            'city'           => 'Astore',
            'program_id'     => $program->id,
            'name'          => 'Student Name',
            'father_name'   => 'Guardian Name',
            'cnic'          => '12345-1234567-1',
            'dob'           => '2007-02-15',
            'phone'         => '0311-2345678',
            'student_phone' => '0300-1234567',
            'email'         => 'student@test.com',
            'address'       => 'Village Eidgah, Astore',
            'academic'      => [
                'matric' => [
                    'qualification' => 'matric',
                    'pass_year' => 2024,
                    'board' => 'BISE Gilgit',
                    'marks' => '850/1100',
                    'school' => 'Govt High School Astore',
                ],
            ],
            'declare_true'  => '1',
            'message'       => 'I want to enroll.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admission_inquiries', [
            'name'   => 'Student Name',
            'phone'  => '+923112345678',
            'status' => 'new',
        ]);

        Mail::assertQueued(AdmissionInquiryAcknowledgementMail::class);
        Mail::assertQueued(AdmissionInquiryOfficeNotificationMail::class);
    }

    public function test_admission_inquiry_rejects_invalid_program_id(): void
    {
        $response = $this->post('/admissions/inquiry', [
            'entry_path'     => 'intermediate',
            'gender'         => 'male',
            'campus'         => 'main',
            'city'           => 'Astore',
            'name'           => 'Student Name',
            'father_name'    => 'Guardian Name',
            'cnic'           => '12345-1234567-1',
            'dob'            => '2007-02-15',
            'phone'          => '0311-2345678',
            'email'          => 'student@test.com',
            'address'        => 'Village Eidgah, Astore',
            'program_id'     => 99999,
            'academic'       => [
                'matric' => [
                    'qualification' => 'matric',
                    'pass_year' => 2024,
                    'board' => 'BISE Gilgit',
                    'marks' => '850/1100',
                    'school' => 'Govt High School Astore',
                ],
            ],
            'declare_true'   => '1',
        ]);

        $response->assertSessionHasErrors(['program_id']);
    }

    public function test_admission_inquiry_with_valid_program_id(): void
    {
        $program = AcademicProgram::factory()->create([
            'is_active' => true,
            'show_on_website' => true,
            'admission_category' => 'undergraduate',
        ]);

        $response = $this->post('/admissions/inquiry', [
            'entry_path'     => 'undergraduate',
            'gender'         => 'female',
            'campus'         => 'main',
            'city'           => 'Astore',
            'name'           => 'Student Name',
            'father_name'    => 'Guardian Name',
            'cnic'           => '12345-1234567-1',
            'dob'            => '2005-02-15',
            'phone'          => '0311-2345678',
            'email'          => 'student@test.com',
            'address'        => 'Village Eidgah, Astore',
            'program_id'     => $program->id,
            'academic'       => [
                'matric_summary' => [
                    'board' => 'BISE Gilgit',
                    'pass_year' => 2022,
                    'marks' => '930/1100',
                ],
                'hssc' => [
                    'qualification' => 'hssc',
                    'pass_year' => 2024,
                    'board' => 'BISE Gilgit',
                    'marks' => '910/1100',
                    'school' => 'Govt Degree College',
                ],
            ],
            'declare_true'   => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_admission_inquiry_rejects_program_from_wrong_admission_category(): void
    {
        $program = AcademicProgram::factory()->create([
            'is_active' => true,
            'show_on_website' => true,
            'admission_category' => 'undergraduate',
        ]);

        $response = $this->post('/admissions/inquiry', [
            'entry_path'     => 'intermediate',
            'gender'         => 'male',
            'campus'         => 'main',
            'city'           => 'Astore',
            'program_id'     => $program->id,
            'name'           => 'Student Name',
            'father_name'    => 'Guardian Name',
            'cnic'           => '12345-1234567-1',
            'dob'            => '2007-02-15',
            'phone'          => '0311-2345678',
            'email'          => 'student@test.com',
            'address'        => 'Village Eidgah, Astore',
            'academic'       => [
                'matric' => [
                    'qualification' => 'matric',
                    'pass_year' => 2024,
                    'board' => 'BISE Gilgit',
                    'marks' => '850/1100',
                    'school' => 'Govt High School Astore',
                ],
            ],
            'declare_true'   => '1',
        ]);

        $response->assertSessionHasErrors(['program_id']);
    }

    public function test_admission_step_validation_returns_json_errors(): void
    {
        $response = $this->postJson('/admissions/inquiry', [
            'validation_mode' => 'step',
            'current_step' => 0,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['entry_path', 'program_id', 'gender', 'campus', 'city']);
    }

    public function test_admission_ajax_submission_returns_success_json(): void
    {
        Mail::fake();

        $program = AcademicProgram::factory()->create([
            'is_active' => true,
            'show_on_website' => true,
            'admission_category' => 'intermediate',
        ]);

        $response = $this->postJson('/admissions/inquiry', [
            'entry_path'     => 'intermediate',
            'gender'         => 'male',
            'campus'         => 'main',
            'city'           => 'Astore',
            'program_id'     => $program->id,
            'name'           => 'Student Name',
            'father_name'    => 'Guardian Name',
            'cnic'           => '12345-1234567-1',
            'dob'            => '2007-02-15',
            'phone'          => '0311-2345678',
            'student_phone'  => '0300-1234567',
            'email'          => 'student@test.com',
            'address'        => 'Village Eidgah, Astore',
            'academic'       => [
                'matric' => [
                    'qualification' => 'matric',
                    'pass_year' => 2024,
                    'board' => 'BISE Gilgit',
                    'marks' => '850/1100',
                    'school' => 'Govt High School Astore',
                ],
            ],
            'declare_true'   => '1',
            'message'        => 'I want to enroll.',
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Your admission application has been submitted successfully. Please wait for admission office review and keep your documents ready.');

        Mail::assertQueued(AdmissionInquiryAcknowledgementMail::class);
        Mail::assertQueued(AdmissionInquiryOfficeNotificationMail::class);
    }

    public function test_admission_inquiry_accepts_phone_with_plus_92_prefix(): void
    {
        $program = AcademicProgram::factory()->create([
            'is_active' => true,
            'show_on_website' => true,
            'admission_category' => 'intermediate',
        ]);

        $response = $this->post('/admissions/inquiry', [
            'entry_path'     => 'intermediate',
            'gender'         => 'male',
            'campus'         => 'main',
            'city'           => 'Astore',
            'program_id'     => $program->id,
            'name'           => 'Student Name',
            'father_name'    => 'Guardian Name',
            'cnic'           => '12345-1234567-1',
            'dob'            => '2007-02-15',
            'phone'          => '+92 311-2345678',
            'student_phone'  => '300-1234567',
            'email'          => 'student@test.com',
            'address'        => 'Village Eidgah, Astore',
            'academic'       => [
                'matric' => [
                    'qualification' => 'matric',
                    'pass_year' => 2024,
                    'board' => 'BISE Gilgit',
                    'marks' => '850/1100',
                    'school' => 'Govt High School Astore',
                ],
            ],
            'declare_true'   => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admission_inquiries', [
            'name' => 'Student Name',
            'phone' => '+923112345678',
            'student_phone' => '+923001234567',
        ]);
    }

    public function test_admission_inquiry_is_rate_limited_after_threshold(): void
    {
        $program = AcademicProgram::factory()->create([
            'is_active' => true,
            'show_on_website' => true,
            'admission_category' => 'intermediate',
        ]);

        foreach (range(1, 40) as $attempt) {
            $this->postJson('/admissions/inquiry', [
                'validation_mode' => 'step',
                'current_step' => 0,
                'entry_path' => 'intermediate',
                'program_id' => $program->id,
                'gender' => 'male',
                'campus' => 'main',
                'city' => 'Astore',
                'email' => 'student@test.com',
            ]);
        }

        $this->postJson('/admissions/inquiry', [
            'validation_mode' => 'step',
            'current_step' => 0,
            'entry_path' => 'intermediate',
            'program_id' => $program->id,
            'gender' => 'male',
            'campus' => 'main',
            'city' => 'Astore',
            'email' => 'student@test.com',
        ])->assertStatus(429);
    }
}
