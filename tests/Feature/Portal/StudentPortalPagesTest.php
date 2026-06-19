<?php

namespace Tests\Feature\Portal;

use App\Models\Announcement;
use App\Models\ExamResult;
use App\Models\FeePayment;
use App\Models\Student;
use App\Models\Timetable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentPortalPagesTest extends TestCase
{
    use RefreshDatabase;

    private Student $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->student = Student::factory()->create([
            'roll_number'      => 'CS-2024-TEST',
            'name'             => 'Test Portal Student',
            'current_semester' => 1,
            'portal_password'  => 'test@1234',
        ]);
    }

    private function asStudent(): static
    {
        return $this->actingAs($this->student, 'student');
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function test_dashboard_loads_for_authenticated_student(): void
    {
        $response = $this->asStudent()->get('/portal');
        $response->assertStatus(200);
        $response->assertSee('Test Portal Student');
    }

    public function test_dashboard_shows_student_roll_number(): void
    {
        $response = $this->asStudent()->get('/portal');
        $response->assertSee('CS-2024-TEST');
    }

    public function test_dashboard_shows_fee_balance_zero_when_no_fees(): void
    {
        $response = $this->asStudent()->get('/portal');
        $response->assertStatus(200);
        $response->assertSee('Rs. 0');
    }

    // ── Results ───────────────────────────────────────────────────────────────

    public function test_results_page_loads(): void
    {
        $response = $this->asStudent()->get('/portal/results');
        $response->assertStatus(200);
    }

    public function test_results_page_shows_empty_state_when_no_results(): void
    {
        $response = $this->asStudent()->get('/portal/results');

        $response->assertStatus(200);
        $response->assertSee('No Published Results');
    }

    // ── Fees ──────────────────────────────────────────────────────────────────

    public function test_fees_page_loads(): void
    {
        $response = $this->asStudent()->get('/portal/fees');
        $response->assertStatus(200);
    }

    public function test_fees_page_shows_empty_state_when_no_payments(): void
    {
        $response = $this->asStudent()->get('/portal/fees');

        $response->assertStatus(200);
        $response->assertSee('No Fee Records');
    }

    public function test_fees_page_shows_correct_summary_totals(): void
    {
        FeePayment::factory()->create([
            'student_id'   => $this->student->id,
            'amount_due'   => 15000,
            'amount_paid'  => 10000,
            'payment_status' => 'partial',
            'challan_number' => 'CH-TEST-001',
        ]);

        $response = $this->asStudent()->get('/portal/fees');

        $response->assertStatus(200);
        $response->assertSee('15,000');
        $response->assertSee('10,000');
    }

    public function test_fees_page_does_not_show_other_students_fees(): void
    {
        $otherStudent = Student::factory()->create();

        FeePayment::factory()->create([
            'student_id'     => $otherStudent->id,
            'challan_number' => 'CH-OTHER-999',
        ]);

        $response = $this->asStudent()->get('/portal/fees');

        $response->assertStatus(200);
        $response->assertDontSee('CH-OTHER-999');
    }

    // ── Timetable ─────────────────────────────────────────────────────────────

    public function test_timetable_page_loads(): void
    {
        $response = $this->asStudent()->get('/portal/timetable');
        $response->assertStatus(200);
    }

    public function test_timetable_page_shows_empty_state_when_no_slots(): void
    {
        $response = $this->asStudent()->get('/portal/timetable');

        $response->assertStatus(200);
        $response->assertSee('No Timetable Published');
    }

    // ── Notices ───────────────────────────────────────────────────────────────

    public function test_notices_page_loads(): void
    {
        $response = $this->asStudent()->get('/portal/notices');
        $response->assertStatus(200);
    }

    public function test_notices_page_shows_published_notices_for_students(): void
    {
        Announcement::factory()->create([
            'title'        => 'Student Notice',
            'is_published' => true,
            'audience'     => 'students',
            'publish_date' => now(),
            'expiry_date'  => null,
        ]);
        Announcement::factory()->create([
            'title'        => 'Staff Only Notice',
            'is_published' => true,
            'audience'     => 'staff',
            'publish_date' => now(),
            'expiry_date'  => null,
        ]);

        $response = $this->asStudent()->get('/portal/notices');

        $response->assertStatus(200);
        $response->assertSee('Student Notice');
        $response->assertDontSee('Staff Only Notice');
    }

    // ── Profile ───────────────────────────────────────────────────────────────

    public function test_profile_page_loads(): void
    {
        $response = $this->asStudent()->get('/portal/profile');
        $response->assertStatus(200);
    }

    public function test_profile_page_shows_student_info(): void
    {
        $response = $this->asStudent()->get('/portal/profile');

        $response->assertSee('Test Portal Student');
        $response->assertSee('CS-2024-TEST');
    }

    // ── Password change ───────────────────────────────────────────────────────

    public function test_password_change_requires_all_fields(): void
    {
        $response = $this->asStudent()->post('/portal/profile/password', []);

        $response->assertSessionHasErrors(['current_password', 'password']);
    }

    public function test_password_change_fails_with_wrong_current_password(): void
    {
        $response = $this->asStudent()->post('/portal/profile/password', [
            'current_password'      => 'wrong@pass',
            'password'              => 'new@password',
            'password_confirmation' => 'new@password',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_password_change_succeeds_with_correct_current_password(): void
    {
        $response = $this->asStudent()->post('/portal/profile/password', [
            'current_password'      => 'test@1234',
            'password'              => 'newpass@123',
            'password_confirmation' => 'newpass@123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify new password was stored
        $this->student->refresh();
        $this->assertTrue(Hash::check('newpass@123', $this->student->portal_password));
    }

    public function test_password_change_fails_when_confirmation_does_not_match(): void
    {
        $response = $this->asStudent()->post('/portal/profile/password', [
            'current_password'      => 'test@1234',
            'password'              => 'newpass@123',
            'password_confirmation' => 'different@456',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_password_change_enforces_minimum_length(): void
    {
        $response = $this->asStudent()->post('/portal/profile/password', [
            'current_password'      => 'test@1234',
            'password'              => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // ── Fee challan security ──────────────────────────────────────────────────

    public function test_student_cannot_access_another_students_challan(): void
    {
        $otherStudent = Student::factory()->create();
        $payment = FeePayment::factory()->create([
            'student_id'     => $otherStudent->id,
            'challan_number' => 'CH-OTHER-001',
        ]);

        $response = $this->asStudent()->get('/portal/fees/' . $payment->id . '/challan');

        $response->assertStatus(403);
    }
}
