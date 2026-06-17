<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    protected $fillable = [
        'course_id', 'teacher_id', 'academic_program_id', 'semester_id',
        'session_date', 'start_time', 'end_time', 'semester_number',
        'section', 'topic_covered', 'remarks', 'is_locked',
    ];

    protected $casts = [
        'session_date' => 'date',
        'is_locked'    => 'boolean',
    ];

    public function course(): BelongsTo            { return $this->belongsTo(Course::class); }
    public function teacher(): BelongsTo           { return $this->belongsTo(Teacher::class); }
    public function academicProgram(): BelongsTo   { return $this->belongsTo(AcademicProgram::class); }
    public function semester(): BelongsTo          { return $this->belongsTo(Semester::class); }
    public function records(): HasMany             { return $this->hasMany(AttendanceRecord::class); }

    public function getPresentCountAttribute(): int
    {
        return $this->records()->where('status', 'present')->count();
    }

    public function getAbsentCountAttribute(): int
    {
        return $this->records()->where('status', 'absent')->count();
    }
}
