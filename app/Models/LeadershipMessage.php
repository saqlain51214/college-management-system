<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadershipMessage extends Model
{
    protected $fillable = [
        'name', 'designation', 'organization', 'message', 'photo', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }
}
