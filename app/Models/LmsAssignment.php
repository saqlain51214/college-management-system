<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsAssignment extends Model
{
    use SoftDeletes;

    protected $table = 'lms_assignments';

    protected $fillable = [
        'course_id', 'teacher_id', 'title', 'description', 'instructions',
        'total_marks', 'due_datetime', 'submission_type', 'attachment', 'reference_url',
        'allow_late_submission', 'is_published',
    ];

    protected $casts = [
        'total_marks'          => 'decimal:2',
        'due_datetime'         => 'datetime',
        'allow_late_submission'=> 'boolean',
        'is_published'         => 'boolean',
    ];

    public function course(): BelongsTo  { return $this->belongsTo(Course::class); }
    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); }

    public function submissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LmsSubmission::class, 'assignment_id');
    }

    public function submissionFor(int $studentId): ?LmsSubmission
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }

    public function scopePublished($q) { return $q->where('is_published', true); }

    public function isOverdue(): bool
    {
        return $this->due_datetime && $this->due_datetime->isPast();
    }
}
