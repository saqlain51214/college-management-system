<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsQuery extends Model
{
    protected $table = 'lms_queries';

    protected $fillable = [
        'student_id', 'teacher_id', 'assignment_id', 'submission_id',
        'type', 'subject', 'message', 'status',
        'reply', 'replied_at', 'replied_by',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function assignment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LmsAssignment::class, 'assignment_id');
    }

    public function submission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LmsSubmission::class, 'submission_id');
    }

    public function repliedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'replied_by');
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'grade_dispute'        => 'Grade Dispute',
            'deadline_extension'   => 'Deadline Extension Request',
            'assignment_question'  => 'Assignment Question',
            default                => 'General Query',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'replied'  => '#f59e0b',
            'resolved' => '#22c55e',
            default    => '#6b7280',
        };
    }
}
