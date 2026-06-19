<?php

namespace Tests\Feature\Portal;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_login_renders_for_guest_and_redirects_authenticated_student(): void
    {
        $this->get(route('portal.login'))->assertOk();

        $student = Student::factory()->create(['portal_password' => 'secret123']);

        $this->actingAs($student, 'student')
            ->get(route('portal.login'))
            ->assertRedirect(route('portal.dashboard'));
    }

    public function test_login_requires_roll_number_and_password(): void
    {
        $this->post(route('portal.login.post'), [])
            ->assertSessionHasErrors(['roll_number', 'password']);
    }

    public function test_login_rejects_unknown_roll_number(): void
    {
        $this->post(route('portal.login.post'), [
            'roll_number' => 'CS-404',
            'password' => 'anything',
        ])->assertSessionHasErrors(['roll_number']);
    }

    public function test_login_rejects_inactive_student_even_with_correct_password(): void
    {
        $student = Student::factory()->inactive()->create([
            'roll_number' => 'CS-2026-0009',
            'portal_password' => 'secret123',
        ]);

        $this->post(route('portal.login.post'), [
            'roll_number' => $student->roll_number,
            'password' => 'secret123',
        ])->assertSessionHasErrors(['roll_number']);

        $this->assertGuest('student');
    }

    public function test_login_rejects_incorrect_password_for_active_student(): void
    {
        $student = Student::factory()->create([
            'roll_number' => 'CS-2026-0001',
            'portal_password' => 'correct-pass',
        ]);

        $this->post(route('portal.login.post'), [
            'roll_number' => $student->roll_number,
            'password' => 'wrong-pass',
        ])->assertSessionHasErrors(['password']);
    }

    public function test_login_accepts_trimmed_roll_number_with_hashed_portal_password(): void
    {
        $student = Student::factory()->create([
            'roll_number' => 'CS-2026-0011',
            'portal_password' => 'student@1234',
        ]);

        $this->post(route('portal.login.post'), [
            'roll_number' => '  ' . $student->roll_number . '  ',
            'password' => 'student@1234',
        ])->assertRedirect(route('portal.dashboard'));

        $this->assertAuthenticatedAs($student, 'student');
    }

    public function test_login_accepts_roll_number_as_default_password_when_portal_password_is_null(): void
    {
        $student = Student::factory()->create([
            'roll_number' => 'CS-2026-0012',
            'portal_password' => null,
        ]);

        $this->post(route('portal.login.post'), [
            'roll_number' => $student->roll_number,
            'password' => $student->roll_number,
        ])->assertRedirect(route('portal.dashboard'));

        $this->assertAuthenticatedAs($student, 'student');
    }

    public function test_logout_logs_student_out_and_redirects_to_login(): void
    {
        $student = Student::factory()->create();

        $this->actingAs($student, 'student')
            ->post(route('portal.logout'))
            ->assertRedirect(route('portal.login'))
            ->assertSessionHas('success');

        $this->assertGuest('student');
    }

    public function test_student_login_is_rate_limited_after_threshold(): void
    {
        $student = Student::factory()->create([
            'roll_number' => 'CS-2026-0013',
            'portal_password' => 'student@1234',
        ]);

        foreach (range(1, 5) as $attempt) {
            $this->post(route('portal.login.post'), [
                'roll_number' => $student->roll_number,
                'password' => 'wrong-pass',
            ]);
        }

        $this->post(route('portal.login.post'), [
            'roll_number' => $student->roll_number,
            'password' => 'wrong-pass',
        ])->assertStatus(429);
    }
}
