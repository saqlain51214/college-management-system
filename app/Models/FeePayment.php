<?php

namespace App\Models;

use App\Enums\FeeTypeEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
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
    ];

    protected $casts = [
        'fee_type'       => FeeTypeEnum::class,
        'payment_status' => PaymentStatusEnum::class,
        'payment_method' => PaymentMethodEnum::class,
        'due_date'       => 'date',
        'payment_date'   => 'date',
        'amount_due'     => 'decimal:2',
        'amount_paid'    => 'decimal:2',
        'fine_amount'    => 'decimal:2',
        'discount_amount'=> 'decimal:2',
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
     * True once the fee is fully paid — such records are locked from deletion.
     */
    public function isLocked(): bool
    {
        return $this->payment_status === PaymentStatusEnum::Paid;
    }

    /**
     * Single source of truth for settling a challan: pay the full net amount
     * (due + fine − discount), stamp the date, record who collected it, and
     * issue a receipt number.
     */
    public function markAsPaid(?int $collectorId = null): void
    {
        $this->amount_paid    = $this->net_amount;
        $this->payment_status = PaymentStatusEnum::Paid;
        $this->payment_date   = now()->toDateString();

        if ($collectorId) {
            $this->collected_by = $collectorId;
        }

        $this->save();
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
