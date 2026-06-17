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
        'total_marks', 'due_datetime', 'submission_type', 'attachment',
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

    public function scopePublished($q) { return $q->where('is_published', true); }
}
