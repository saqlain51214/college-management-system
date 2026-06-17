<?php

namespace App\Models;

use App\Enums\FeeTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeStructure extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'academic_program_id', 'academic_year_id', 'title', 'fee_type',
        'semester_number', 'amount', 'late_fine_per_day', 'due_date',
        'frequency', 'is_mandatory', 'is_active', 'description',
    ];

    protected $casts = [
        'fee_type'       => FeeTypeEnum::class,
        'due_date'       => 'date',
        'amount'         => 'decimal:2',
        'late_fine_per_day' => 'decimal:2',
        'is_mandatory'   => 'boolean',
        'is_active'      => 'boolean',
    ];

    public function academicProgram(): BelongsTo { return $this->belongsTo(AcademicProgram::class); }
    public function academicYear(): BelongsTo    { return $this->belongsTo(AcademicYear::class); }
    public function payments(): HasMany          { return $this->hasMany(FeePayment::class); }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
