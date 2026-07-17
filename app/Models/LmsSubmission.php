<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsSubmission extends Model
{
    protected $fillable = [
        'assignment_id', 'student_id', 'status',
        'file_path', 'text_content', 'submitted_url',
        'submitted_at', 'marks_obtained', 'feedback',
        'graded_at', 'graded_by',
    ];

    protected $casts = [
        'submitted_at'   => 'datetime',
        'graded_at'      => 'datetime',
        'marks_obtained' => 'decimal:2',
    ];

    public function assignment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LmsAssignment::class, 'assignment_id');
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function gradedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'graded_by');
    }

    public function isSubmitted(): bool
    {
        return in_array($this->status, ['submitted', 'late', 'graded']);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'submitted' => 'Submitted',
            'late'      => 'Late Submission',
            'graded'    => 'Graded',
            default     => 'Pending',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'submitted' => '#2563eb',
            'late'      => '#d97706',
            'graded'    => '#16a34a',
            default     => '#6b7280',
        };
    }
}
