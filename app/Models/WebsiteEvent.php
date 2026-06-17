<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WebsiteEvent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by', 'title', 'slug', 'description', 'venue',
        'start_datetime', 'end_datetime', 'organizer', 'featured_image',
        'registration_link', 'is_published', 'is_featured',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime'   => 'datetime',
        'is_published'   => 'boolean',
        'is_featured'    => 'boolean',
    ];

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function scopePublished($q)  { return $q->where('is_published', true); }
    public function scopeUpcoming($q)   { return $q->where('start_datetime', '>=', now()); }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
}
