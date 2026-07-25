<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TeacherSalaryPayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'teacher_id', 'reference_no', 'year', 'month',
        'basic_salary', 'allowances', 'deductions', 'net_amount', 'amount_paid',
        'payment_status', 'due_date', 'payment_date', 'payment_method',
        'paid_by', 'remarks',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances'   => 'decimal:2',
        'deductions'   => 'decimal:2',
        'net_amount'   => 'decimal:2',
        'amount_paid'  => 'decimal:2',
        'due_date'     => 'date',
        'payment_date' => 'date',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function getMonthLabelAttribute(): string
    {
        return \Illuminate\Support\Carbon::create($this->year, $this->month, 1)->format('F Y');
    }

    public function getBalanceAttribute(): float
    {
        return max(0, (float) $this->net_amount - (float) $this->amount_paid);
    }

    public function markAsPaid(?int $paidById = null, ?string $paymentDate = null, ?string $paymentMethod = null): void
    {
        $this->amount_paid = $this->net_amount;
        $this->payment_status = 'paid';
        $this->payment_date = $paymentDate ?: now()->toDateString();
        if ($paymentMethod) {
            $this->payment_method = $paymentMethod;
        }
        if ($paidById) {
            $this->paid_by = $paidById;
        }
        $this->save();
    }

    /**
     * Create (or return the existing) salary payment for a teacher for a given
     * month/year — one record per (teacher, year, month), so re-running this for
     * a period that's already generated never creates a duplicate.
     */
    public static function generateForMonth(Teacher $teacher, int $year, int $month, array $overrides = []): self
    {
        $existing = static::withTrashed()
            ->where('teacher_id', $teacher->id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($existing) {
            return $existing;
        }

        $basicSalary = (float) ($overrides['basic_salary'] ?? $teacher->basic_salary ?? 0);
        $allowances  = (float) ($overrides['allowances'] ?? 0);
        $deductions  = (float) ($overrides['deductions'] ?? 0);
        $netAmount   = round($basicSalary + $allowances - $deductions, 2);

        return static::create([
            'teacher_id'      => $teacher->id,
            'reference_no'    => 'SAL-' . strtoupper(Str::random(8)),
            'year'            => $year,
            'month'           => $month,
            'basic_salary'    => $basicSalary,
            'allowances'      => $allowances,
            'deductions'      => $deductions,
            'net_amount'      => $netAmount,
            'amount_paid'     => 0,
            'payment_status'  => 'pending',
            'due_date'        => $overrides['due_date'] ?? \Illuminate\Support\Carbon::create($year, $month, 1)->endOfMonth()->toDateString(),
            'remarks'         => $overrides['remarks'] ?? null,
        ]);
    }
}
