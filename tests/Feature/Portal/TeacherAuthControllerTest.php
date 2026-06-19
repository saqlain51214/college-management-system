<?php

namespace Tests\Feature\Portal;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_login_renders_for_guest_and_redirects_authenticated_teacher(): void
    {
        $this->get(route('teacher.login'))->assertOk();

        $teacher = $this->createTeacher([
            'portal_password' => 'secret123',
        ]);

        $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.login'))
            ->assertRedirect(route('teacher.dashboard'));
    }

    public function test_login_requires_login_and_password(): void
    {
        $this->post(route('teacher.login.post'), [])
            ->assertSessionHasErrors(['login', 'password']);
    }

    public function test_login_rejects_unknown_teacher(): void
    {
        $this->post(route('teacher.login.post'), [
            'login' => 'missing@jdca.edu.pk',
            'password' => 'anything',
        ])->assertSessionHasErrors(['login']);
    }

    public function test_login_rejects_inactive_teacher_even_with_correct_password(): void
    {
        $teacher = $this->createTeacher([
            'email' => 'inactive@jdca.edu.pk',
            'portal_password' => 'secret123',
            'is_active' => false,
        ]);

        $this->post(route('teacher.login.post'), [
            'login' => $teacher->email,
            'password' => 'secret123',
        ])->assertSessionHasErrors(['login']);

        $this->assertGuest('teacher');
    }

    public function test_login_accepts_email_with_hashed_portal_password(): void
    {
        $teacher = $this->createTeacher([
            'email' => 'teacher1@jdca.edu.pk',
            'portal_password' => 'teacher@1234',
        ]);

        $this->post(route('teacher.login.post'), [
            'login' => '  ' . $teacher->email . '  ',
            'password' => 'teacher@1234',
        ])->assertRedirect(route('teacher.dashboard'));

        $this->assertAuthenticatedAs($teacher, 'teacher');
    }

    public function test_login_accepts_employee_id_as_default_password_when_portal_password_is_null(): void
    {
        $teacher = $this->createTeacher([
            'employee_id' => 'JDCA-T-099',
            'portal_password' => null,
        ]);

        $this->post(route('teacher.login.post'), [
            'login' => $teacher->employee_id,
            'password' => $teacher->employee_id,
        ])->assertRedirect(route('teacher.dashboard'));

        $this->assertAuthenticatedAs($teacher, 'teacher');
    }

    public function test_logout_logs_teacher_out_and_redirects_to_login(): void
    {
        $teacher = $this->createTeacher();

        $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.logout'))
            ->assertRedirect(route('teacher.login'))
            ->assertSessionHas('success');

        $this->assertGuest('teacher');
    }

    public function test_teacher_login_is_rate_limited_after_threshold(): void
    {
        $teacher = $this->createTeacher([
            'email' => 'teacher-limit@jdca.edu.pk',
            'portal_password' => 'teacher@1234',
        ]);

        foreach (range(1, 5) as $attempt) {
            $this->post(route('teacher.login.post'), [
                'login' => $teacher->email,
                'password' => 'wrong-pass',
            ]);
        }

        $this->post(route('teacher.login.post'), [
            'login' => $teacher->email,
            'password' => 'wrong-pass',
        ])->assertStatus(429);
    }

    private function createTeacher(array $attributes = []): Teacher
    {
        return Teacher::create(array_merge([
            'employee_id' => 'JDCA-T-001',
            'name' => 'Muhammad Bilal',
            'email' => 'teacher@jdca.edu.pk',
            'designation' => 'Lecturer',
            'status' => 'active',
            'is_active' => true,
        ], $attributes));
    }
}
