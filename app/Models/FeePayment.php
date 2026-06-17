<?php

namespace App\Models;

use App\Enums\FeeTypeEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id', 'fee_structure_id', 'academic_year_id', 'challan_number',
        'fee_type', 'semester_number', 'amount_due', 'amount_paid', 'fine_amount',
        'discount_amount', 'payment_status', 'payment_method', 'due_date',
        'payment_date', 'transaction_id', 'bank_name', 'remarks', 'collected_by',
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
        return $this->amount_due + $this->fine_amount - $this->discount_amount;
    }
}
