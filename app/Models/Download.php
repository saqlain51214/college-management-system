<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'original_filename',
        'file_type',
        'category',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeAttribute(): string
    {
        try {
            $bytes = Storage::size($this->file_path);
            $units = ['B', 'KB', 'MB', 'GB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return round($bytes, 2) . ' ' . $units[$i];
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}
