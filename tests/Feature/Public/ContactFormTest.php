<?php

namespace Tests\Feature\Public;

use App\Models\AdmissionInquiry;
use App\Models\AcademicProgram;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    }

    // ── Admission inquiry ─────────────────────────────────────────────────────

    public function test_admission_inquiry_requires_name_and_phone(): void
    {
        $response = $this->post('/admissions/inquiry', []);

        $response->assertSessionHasErrors(['name', 'phone']);
    }

    public function test_admission_inquiry_saves_and_redirects(): void
    {
        $response = $this->post('/admissions/inquiry', [
            'name'          => 'Student Name',
            'phone'         => '0311234567',
            'email'         => 'student@test.com',
            'qualification' => 'matric',
            'message'       => 'I want to enroll.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admission_inquiries', [
            'name'   => 'Student Name',
            'phone'  => '0311234567',
            'status' => 'new',
        ]);
    }

    public function test_admission_inquiry_rejects_invalid_program_id(): void
    {
        $response = $this->post('/admissions/inquiry', [
            'name'       => 'Student Name',
            'phone'      => '0311234567',
            'program_id' => 99999,
        ]);

        $response->assertSessionHasErrors(['program_id']);
    }

    public function test_admission_inquiry_with_valid_program_id(): void
    {
        $program = AcademicProgram::factory()->create(['is_active' => true]);

        $response = $this->post('/admissions/inquiry', [
            'name'       => 'Student Name',
            'phone'      => '0311234567',
            'program_id' => $program->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
