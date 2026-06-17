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
}
