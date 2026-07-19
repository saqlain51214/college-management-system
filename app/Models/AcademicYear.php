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

    /**
     * Make sure a sensible set of academic years always exists so the
     * "Academic Year" dropdowns are never empty — self-heals regardless of
     * whether the data migration has run on this environment yet.
     */
    public static function ensureDefaults(): void
    {
        if (static::query()->exists()) {
            return;
        }

        $years = [
            ['name' => '2024-2025', 'start' => '2024-09-01', 'end' => '2025-08-31', 'current' => false],
            ['name' => '2025-2026', 'start' => '2025-09-01', 'end' => '2026-08-31', 'current' => true],
            ['name' => '2026-2027', 'start' => '2026-09-01', 'end' => '2027-08-31', 'current' => false],
        ];

        foreach ($years as $y) {
            static::updateOrCreate(
                ['name' => $y['name']],
                ['start_date' => $y['start'], 'end_date' => $y['end'], 'is_current' => $y['current'], 'is_active' => true],
            );
        }
    }

    /**
     * Options for a Filament Select — seeds defaults if the table is empty.
     */
    public static function selectOptions(): \Illuminate\Support\Collection
    {
        static::ensureDefaults();

        return static::orderByDesc('name')->pluck('name', 'id');
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getSemesterCountAttribute(): int
    {
        return $this->semesters()->count();
    }
}
