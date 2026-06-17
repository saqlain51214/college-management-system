<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by', 'department_id', 'title', 'content', 'audience',
        'priority', 'publish_date', 'expiry_date', 'is_published',
        'send_email', 'send_sms', 'attachment',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expiry_date'  => 'date',
        'is_published' => 'boolean',
        'send_email'   => 'boolean',
        'send_sms'     => 'boolean',
    ];

    public function creator(): BelongsTo    { return $this->belongsTo(User::class, 'created_by'); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeActive($q)
    {
        return $q->where('is_published', true)
            ->where(fn($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()->toDateString()));
    }
}
