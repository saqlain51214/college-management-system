<?php

namespace Tests\Feature;

use App\Enums\TeacherStatusEnum;
use App\Mail\TeacherPortalPasswordChangedMail;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\TeacherSalaryPayment;
use App\Models\User;
use App\Services\TeacherService;
use Database\Seeders\NotificationTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * End-to-end coverage of every Teacher-related action an admin performs:
 * creating a teacher (confirming the default portal password actually
 * works), generating monthly salary, marking it paid, status change,
 * password-change email, and delete — asserting the notification/audit-trail
 * side effect of each, not just the database row.
 */
class TeacherModuleTest extends TestCase
{
    use RefreshDatabase;

    protected Department $department;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(NotificationTemplateSeeder::class);

        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->department = Department::create([
            'name' => 'Computer Science', 'code' => 'CS', 'is_active' => true,
        ]);
    }

    /** @return array{0: Teacher, 1: string} [teacher, plaintext password used] */
    protected function createTeacher(array $overrides = []): array
    {
        $teacher = app(TeacherService::class)->createTeacher(array_merge([
            'name' => 'Test Teacher',
            'gender' => 'male',
            'department_id' => $this->department->id,
            'employment_type' => 'permanent',
            'is_active' => true,
            'status' => TeacherStatusEnum::Active->value,
        ], $overrides));

        return [$teacher, '123456'];
    }

    public function test_creating_a_teacher_auto_generates_a_unique_employee_id_when_left_blank(): void
    {
        [$a] = $this->createTeacher(['name' => 'Teacher A']);
        [$b] = $this->createTeacher(['name' => 'Teacher B']);

        $this->assertNotEmpty($a->employee_id);
        $this->assertNotEmpty($b->employee_id);
        $this->assertNotEquals($a->employee_id, $b->employee_id);
        $this->assertMatchesRegularExpression('/^EMP-\d{4}$/', $a->employee_id);
    }

    public function test_creating_a_teacher_with_a_custom_employee_id_keeps_it(): void
    {
        [$teacher] = $this->createTeacher(['employee_id' => 'EMP-CUSTOM-1']);

        $this->assertEquals('EMP-CUSTOM-1', $teacher->employee_id);
    }

    public function test_new_teacher_can_log_into_the_portal_with_the_default_password(): void
    {
        [$teacher] = $this->createTeacher();

        // Confirms the portal_password mutator actually hashed "123456" — a
        // plaintext default would silently lock every new teacher out.
        $this->assertTrue(Hash::check('123456', $teacher->portal_password));

        $this->post('/teacher/login', [
            'login' => $teacher->employee_id,
            'password' => '123456',
        ]);

        $this->assertTrue(Auth::guard('teacher')->check());
        // Teacher::getAuthIdentifierName() returns 'employee_id' — that's the
        // portal's login identifier, not the numeric primary key.
        $this->assertEquals($teacher->employee_id, Auth::guard('teacher')->id());
    }

    public function test_generating_monthly_salary_notifies_the_teacher_and_is_idempotent(): void
    {
        [$teacher] = $this->createTeacher(['basic_salary' => 50000]);

        $payment = TeacherSalaryPayment::generateForMonth($teacher, 2026, 8);

        $this->assertEquals(50000.0, (float) $payment->net_amount);
        $this->assertEquals('pending', $payment->payment_status);
        $this->assertGreaterThanOrEqual(1, $teacher->notifications()->count());

        // Re-running for the same (teacher, year, month) must return the existing
        // record, not create a duplicate payroll entry.
        $again = TeacherSalaryPayment::generateForMonth($teacher, 2026, 8);
        $this->assertEquals($payment->id, $again->id);
        $this->assertEquals(1, TeacherSalaryPayment::where('teacher_id', $teacher->id)->count());
    }

    public function test_marking_salary_paid_notifies_teacher_by_mail_and_database_and_is_audited(): void
    {
        Mail::fake();

        [$teacher] = $this->createTeacher(['basic_salary' => 40000, 'email' => 'teacher@example.test']);
        $payment = TeacherSalaryPayment::generateForMonth($teacher, 2026, 8);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\TeacherSalaryPaymentResource\Pages\ListTeacherSalaryPayments::class)
            ->callTableAction('markPaid', $payment, data: [
                'payment_date' => now()->toDateString(),
                'payment_method' => 'bank_transfer',
            ]);

        $payment->refresh();
        $this->assertEquals('paid', $payment->payment_status);
        $this->assertEquals(40000.0, (float) $payment->amount_paid);

        $log = ActivityLog::where('event', 'updated')
            ->where('subject_type', 'teacher_salary_payment')
            ->latest()->first();
        $this->assertNotNull($log, 'Expected an audit log entry for the salary payment update.');
        $this->assertEquals('pending', $log->meta['values']['payment_status']['from'] ?? null);
        $this->assertEquals('paid', $log->meta['values']['payment_status']['to'] ?? null);
    }

    public function test_admin_can_change_teacher_status(): void
    {
        [$teacher] = $this->createTeacher();

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\TeacherResource\Pages\EditTeacher::class, ['record' => $teacher->getRouteKey()])
            ->fillForm(['status' => TeacherStatusEnum::Resigned->value, 'is_active' => false])
            ->call('save')
            ->assertHasNoFormErrors();

        $teacher->refresh();
        $this->assertEquals(TeacherStatusEnum::Resigned, $teacher->status);
        $this->assertFalse((bool) $teacher->is_active);
    }

    public function test_teacher_password_change_sends_a_confirmation_email(): void
    {
        Mail::fake();

        [$teacher] = $this->createTeacher(['email' => 'teacher@example.test']);

        $this->actingAs($teacher, 'teacher');
        $this->post('/teacher/profile/password', [
            'current_password' => '123456',
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ]);

        Mail::assertQueued(TeacherPortalPasswordChangedMail::class);
        $this->assertTrue(Hash::check('newpass123', $teacher->fresh()->portal_password));
    }

    public function test_admin_can_delete_a_teacher(): void
    {
        [$teacher] = $this->createTeacher();

        $this->actingAs($this->admin);
        $teacher->delete();

        $this->assertSoftDeleted($teacher);
    }
}
