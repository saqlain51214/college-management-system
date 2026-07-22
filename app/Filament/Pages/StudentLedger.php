<?php

namespace App\Filament\Pages;

use App\Enums\FeeTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\AcademicYear;
use App\Models\FeePayment;
use App\Models\Student;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class StudentLedger extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Student Account';

    protected static ?string $title = 'Student Account — Fee Ledger';

    protected static ?int $navigationSort = 8;

    protected static string $view = 'filament.pages.student-ledger';

    /** Search box value (registration number or roll number). */
    public string $q = '';

    public bool $searched = false;

    public ?string $notFound = null;

    /** @var array<string,mixed>|null */
    public ?array $student = null;

    /** @var array<int,array<string,mixed>> */
    public array $payments = [];

    /** @var array<string,float|int> */
    public array $totals = [];

    /** Eloquent id of the currently loaded student (kept out of the display array). */
    public ?int $studentId = null;

    // ─── Generate Fee Slip (custom amount) — admin-side self-service parity ──
    // slipSemester/slipAcademicYearId are intentionally untyped: Livewire binds an
    // empty string from the "Not applicable" <select> option, which would throw a
    // TypeError against a strict ?int property. normalizeInt() converts on use.
    public string $slipFeeType = 'tuition';
    public $slipSemester = null;
    public $slipAcademicYearId = null;
    public ?string $slipAmount = null;
    public ?string $slipDueDate = null;
    public ?string $slipSuccess = null;
    public ?string $slipError = null;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'Developer', 'panel_user']) ?? false;
    }

    public function search(): void
    {
        $this->reset(['student', 'payments', 'totals', 'notFound', 'studentId', 'slipSuccess', 'slipError']);
        $this->searched = true;

        $term = trim($this->q);
        if ($term === '') {
            $this->notFound = 'Please enter a registration number or roll number.';
            return;
        }

        $student = Student::with(['academicProgram', 'department'])
            ->where('registration_number', $term)
            ->orWhere('roll_number', $term)
            ->first();

        if (! $student) {
            $this->notFound = "No student found for \"{$term}\". Check the registration/roll number.";
            return;
        }

        $this->loadStudent($student);
    }

    protected function loadStudent(Student $student): void
    {
        $this->studentId = $student->id;

        $this->student = [
            'name'        => $student->name,
            'father'      => $student->father_name,
            'reg'         => $student->registration_number,
            'roll'        => $student->roll_number,
            'program'     => $student->academicProgram?->name,
            'department'  => $student->department?->name,
            'phone'       => $student->phone,
            'gender'      => $student->gender instanceof \BackedEnum ? $student->gender->value : $student->gender,
            'active'      => (bool) $student->is_active,
        ];

        // Default the slip form to the student's current semester/enrollment year.
        $this->slipSemester = $student->current_semester;
        $this->slipAcademicYearId = $student->academic_year_id;

        $today   = Carbon::today();
        $billed  = 0.0;
        $paid    = 0.0;
        $rows    = [];

        foreach ($student->feePayments()->orderByDesc('due_date')->orderByDesc('id')->get() as $p) {
            $net       = (float) $p->amount_due + (float) $p->fine_amount - (float) $p->discount_amount;
            $amtPaid   = (float) $p->amount_paid;
            $balance   = max(0, $net - $amtPaid);
            $billed   += $net;
            $paid     += $amtPaid;
            $status    = $p->payment_status instanceof PaymentStatusEnum ? $p->payment_status->value : (string) $p->payment_status;
            $daysLate  = ($balance > 0 && $p->due_date && $today->gt(Carbon::parse($p->due_date)))
                ? $today->diffInDays(Carbon::parse($p->due_date))
                : 0;

            $rows[] = [
                'id'          => $p->id,
                'challan'     => $p->challan_number,
                'fee_type'    => $p->fee_type instanceof \BackedEnum ? ucwords(str_replace('_', ' ', $p->fee_type->value)) : ucwords(str_replace('_', ' ', (string) $p->fee_type)),
                'semester'    => $p->semester_number,
                'installment' => $p->installment_no,
                'net'         => $net,
                'paid'        => $amtPaid,
                'balance'     => $balance,
                'status'      => $status,
                'due'         => $p->due_date ? Carbon::parse($p->due_date)->format('d M Y') : '—',
                'paid_on'     => $p->payment_date ? Carbon::parse($p->payment_date)->format('d M Y') : '—',
                'days_late'   => $daysLate,
            ];
        }

        $this->payments = $rows;
        $this->totals   = [
            'billed'      => $billed,
            'paid'        => $paid,
            'outstanding' => max(0, $billed - $paid),
            'count'       => count($rows),
            'unpaid'      => collect($rows)->where('balance', '>', 0)->count(),
        ];
    }

    /**
     * Live "how much more can still be invoiced" for the currently selected
     * fee type/semester/year on the Generate Fee Slip form. Recomputes on
     * every render so it reflects dropdown changes immediately.
     *
     * @return array{total: float, already_invoiced: float, available: float}|null
     */
    public function slipSummary(): ?array
    {
        if (! $this->studentId) {
            return null;
        }

        $student = Student::find($this->studentId);
        if (! $student) {
            return null;
        }

        return FeePayment::invoiceSummary($student, $this->slipFeeType, $this->normalizeInt($this->slipSemester), $this->normalizeInt($this->slipAcademicYearId));
    }

    /** Empty-string select values ("Not applicable") normalize to null instead of 0. */
    protected function normalizeInt($value): ?int
    {
        return ($value === null || $value === '') ? null : (int) $value;
    }

    public function generateSlip(): void
    {
        $this->slipSuccess = null;
        $this->slipError   = null;

        $student = $this->studentId ? Student::find($this->studentId) : null;
        if (! $student) {
            $this->slipError = 'No student is loaded. Please search again.';
            return;
        }

        if (blank($this->slipAmount) || (float) $this->slipAmount <= 0) {
            $this->slipError = 'Please enter an amount greater than zero.';
            return;
        }

        try {
            $slip = FeePayment::generateSlip($student, [
                'fee_type'         => $this->slipFeeType,
                'semester_number'  => $this->normalizeInt($this->slipSemester),
                'academic_year_id' => $this->normalizeInt($this->slipAcademicYearId),
                'amount'           => $this->slipAmount,
                'due_date'         => $this->slipDueDate ?: null,
                'remarks'          => 'Custom slip generated by admin from Student Account.',
            ]);
        } catch (\InvalidArgumentException $e) {
            $this->slipError = $e->getMessage();
            return;
        }

        $this->slipSuccess = "Challan {$slip->challan_number} generated for Rs. " . number_format((float) $slip->amount_due) . '.';
        $this->slipAmount  = null;
        $this->loadStudent($student->fresh(['academicProgram', 'department']));
    }

    /** @return array<string,string> */
    public function feeTypeOptions(): array
    {
        return FeeTypeEnum::options();
    }

    public function academicYearOptions()
    {
        return AcademicYear::selectOptions();
    }
}
