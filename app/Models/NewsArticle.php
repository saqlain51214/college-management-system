<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class NewsArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by', 'title', 'slug', 'excerpt', 'content', 'category',
        'featured_image', 'published_date', 'is_published', 'is_featured', 'views',
    ];

    protected $casts = [
        'published_date' => 'date',
        'is_published'   => 'boolean',
        'is_featured'    => 'boolean',
        'views'          => 'integer',
    ];

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
}
