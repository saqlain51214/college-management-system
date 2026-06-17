<?php

namespace App\Models;

use App\Enums\CourseTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'academic_program_id',
        'name',
        'name_urdu',
        'code',
        'slug',
        'course_type',
        'semester_number',
        'credit_hours',
        'theory_hours',
        'lab_hours',
        'contact_hours_per_week',
        'description',
        'objectives',
        'outcomes',
        'pre_requisites',
        'is_active',
        'show_on_website',
        'sort_order',
    ];

    protected $casts = [
        'course_type'           => CourseTypeEnum::class,
        'credit_hours'          => 'float',
        'theory_hours'          => 'float',
        'lab_hours'             => 'float',
        'is_active'             => 'boolean',
        'show_on_website'       => 'boolean',
        'sort_order'            => 'integer',
        'semester_number'       => 'integer',
        'contact_hours_per_week'=> 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->name);
            }
        });
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function academicProgram(): BelongsTo
    {
        return $this->belongsTo(AcademicProgram::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDepartment($query, int $deptId)
    {
        return $query->where('department_id', $deptId);
    }

    public function scopeForProgram($query, int $programId)
    {
        return $query->where('academic_program_id', $programId);
    }

    public function scopeForSemester($query, int $semNum)
    {
        return $query->where('semester_number', $semNum);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('semester_number')->orderBy('sort_order')->orderBy('code');
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getCreditSummaryAttribute(): string
    {
        $parts = [];
        if ($this->theory_hours) $parts[] = "T:{$this->theory_hours}";
        if ($this->lab_hours)    $parts[] = "L:{$this->lab_hours}";
        return count($parts) ? implode(' + ', $parts) : "{$this->credit_hours} cr";
    }
}
