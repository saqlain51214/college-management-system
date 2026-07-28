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
            $student = $award->student;
            if (! $student) {
                return;
            }

            if (in_array($award->status, [ScholarshipStatusEnum::Approved, ScholarshipStatusEnum::Disbursed], true)) {
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

                return;
            }

            // Rejected/expired: if no OTHER currently-active award remains for
            // this student, clear the flat scholarship fields so future
            // challans stop getting a discount for an award that no longer
            // applies. Historical challans already generated keep their own
            // snapshot (original_fee_amount/scholarship_discount_amount) and
            // are unaffected either way.
            if (in_array($award->status, [ScholarshipStatusEnum::Rejected, ScholarshipStatusEnum::Expired], true)) {
                $stillActive = static::query()
                    ->where('student_id', $student->id)
                    ->where('id', '!=', $award->id)
                    ->whereIn('status', [ScholarshipStatusEnum::Approved, ScholarshipStatusEnum::Disbursed])
                    ->where(fn ($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()->toDateString()))
                    ->exists();

                if (! $stillActive) {
                    $student->update(['scholarship_type' => null, 'scholarship_value' => null]);
                }
            }
        });
    }
}
