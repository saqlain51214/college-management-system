<?php

namespace App\Models;

use App\Enums\DepartmentTypeEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_urdu',
        'slug',
        'code',
        'type',
        'hod_name',
        'hod_designation',
        'hod_photo',
        'hod_message',
        'description',
        'vision',
        'mission',
        'banner_image',
        'email',
        'phone',
        'sort_order',
        'is_active',
        'show_on_website',
    ];

    protected $casts = [
        'type'            => DepartmentTypeEnum::class,
        'is_active'       => 'boolean',
        'show_on_website' => 'boolean',
        'sort_order'      => 'integer',
    ];

    // ─── Boot ────────────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $dept) {
            if (empty($dept->slug)) {
                $dept->slug = Str::slug($dept->name);
            }
        });

        // Delete images from storage when record is force deleted
        static::forceDeleting(function (self $dept) {
            if ($dept->hod_photo) {
                Storage::disk('public')->delete($dept->hod_photo);
            }
            if ($dept->banner_image) {
                Storage::disk('public')->delete($dept->banner_image);
            }
        });
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_active', true)->where('show_on_website', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getStatusAttribute(): StatusEnum
    {
        return $this->is_active ? StatusEnum::Active : StatusEnum::Inactive;
    }

    public function getHodPhotoUrlAttribute(): ?string
    {
        return $this->hod_photo ? asset('storage/' . $this->hod_photo) : null;
    }

    public function getBannerImageUrlAttribute(): ?string
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }
}
