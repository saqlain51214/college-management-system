<?php

namespace App\Models;

use App\Enums\BookStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'department_id', 'accession_number', 'isbn', 'title', 'author',
        'publisher', 'publication_year', 'edition', 'language', 'total_copies',
        'available_copies', 'rack_location', 'category', 'subject', 'price',
        'status', 'cover_image', 'description', 'is_reference_only', 'is_active',
    ];

    protected $casts = [
        'status'           => BookStatusEnum::class,
        'price'            => 'decimal:2',
        'is_reference_only'=> 'boolean',
        'is_active'        => 'boolean',
    ];

    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function issues(): HasMany       { return $this->hasMany(BookIssue::class); }

    public function scopeActive($q)    { return $q->where('is_active', true); }
    public function scopeAvailable($q) { return $q->where('available_copies', '>', 0); }
}
