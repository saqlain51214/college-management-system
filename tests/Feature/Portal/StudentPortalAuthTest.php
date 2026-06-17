<?php

namespace Tests\Feature\Portal;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentPortalAuthTest extends TestCase
{
    use RefreshDatabase;

    // ── Login page ────────────────────────────────────────────────────────────

    public function test_login_page_loads(): void
    {
        $response = $this->get('/portal/login');
        $response->assertStatus(200);
    }

    public function test_authenticated_student_is_redirected_from_login(): void
    {
        $student = Student::factory()->withPassword('test@1234')->create();

        $response = $this->actingAs($student, 'student')->get('/portal/login');

        $response->assertRedirect('/portal');
    }

    // ── Login POST ────────────────────────────────────────────────────────────

    public function test_login_requires_roll_number_and_password(): void
    {
        $response = $this->post('/portal/login', []);

        $response->assertSessionHasErrors(['roll_number', 'password']);
    }

    public function test_login_fails_with_wrong_roll_number(): void
    {
        $response = $this->post('/portal/login', [
            'roll_number' => 'WRONG-0000',
            'password'    => 'anything',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        Student::factory()->withPassword('correct@pass')->create(['roll_number' => 'CS-2024-0001']);

        $response = $this->post('/portal/login', [
            'roll_number' => 'CS-2024-0001',
            'password'    => 'wrong@pass',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_student_can_login_with_hashed_password(): void
    {
        Student::factory()->withPassword('student@1234')->create(['roll_number' => 'CS-2024-0001']);

        $response = $this->post('/portal/login', [
            'roll_number' => 'CS-2024-0001',
            'password'    => 'student@1234',
        ]);

        $response->assertRedirect('/portal');
        $this->assertAuthenticatedAs(Student::first(), 'student');
    }

    public function test_student_can_login_with_roll_number_as_default_password(): void
    {
        // When portal_password is null, roll_number is used as the password
        Student::factory()->create([
            'roll_number'     => 'CS-2024-0002',
            'portal_password' => null,
        ]);

        $response = $this->post('/portal/login', [
            'roll_number' => 'CS-2024-0002',
            'password'    => 'CS-2024-0002',
        ]);

        $response->assertRedirect('/portal');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function test_student_can_logout(): void
    {
        $student = Student::factory()->create();

        $response = $this->actingAs($student, 'student')->post('/portal/logout');

        $response->assertRedirect('/portal/login');
        $this->assertGuest('student');
    }

    // ── Guard protection ──────────────────────────────────────────────────────

    public function test_unauthenticated_redirected_from_dashboard(): void
    {
        $response = $this->get('/portal');
        $response->assertRedirect('/portal/login');
    }

    public function test_unauthenticated_redirected_from_results(): void
    {
        $response = $this->get('/portal/results');
        $response->assertRedirect('/portal/login');
    }

    public function test_unauthenticated_redirected_from_fees(): void
    {
        $response = $this->get('/portal/fees');
        $response->assertRedirect('/portal/login');
    }

    public function test_unauthenticated_redirected_from_timetable(): void
    {
        $response = $this->get('/portal/timetable');
        $response->assertRedirect('/portal/login');
    }

    public function test_unauthenticated_redirected_from_notices(): void
    {
        $response = $this->get('/portal/notices');
        $response->assertRedirect('/portal/login');
    }

    public function test_unauthenticated_redirected_from_profile(): void
    {
        $response = $this->get('/portal/profile');
        $response->assertRedirect('/portal/login');
    }
}
