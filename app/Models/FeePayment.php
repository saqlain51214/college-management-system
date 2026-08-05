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
        'discount_amount', 'manual_discount_amount', 'scholarship_discount_amount',
        'original_fee_amount', 'scholarship_name', 'scholarship_percent', 'scholarship_baked_into_amount_due',
        'scholarship_applied', 'payment_status', 'payment_method', 'due_date',
        'payment_date', 'transaction_id', 'bank_name', 'remarks', 'collected_by',
        'payment_proof_path', 'proof_uploaded_at',
        'installment_no', 'late_fine_per_day', 'proof_claimed_amount', 'proof_claimed_date',
    ];

    protected $casts = [
        'fee_type'                     => FeeTypeEnum::class,
        'payment_status'               => PaymentStatusEnum::class,
        'payment_method'               => PaymentMethodEnum::class,
        'due_date'                     => 'date',
        'payment_date'                 => 'date',
        'amount_due'                   => 'decimal:2',
        'amount_paid'                  => 'decimal:2',
        'fine_amount'                  => 'decimal:2',
        'discount_amount'              => 'decimal:2',
        'manual_discount_amount'       => 'decimal:2',
        'scholarship_discount_amount'  => 'decimal:2',
        'original_fee_amount'          => 'decimal:2',
        'scholarship_percent'          => 'decimal:2',
        'scholarship_applied'          => 'boolean',
        'scholarship_baked_into_amount_due' => 'boolean',
        'late_fine_per_day'            => 'decimal:2',
        'proof_claimed_amount'         => 'decimal:2',
        'proof_claimed_date'           => 'date',
        'proof_uploaded_at'            => 'datetime',
    ];

    public function student(): BelongsTo      { return $this->belongsTo(Student::class); }
    public function feeStructure(): BelongsTo { return $this->belongsTo(FeeStructure::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function collector(): BelongsTo    { return $this->belongsTo(User::class, 'collected_by'); }

    public function getNetAmountAttribute(): float
    {
        return (float) $this->amount_due + (float) $this->fine_amount - (float) $this->discount_amount;
    }

    /**
     * Full "Original Fee → Scholarship → Discount → Final Payable" breakdown
     * for display (admin table, student portal, PDF). Never used for the
     * actual net_amount/balance math above — those keep working exactly as
     * before on amount_due/discount_amount alone, so this is purely additive.
     *
     * @return array{original_fee: float, scholarship_name: ?string, scholarship_percent: ?float, scholarship_discount: float, subtotal_after_scholarship: float, manual_discount: float, fine: float, final_payable: float}
     */
    public function getFeeBreakdownAttribute(): array
    {
        $original            = (float) ($this->original_fee_amount ?? $this->amount_due);
        $scholarshipDiscount = (float) ($this->scholarship_discount_amount ?? 0);
        $subtotal            = round($original - $scholarshipDiscount, 2);
        $manualDiscount      = (float) ($this->manual_discount_amount ?? max(0, (float) $this->discount_amount - $scholarshipDiscount));

        return [
            'original_fee'                => $original,
            'scholarship_name'            => $this->scholarship_name,
            'scholarship_percent'         => $this->scholarship_percent !== null ? (float) $this->scholarship_percent : null,
            'scholarship_discount'        => $scholarshipDiscount,
            'subtotal_after_scholarship'  => $subtotal,
            'manual_discount'             => $manualDiscount,
            'fine'                        => (float) $this->fine_amount,
            'final_payable'               => $this->net_amount,
        ];
    }

    /**
     * Reconstructs the pre-scholarship fee and the scholarship's own discount
     * amount as a point-in-time snapshot, from whatever net amount is about
     * to be billed. Snapshotted (not live-computed) so that if the student's
     * scholarship later changes, this challan's history stays accurate.
     *
     * @return array{original_fee_amount: float, scholarship_discount_amount: float, scholarship_name: ?string, scholarship_percent: ?float}
     */
    protected static function buildScholarshipSnapshot(Student $student, float $netAmount): array
    {
        if (! $student->has_scholarship) {
            return [
                'original_fee_amount'         => $netAmount,
                'scholarship_discount_amount' => 0.0,
                'scholarship_name'            => null,
                'scholarship_percent'         => null,
            ];
        }

        if ($student->scholarship_type === 'percentage') {
            $percent  = min(99.99, (float) $student->scholarship_value);
            $original = $percent < 100 ? round($netAmount / (1 - $percent / 100), 2) : $netAmount;

            return [
                'original_fee_amount'         => $original,
                'scholarship_discount_amount' => round($original - $netAmount, 2),
                'scholarship_name'            => $student->scholarship_label,
                'scholarship_percent'         => $percent,
            ];
        }

        $value = (float) $student->scholarship_value;

        return [
            'original_fee_amount'         => round($netAmount + $value, 2),
            'scholarship_discount_amount' => $value,
            'scholarship_name'            => $student->scholarship_label,
            'scholarship_percent'         => null,
        ];
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

        if (filled($this->student->email)) {
            \Illuminate\Support\Facades\Mail::to($this->student->email)
                ->queue(new \App\Mail\FeePaymentReceiptMail($this));
        }
    }

    /**
     * How much of the applicable FeeStructure total for this student/period
     * has already been invoiced (i.e. a challan already exists for it,
     * whether paid or still pending), and how much remains available to
     * invoice via a new self-chosen-amount slip.
     *
     * @return array{total: float, already_invoiced: float, available: float, has_fee_structure: bool}
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
            'total'             => $total,
            'already_invoiced'  => $alreadyInvoiced,
            'available'         => max(0, round($total - $alreadyInvoiced, 2)),
            // Distinguishes "already fully invoiced" (a real FeeStructure exists,
            // total is just used up) from "nothing was ever configured" (no
            // FeeStructure row matches this program/semester/year at all) — the
            // two need very different admin-facing error messages.
            'has_fee_structure' => static::feeStructureExists($student, $feeType, $semester, $academicYearId),
        ];
    }

    /** Whether ANY active FeeStructure row matches this student/fee-type/period, regardless of amount. */
    protected static function feeStructureExists(Student $student, string $feeType, ?int $semester, ?int $academicYearId): bool
    {
        return FeeStructure::query()
            ->where('is_active', true)
            ->where('fee_type', $feeType)
            ->where(fn ($q) => $q->whereNull('academic_program_id')->orWhere('academic_program_id', $student->academic_program_id))
            ->where(fn ($q) => $q->whereNull('academic_year_id')->orWhere('academic_year_id', $academicYearId))
            ->where(fn ($q) => $q->whereNull('semester_number')->orWhere('semester_number', $semester))
            ->exists();
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
            if (! $summary['has_fee_structure']) {
                $feeTypeLabel = FeeTypeEnum::tryFrom($feeType)?->label() ?? $feeType;
                throw new \InvalidArgumentException(
                    "No active Fee Structure is configured for \"{$feeTypeLabel}\" for this student's program/semester/year — add one under Fee Structures before generating a challan."
                );
            }

            throw new \InvalidArgumentException(
                'This amount exceeds the remaining balance that can still be invoiced for this period (Rs. ' . number_format($summary['available']) . ' available).'
            );
        }

        // Cap self-chosen installments at 3 per (student, fee type, semester, year) —
        // applies to admin, portal, and public self-service alike, so a student can't
        // pile up unlimited unpaid slips. Pay/waive an existing one to free up a slot.
        $groupQuery = fn () => static::query()
            ->where('student_id', $student->id)
            ->where('fee_type', $feeType)
            ->when($semester, fn ($q) => $q->where('semester_number', $semester), fn ($q) => $q->whereNull('semester_number'))
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId), fn ($q) => $q->whereNull('academic_year_id'));

        $pendingCount = (int) $groupQuery()->where('payment_status', '!=', PaymentStatusEnum::Paid)->count();
        if ($pendingCount >= 3) {
            throw new \InvalidArgumentException(
                'This student already has 3 unpaid installments for this period. Pay or waive an existing one before generating another.'
            );
        }

        $installmentNo = (int) $groupQuery()->count() + 1;

        $structure = static::resolveFeeStructure($student, $feeType, $semester, $academicYearId);

        // The "available" ceiling checked above already deducts the
        // student's current scholarship (via invoiceSummary() →
        // resolveFeeStructureTotal() → applyScholarship()), so any slip
        // generated while a scholarship is active is scholarship-correct
        // by construction — this snapshot just makes that fact visible/
        // trackable instead of silently baked into a smaller number.
        $snapshot = static::buildScholarshipSnapshot($student, $amount);

        $slip = static::create([
            'student_id'        => $student->id,
            'fee_structure_id'  => $structure?->id,
            'academic_year_id'  => $academicYearId,
            'challan_number'    => 'CHN-' . strtoupper(Str::random(8)),
            'fee_type'          => $feeType,
            'semester_number'   => $semester,
            'amount_due'        => $amount,
            'amount_paid'       => 0,
            'fine_amount'       => 0,
            'manual_discount_amount'      => 0,
            'scholarship_discount_amount' => $snapshot['scholarship_discount_amount'],
            'original_fee_amount'         => $snapshot['original_fee_amount'],
            'scholarship_name'            => $snapshot['scholarship_name'],
            'scholarship_percent'         => $snapshot['scholarship_percent'],
            // amount_due above is already net of scholarship (see comment
            // above) — the snapshot is for display only and must not also be
            // subtracted via discount_amount, or the student would be
            // charged the scholarship discount twice.
            'scholarship_baked_into_amount_due' => true,
            'scholarship_applied' => $student->has_scholarship,
            'payment_status'    => PaymentStatusEnum::Pending,
            'due_date'          => $data['due_date'] ?? now()->addDays(15)->toDateString(),
            'installment_no'    => $installmentNo,
            'late_fine_per_day' => $structure?->late_fine_per_day ?? \App\Models\CollegeSetting::get('fee_late_fine_per_day'),
            'remarks'           => $data['remarks'] ?? null,
        ]);

        $slip->sendSlipGeneratedNotification();

        return $slip;
    }

    protected function sendSlipGeneratedNotification(): void
    {
        if (! $this->student) {
            return;
        }

        $feeType = $this->fee_type instanceof FeeTypeEnum ? $this->fee_type->label() : ($this->fee_type ?? 'Fee');

        app(NotificationService::class)->send($this->student, 'fee_slip_generated', [
            'student_name' => $this->student->name,
            'amount'       => number_format((float) $this->amount_due),
            'fee_type'     => $feeType,
            'challan'      => $this->challan_number,
            'due_date'     => optional($this->due_date)->format('d M Y') ?? 'N/A',
            'challan_id'   => (string) $this->id,
        ]);

        $this->notifyAdminsOfChallanGenerated($feeType);
    }

    /**
     * Admin-facing counterpart to the student email/in-app notification —
     * a bell notification so nobody has to remember to check Fee Payments
     * after generating challans. Clicking it opens this exact challan.
     */
    protected function notifyAdminsOfChallanGenerated(string $feeTypeLabel): void
    {
        $roles = \Spatie\Permission\Models\Role::whereIn('name', ['super_admin', 'Developer'])
            ->where('guard_name', 'web')->pluck('name')->all();
        $admins = $roles ? \App\Models\User::role($roles)->get() : collect();

        if ($admins->isEmpty()) {
            return;
        }

        \Filament\Notifications\Notification::make()
            ->title('Fee Challan Generated')
            ->body("{$this->student->name} — {$feeTypeLabel} — Rs. " . number_format((float) $this->amount_due) . " ({$this->challan_number})")
            ->icon('heroicon-o-document-text')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('View Challan')
                    ->button()
                    ->url(route('pdf.challan.preview', $this)),
            ])
            ->sendToDatabase($admins);
    }

    /**
     * Split a total into $count roughly-equal installment amounts (each
     * rounded to 2dp), with any rounding remainder absorbed by the last
     * installment so the parts always sum exactly back to $total.
     *
     * @return array<int,float>
     */
    public static function splitInstallments(float $total, int $count): array
    {
        $count = max(1, $count);
        $base  = round($total / $count, 2);
        $parts = array_fill(0, $count, $base);
        $parts[$count - 1] = round($total - $base * ($count - 1), 2);

        return $parts;
    }

    /**
     * Generate a full plan in one go: either a single slip for the whole
     * available amount, or several installment slips splitting it evenly,
     * each due a fixed number of days apart. Used by the admin-only slip
     * generators (Student Ledger, dept-wise bulk generator) — students no
     * longer have a self-service equivalent, admins decide the plan.
     *
     * @return array<int,self>
     */
    public static function generateInstallmentPlan(Student $student, array $data, int $installments, int $gapDays = 30): array
    {
        $amount = round((float) $data['amount'], 2);
        $parts  = static::splitInstallments($amount, $installments);

        $pendingCount = (int) static::query()
            ->where('student_id', $student->id)
            ->where('fee_type', $data['fee_type'])
            ->when($data['semester_number'] ?? null, fn ($q, $s) => $q->where('semester_number', $s), fn ($q) => $q->whereNull('semester_number'))
            ->when($data['academic_year_id'] ?? null, fn ($q, $y) => $q->where('academic_year_id', $y), fn ($q) => $q->whereNull('academic_year_id'))
            ->where('payment_status', '!=', PaymentStatusEnum::Paid)
            ->count();

        if ($pendingCount + $installments > 3) {
            throw new \InvalidArgumentException(
                'This plan would exceed the 3 unpaid installments allowed for this period (' . $pendingCount . ' already pending). Pay or waive an existing one, or choose fewer installments.'
            );
        }

        $firstDue = $data['due_date'] ?? now()->addDays(15)->toDateString();

        $slips = [];
        foreach ($parts as $i => $part) {
            $slips[] = static::generateSlip($student, array_merge($data, [
                'amount'   => $part,
                'due_date' => \Illuminate\Support\Carbon::parse($firstDue)->addDays($gapDays * $i)->toDateString(),
            ]));
        }

        return $slips;
    }

    protected static function resolveFeeStructureTotal(Student $student, string $feeType, ?int $semester, ?int $academicYearId): float
    {
        $baseTotal = (float) FeeStructure::query()
            ->where('is_active', true)
            ->where('fee_type', $feeType)
            ->where(fn ($q) => $q->whereNull('academic_program_id')->orWhere('academic_program_id', $student->academic_program_id))
            ->where(fn ($q) => $q->whereNull('academic_year_id')->orWhere('academic_year_id', $academicYearId))
            ->where(fn ($q) => $q->whereNull('semester_number')->orWhere('semester_number', $semester))
            ->sum('amount');

        return $student->applyScholarship($baseTotal);
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

    /**
     * Fix a single challan that predates the student's current scholarship
     * (or never had it applied for any other reason) — treats the challan's
     * current amount_due as the un-discounted flat amount, calculates what
     * the scholarship discount would be on that amount, and applies it via
     * discount_amount (never mutates amount_due, so this is always visible
     * and reversible, same as a manual "Apply Discount").
     *
     * @return float the discount amount applied (0 if nothing to reconcile)
     */
    public function reconcileScholarship(?int $actorId = null): float
    {
        if (! $this->student || ! $this->student->has_scholarship || $this->scholarship_applied) {
            return 0.0;
        }

        $before = (float) $this->discount_amount;
        $discount = round((float) $this->amount_due - $this->student->applyScholarship((float) $this->amount_due), 2);

        if ($discount <= 0) {
            $this->scholarship_applied = true;
            $this->save();
            return 0.0;
        }

        // amount_due here predates the scholarship and is therefore still the
        // true original flat amount — unlike a freshly generated slip, no
        // reconstruction is needed, this is exact.
        $this->original_fee_amount = $this->original_fee_amount ?? (float) $this->amount_due;
        $this->scholarship_discount_amount = (float) $this->scholarship_discount_amount + $discount;
        $this->scholarship_baked_into_amount_due = false;
        $this->scholarship_name = $this->student->scholarship_label;
        $this->scholarship_percent = $this->student->scholarship_type === 'percentage' ? (float) $this->student->scholarship_value : null;
        $this->scholarship_applied = true;
        $this->save();

        \App\Support\ActivityLogWriter::activity(
            'fee.scholarship_reconciled',
            subject: $this,
            message: "Applied Rs. " . number_format($discount) . " scholarship discount on challan {$this->challan_number} ({$this->student->scholarship_label}).",
            meta: ['before' => $before, 'after' => (float) $this->discount_amount, 'discount' => $discount],
            actor: $actorId ? \App\Models\User::find($actorId) : null,
        );

        return $discount;
    }

    /**
     * Reconcile every unpaid challan across all scholarship students that
     * hasn't had its scholarship applied yet — the bulk version of the
     * action above, for the "Reconcile Scholarships" admin action.
     *
     * @return array{count: int, total_discount: float}
     */
    public static function reconcilePendingScholarships(?int $actorId = null): array
    {
        $candidates = static::query()
            ->where('scholarship_applied', false)
            ->where('payment_status', '!=', PaymentStatusEnum::Paid->value)
            ->whereHas('student', fn ($q) => $q->whereNotNull('scholarship_type')->whereNotNull('scholarship_value'))
            ->with('student')
            ->get();

        $count = 0;
        $totalDiscount = 0.0;

        foreach ($candidates as $payment) {
            $discount = $payment->reconcileScholarship($actorId);
            if ($discount > 0) {
                $count++;
                $totalDiscount += $discount;
            }
        }

        return ['count' => $count, 'total_discount' => round($totalDiscount, 2)];
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

            // discount_amount is always the derived total of the manual
            // discount plus the scholarship discount — UNLESS the scholarship
            // discount is already baked into amount_due (a freshly generated
            // slip), in which case adding it again here would double it.
            // net_amount/balance keep reading discount_amount exactly as
            // before, so this is the only place that needs to know the split
            // exists.
            $payment->discount_amount = (float) $payment->manual_discount_amount
                + ($payment->scholarship_baked_into_amount_due ? 0.0 : (float) $payment->scholarship_discount_amount);
        });
    }
}
