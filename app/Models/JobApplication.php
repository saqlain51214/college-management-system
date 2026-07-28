<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'position', 'name', 'email', 'phone', 'education', 'experience',
        'message', 'cv_path', 'status', 'is_read', 'admin_notes',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function getCvUrlAttribute(): ?string
    {
        return $this->cv_path ? asset('storage/' . $this->cv_path) : null;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'new'         => 'info',
            'reviewed'    => 'gray',
            'shortlisted' => 'warning',
            'hired'       => 'success',
            'rejected'    => 'danger',
            default       => 'gray',
        };
    }
}
