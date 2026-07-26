<?php

namespace App\Filament\Pages;

use App\Models\Teacher;
use App\Models\TeacherSalaryPayment;
use Filament\Pages\Page;

class TeacherPayrollLedger extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Payroll';

    protected static ?string $navigationLabel = 'Payroll Ledger';

    protected static ?string $title = 'Teacher Payroll Ledger — Salary History';

    protected static ?int $navigationSort = 16;

    protected static string $view = 'filament.pages.teacher-payroll-ledger';

    /** Search box value (teacher name or employee ID). */
    public string $q = '';

    public bool $searched = false;

    public ?string $notFound = null;

    /** @var array<string,mixed>|null */
    public ?array $teacher = null;

    /** @var array<int,array<string,mixed>> */
    public array $payments = [];

    /** @var array<string,float|int> */
    public array $totals = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'Developer', 'panel_user']) ?? false;
    }

    public function mount(): void
    {
        // Support deep-linking from the resource's "View History" row action:
        // /admin/teacher-payroll-ledger?teacher=<id>
        $teacherId = request()->query('teacher');
        if ($teacherId) {
            $teacher = Teacher::find($teacherId);
            if ($teacher) {
                $this->q = $teacher->employee_id ?: $teacher->name;
                $this->searched = true;
                $this->loadTeacher($teacher);
            }
        }
    }

    public function search(): void
    {
        $this->reset(['teacher', 'payments', 'totals', 'notFound']);
        $this->searched = true;

        $term = trim($this->q);
        if ($term === '') {
            $this->notFound = 'Please enter a teacher name or employee ID.';
            return;
        }

        $teacher = Teacher::where('employee_id', $term)
            ->orWhere('name', 'like', "%{$term}%")
            ->first();

        if (! $teacher) {
            $this->notFound = "No teacher found for \"{$term}\". Check the name or employee ID.";
            return;
        }

        $this->loadTeacher($teacher);
    }

    protected function loadTeacher(Teacher $teacher): void
    {
        $this->teacher = [
            'id'          => $teacher->id,
            'name'        => $teacher->name,
            'employee_id' => $teacher->employee_id,
            'designation' => $teacher->designation,
            'department'  => $teacher->department?->name,
            'phone'       => $teacher->phone,
            'active'      => (bool) $teacher->is_active,
        ];

        $net    = 0.0;
        $paid   = 0.0;
        $rows   = [];

        foreach ($teacher->salaryPayments()->orderByDesc('year')->orderByDesc('month')->get() as $p) {
            $net  += (float) $p->net_amount;
            $paid += (float) $p->amount_paid;

            $rows[] = [
                'id'         => $p->id,
                'reference'  => $p->reference_no,
                'period'     => $p->month_label,
                'net'        => (float) $p->net_amount,
                'paid'       => (float) $p->amount_paid,
                'balance'    => $p->balance,
                'status'     => $p->payment_status,
                'due'        => $p->due_date ? $p->due_date->format('d M Y') : '—',
                'paid_on'    => $p->payment_date ? $p->payment_date->format('d M Y') : '—',
                'method'     => $p->payment_method?->value ?? $p->payment_method,
            ];
        }

        $this->payments = $rows;
        $this->totals = [
            'net'         => $net,
            'paid'        => $paid,
            'outstanding' => max(0, $net - $paid),
            'count'       => count($rows),
            'pending'     => collect($rows)->where('balance', '>', 0)->count(),
        ];
    }
}
