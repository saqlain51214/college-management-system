<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WebsitePage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'content', 'meta_title', 'meta_description',
        'featured_image', 'sort_order', 'in_menu', 'is_published',
    ];

    protected $casts = [
        'in_menu'      => 'boolean',
        'is_published' => 'boolean',
    ];

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeInMenu($q)    { return $q->where('in_menu', true)->orderBy('sort_order'); }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
}
