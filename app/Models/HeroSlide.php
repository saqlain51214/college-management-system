<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    protected $fillable = [
        'image', 'title', 'description',
        'primary_btn_text', 'primary_btn_link',
        'secondary_btn_text', 'secondary_btn_link',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        // Slides migrated from the old WebsitePage JSON blob may still carry
        // a plain public-asset path (e.g. "assets/images/...") rather than a
        // storage-disk path — same convention already used by hero.blade.php
        // and WebsitePageResource for the legacy data.
        return str_starts_with($this->image, 'assets/') ? asset($this->image) : Storage::url($this->image);
    }
}
