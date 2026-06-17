<?php

namespace App\Models;

use App\Enums\ScholarshipTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scholarship extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'scholarship_type', 'description', 'eligibility_criteria',
        'amount', 'coverage_percent', 'funding_source', 'seats',
        'application_start', 'application_end', 'is_recurring', 'is_active',
    ];

    protected $casts = [
        'scholarship_type' => ScholarshipTypeEnum::class,
        'application_start'=> 'date',
        'application_end'  => 'date',
        'amount'           => 'decimal:2',
        'coverage_percent' => 'decimal:2',
        'is_recurring'     => 'boolean',
        'is_active'        => 'boolean',
    ];

    public function awards(): HasMany { return $this->hasMany(ScholarshipAward::class); }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
