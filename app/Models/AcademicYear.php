<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
        'is_active',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_current' => 'boolean',
        'is_active'  => 'boolean',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class)->orderBy('sort_order');
    }

    public function activeSemesters(): HasMany
    {
        return $this->hasMany(Semester::class)->where('is_active', true)->orderBy('sort_order');
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
        return static::where('is_current', true)->first();
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getSemesterCountAttribute(): int
    {
        return $this->semesters()->count();
    }
}
