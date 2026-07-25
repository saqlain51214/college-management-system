<?php

namespace App\Models;

use App\Enums\ScholarshipStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipAward extends Model
{
    protected $fillable = [
        'scholarship_id', 'student_id', 'academic_year_id', 'status',
        'amount_awarded', 'application_date', 'approval_date',
        'disbursement_date', 'expiry_date', 'reason', 'remarks', 'approved_by',
    ];

    protected $casts = [
        'status'            => ScholarshipStatusEnum::class,
        'amount_awarded'    => 'decimal:2',
        'application_date'  => 'date',
        'approval_date'     => 'date',
        'disbursement_date' => 'date',
        'expiry_date'       => 'date',
    ];

    public function scholarship(): BelongsTo  { return $this->belongsTo(Scholarship::class); }
    public function student(): BelongsTo      { return $this->belongsTo(Student::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function approver(): BelongsTo     { return $this->belongsTo(User::class, 'approved_by'); }

    /**
     * Once an award is approved or disbursed, push its value onto the
     * student's own scholarship fields — that's the single field every fee
     * calculation actually reads, so approving an award here is enough to
     * make it take effect everywhere without re-entering it by hand.
     */
    protected static function booted(): void
    {
        static::saved(function (ScholarshipAward $award) {
            if (! in_array($award->status, [ScholarshipStatusEnum::Approved, ScholarshipStatusEnum::Disbursed], true)) {
                return;
            }

            $student = $award->student;
            if (! $student) {
                return;
            }

            $scholarship = $award->scholarship;
            if ($scholarship && filled($scholarship->coverage_percent)) {
                $student->update([
                    'scholarship_type'  => 'percentage',
                    'scholarship_value' => $scholarship->coverage_percent,
                ]);
            } else {
                $student->update([
                    'scholarship_type'  => 'fixed',
                    'scholarship_value' => $award->amount_awarded,
                ]);
            }
        });
    }
}
