<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseOutline extends Model
{
    protected $fillable = [
        'department_id', 'academic_program_id', 'semester_number',
        'title', 'file_path', 'external_url', 'description', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'semester_number' => 'integer',
        'sort_order'      => 'integer',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function academicProgram(): BelongsTo
    {
        return $this->belongsTo(AcademicProgram::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Public URL to download/open the outline (uploaded file or external link). */
    public function getUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }

        return $this->external_url ?: null;
    }
}
