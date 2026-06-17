<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timetable extends Model
{
    protected $fillable = [
        'academic_program_id','semester','course_id','teacher_id',
        'day_of_week','start_time','end_time','room','is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function academicProgram(): BelongsTo { return $this->belongsTo(AcademicProgram::class); }
    public function course(): BelongsTo          { return $this->belongsTo(Course::class); }
    public function teacher(): BelongsTo         { return $this->belongsTo(Teacher::class); }

    public function scopeActive($query)          { return $query->where('is_active', true); }
    public function scopeForProgram($q, ?int $id) { return $id !== null ? $q->where('academic_program_id', $id) : $q; }
    public function scopeForSemester($q, int $s) { return $q->where('semester', $s); }

    public function getDayLabelAttribute(): string
    {
        return ucfirst($this->day_of_week);
    }

    public function getTimeRangeAttribute(): string
    {
        return substr($this->start_time, 0, 5) . ' - ' . substr($this->end_time, 0, 5);
    }
}
