<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $fillable = [
        'title', 'employment_type', 'department', 'qualification',
        'description', 'closing_date', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'closing_date' => 'date',
        'is_active'    => 'boolean',
        'sort_order'   => 'integer',
    ];

    public static function employmentTypeOptions(): array
    {
        return [
            'full_time' => 'Full-time',
            'part_time' => 'Part-time',
            'contract'  => 'Contract',
            'visiting'  => 'Visiting',
        ];
    }

    public function getEmploymentTypeLabelAttribute(): string
    {
        return self::employmentTypeOptions()[$this->employment_type] ?? $this->employment_type;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('closing_date')->orWhere('closing_date', '>=', now()->toDateString()));
    }
}
