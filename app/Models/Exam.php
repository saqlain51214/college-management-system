<?php

namespace App\Models;

use App\Enums\ExamTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'academic_program_id', 'academic_year_id', 'semester_id',
        'title', 'exam_type', 'semester_number', 'exam_date', 'start_time',
        'duration_minutes', 'total_marks', 'passing_marks', 'weightage_percent',
        'venue', 'is_published', 'results_published', 'instructions',
    ];

    protected $casts = [
        'exam_type'          => ExamTypeEnum::class,
        'exam_date'          => 'date',
        'total_marks'        => 'decimal:2',
        'passing_marks'      => 'decimal:2',
        'weightage_percent'  => 'decimal:2',
        'is_published'       => 'boolean',
        'results_published'  => 'boolean',
    ];

    public function course(): BelongsTo          { return $this->belongsTo(Course::class); }
    public function academicProgram(): BelongsTo { return $this->belongsTo(AcademicProgram::class); }
    public function academicYear(): BelongsTo    { return $this->belongsTo(AcademicYear::class); }
    public function semester(): BelongsTo        { return $this->belongsTo(Semester::class); }
    public function results(): HasMany           { return $this->hasMany(ExamResult::class); }

    public function scopePublished($q) { return $q->where('is_published', true); }
}
