<?php

namespace App\Models;

use App\Enums\SemesterTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'name',
        'type',
        'number',
        'start_date',
        'end_date',
        'registration_start',
        'registration_end',
        'exam_start',
        'exam_end',
        'is_current',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'type'               => SemesterTypeEnum::class,
        'start_date'         => 'date',
        'end_date'           => 'date',
        'registration_start' => 'date',
        'registration_end'   => 'date',
        'exam_start'         => 'date',
        'exam_end'           => 'date',
        'is_current'         => 'boolean',
        'is_active'          => 'boolean',
        'sort_order'         => 'integer',
        'number'             => 'integer',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    // ─── Static Helpers ──────────────────────────────────────────────────────

    public static function getCurrent(): ?self
    {
        return static::with('academicYear')->where('is_current', true)->first();
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return $this->name . ' — ' . ($this->academicYear?->name ?? '');
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->is_current) return 'Current';
        if (! $this->is_active) return 'Inactive';
        return 'Active';
    }
}
