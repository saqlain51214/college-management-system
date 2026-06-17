<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookIssue extends Model
{
    protected $fillable = [
        'book_id', 'student_id', 'teacher_id', 'issue_date', 'due_date',
        'return_date', 'fine_amount', 'fine_paid', 'condition_on_issue',
        'condition_on_return', 'issued_by', 'remarks',
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
        'fine_amount' => 'decimal:2',
        'fine_paid'   => 'boolean',
    ];

    public function book(): BelongsTo    { return $this->belongsTo(Book::class); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); }

    public function scopeActive($q)    { return $q->whereNull('return_date'); }
    public function scopeOverdue($q)   { return $q->whereNull('return_date')->where('due_date', '<', now()); }

    public function getIsOverdueAttribute(): bool
    {
        return is_null($this->return_date) && $this->due_date < now()->toDateString();
    }

    public function getDaysOverdueAttribute(): int
    {
        if (! $this->is_overdue) {
            return 0;
        }

        return now()->diffInDays($this->due_date);
    }
}
