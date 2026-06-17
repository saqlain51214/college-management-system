<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsMaterial extends Model
{
    use SoftDeletes;

    protected $table = 'lms_materials';

    protected $fillable = [
        'course_id', 'teacher_id', 'title', 'description', 'material_type',
        'file_path', 'external_url', 'week_number', 'is_published', 'download_count',
    ];

    protected $casts = [
        'is_published'   => 'boolean',
        'download_count' => 'integer',
    ];

    public function course(): BelongsTo  { return $this->belongsTo(Course::class); }
    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); }

    public function scopePublished($q) { return $q->where('is_published', true); }
}
