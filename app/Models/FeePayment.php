<?php

namespace App\Models;

use App\Enums\FeeTypeEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FeePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id', 'fee_structure_id', 'academic_year_id', 'challan_number',
        'receipt_number',
        'fee_type', 'semester_number', 'amount_due', 'amount_paid', 'fine_amount',
        'discount_amount', 'payment_status', 'payment_method', 'due_date',
        'payment_date', 'transaction_id', 'bank_name', 'remarks', 'collected_by',
        'payment_proof_path', 'proof_uploaded_at',
        'installment_no', 'late_fine_per_day', 'proof_claimed_amount', 'proof_claimed_date',
    ];

    protected $casts = [
        'fee_type'             => FeeTypeEnum::class,
        'payment_status'       => PaymentStatusEnum::class,
        'payment_method'       => PaymentMethodEnum::class,
        'due_date'             => 'date',
        'payment_date'         => 'date',
        'amount_due'           => 'decimal:2',
        'amount_paid'          => 'decimal:2',
        'fine_amount'          => 'decimal:2',
        'discount_amount'      => 'decimal:2',
        'late_fine_per_day'    => 'decimal:2',
        'proof_claimed_amount' => 'decimal:2',
        'proof_claimed_date'   => 'date',
        'proof_uploaded_at'    => 'datetime',
    ];

    public function student(): BelongsTo      { return $this->belongsTo(Student::class); }
    public function feeStructure(): BelongsTo { return $this->belongsTo(FeeStructure::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function collector(): BelongsTo    { return $this->belongsTo(User::class, 'collected_by'); }

    public function getNetAmountAttribute(): float
    {
        return (float) $this->amount_due + (float) $this->fine_amount - (float) $this->discount_amount;
    }

    /** Outstanding balance on this challan (never negative). */
    public function getBalanceAttribute(): float
    {
        return max(0, $this->net_amount - (float) $this->amount_paid);
    }

    /**
     * True once the fee is fully paid — such records are locked from deletion.
     */
    public function isLocked(): bool
    {
        return $this->payment_status === PaymentStatusEnum::Paid;
    }

    /**
     * Single source of truth for settling a challan: pay the full net amount
     * (due + fine − discount), stamp the date, record who collected it, issue
     * a receipt number, and notify the student. Every challan is atomic — it
     * is either outstanding or paid in full for its own amount; there is no
     * partial-payment ledger on a single row (flexibility instead comes from
     * generating multiple smaller challans via generateSlip()).
     */
    public function markAsPaid(?int $collectorId = null, ?string $paymentDate = null, ?string $paymentMethod = null): void
    {
        $this->amount_paid    = $this->net_amount;
        $this->payment_status = PaymentStatusEnum::Paid;
        $this->payment_date   = $paymentDate ?: now()->toDateString();

        if ($paymentMethod) {
            $this->payment_method = $paymentMethod;
        }
        if ($collectorId) {
            $this->collected_by = $collectorId;
        }

        $this->save();

        $this->sendPaymentConfirmedNotification();
    }

    protected function sendPaymentConfirmedNotification(): void
    {
        if (! $this->student) {
            return;
        }

        $feeType = $this->fee_type instanceof FeeTypeEnum ? $this->fee_type->label() : ($this->fee_type ?? 'Fee');

        app(NotificationService::class)->send($this->student, 'fee_payment_confirmed', [
            'student_name' => $this->student->name,
            'amount'       => number_format((float) $this->amount_paid),
            'fee_type'     => $feeType,
            'payment_date' => optional($this->payment_date)->format('d M Y') ?? now()->format('d M Y'),
        ]);
    }

    /**
     * How much of the applicable FeeStructure total for this student/period
     * has already been invoiced (i.e. a challan already exists for it,
     * whether paid or still pending), and how much remains available to
     * invoice via a new self-chosen-amount slip.
     *
     * @return array{total: float, already_invoiced: float, available: float}
     */
    public static function invoiceSummary(Student $student, string $feeType, ?int $semester, ?int $academicYearId): array
    {
        $total = static::resolveFeeStructureTotal($student, $feeType, $semester, $academicYearId);

        $alreadyInvoiced = (float) static::query()
            ->where('student_id', $student->id)
            ->where('fee_type', $feeType)
            ->when($semester, fn ($q) => $q->where('semester_number', $semester), fn ($q) => $q->whereNull('semester_number'))
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId), fn ($q) => $q->whereNull('academic_year_id'))
            ->sum('amount_due');

        return [
            'total'            => $total,
            'already_invoiced' => $alreadyInvoiced,
            'available'        => max(0, round($total - $alreadyInvoiced, 2)),
        ];
    }

    /**
     * Create one new Pending challan for a chosen amount (an "installment").
     * Used by admins (custom slip for one student) and by student self-service
     * (portal + public lookup page) alike — the amount is validated against
     * invoiceSummary() so nobody can invoice more than the fee structure total.
     *
     * @param  array{fee_type: string, semester_number?: int|null, academic_year_id?: int|null, amount: float|string, due_date?: string|null, remarks?: string|null}  $data
     */
    public static function generateSlip(Student $student, array $data): self
    {
        $feeType        = $data['fee_type'];
        $semester       = $data['semester_number'] ?? null;
        $academicYearId = $data['academic_year_id'] ?? null;
        $amount         = round((float) $data['amount'], 2);

        if ($amount <= 0) {
            throw new \InvalidArgumentException('The amount must be greater than zero.');
        }

        $summary = static::invoiceSummary($student, $feeType, $semester, $academicYearId);
        if ($amount > $summary['available'] + 0.01) {
            throw new \InvalidArgumentException(
                'This amount exceeds the remaining balance that can still be invoiced for this period (Rs. ' . number_format($summary['available']) . ' available).'
            );
        }

        $installmentNo = (int) static::query()
            ->where('student_id', $student->id)
            ->where('fee_type', $feeType)
            ->when($semester, fn ($q) => $q->where('semester_number', $semester), fn ($q) => $q->whereNull('semester_number'))
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId), fn ($q) => $q->whereNull('academic_year_id'))
            ->count() + 1;

        $structure = static::resolveFeeStructure($student, $feeType, $semester, $academicYearId);

        return static::create([
            'student_id'        => $student->id,
            'fee_structure_id'  => $structure?->id,
            'academic_year_id'  => $academicYearId,
            'challan_number'    => 'CHN-' . strtoupper(Str::random(8)),
            'fee_type'          => $feeType,
            'semester_number'   => $semester,
            'amount_due'        => $amount,
            'amount_paid'       => 0,
            'fine_amount'       => 0,
            'discount_amount'   => 0,
            'payment_status'    => PaymentStatusEnum::Pending,
            'due_date'          => $data['due_date'] ?? now()->addDays(15)->toDateString(),
            'installment_no'    => $installmentNo,
            'late_fine_per_day' => $structure?->late_fine_per_day ?? \App\Models\CollegeSetting::get('fee_late_fine_per_day'),
            'remarks'           => $data['remarks'] ?? null,
        ]);
    }

    protected static function resolveFeeStructureTotal(Student $student, string $feeType, ?int $semester, ?int $academicYearId): float
    {
        return (float) FeeStructure::query()
            ->where('is_active', true)
            ->where('fee_type', $feeType)
            ->where(fn ($q) => $q->whereNull('academic_program_id')->orWhere('academic_program_id', $student->academic_program_id))
            ->where(fn ($q) => $q->whereNull('academic_year_id')->orWhere('academic_year_id', $academicYearId))
            ->where(fn ($q) => $q->whereNull('semester_number')->orWhere('semester_number', $semester))
            ->sum('amount');
    }

    protected static function resolveFeeStructure(Student $student, string $feeType, ?int $semester, ?int $academicYearId): ?FeeStructure
    {
        return FeeStructure::query()
            ->where('is_active', true)
            ->where('fee_type', $feeType)
            ->where(fn ($q) => $q->whereNull('academic_program_id')->orWhere('academic_program_id', $student->academic_program_id))
            ->where(fn ($q) => $q->whereNull('academic_year_id')->orWhere('academic_year_id', $academicYearId))
            ->where(fn ($q) => $q->whereNull('semester_number')->orWhere('semester_number', $semester))
            ->orderByRaw('academic_program_id IS NULL')
            ->first();
    }

    public static function generateReceiptNumber(): string
    {
        do {
            $candidate = 'RCPT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::withTrashed()->where('receipt_number', $candidate)->exists());

        return $candidate;
    }

    protected static function booted(): void
    {
        // Whenever a challan is saved in a Paid state, guarantee it has a
        // receipt number and a payment date — regardless of which path set it.
        static::saving(function (self $payment): void {
            if ($payment->payment_status === PaymentStatusEnum::Paid) {
                if (blank($payment->receipt_number)) {
                    $payment->receipt_number = static::generateReceiptNumber();
                }
                if (blank($payment->payment_date)) {
                    $payment->payment_date = now()->toDateString();
                }
            }
        });
    }
}
