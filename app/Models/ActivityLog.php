<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    use HasFactory;

    public const TYPE_ACTIVITY = 1;
    public const TYPE_AUTH = 2;
    public const TYPE_ERROR = 3;
    public const TYPE_SYSTEM = 4;

    public const LEVEL_INFO = 1;
    public const LEVEL_WARNING = 2;
    public const LEVEL_ERROR = 3;

    public $timestamps = false;

    protected $fillable = [
        'type',
        'level',
        'event',
        'actor_type',
        'actor_id',
        'subject_type',
        'subject_id',
        'message',
        'meta',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public static function typeOptions(): array
    {
        return [
            static::TYPE_ACTIVITY => 'Activity',
            static::TYPE_AUTH => 'Auth',
            static::TYPE_ERROR => 'Error',
            static::TYPE_SYSTEM => 'System',
        ];
    }

    public static function levelOptions(): array
    {
        return [
            static::LEVEL_INFO => 'Info',
            static::LEVEL_WARNING => 'Warning',
            static::LEVEL_ERROR => 'Error',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return static::typeOptions()[$this->type] ?? 'Unknown';
    }

    public function getLevelLabelAttribute(): string
    {
        return static::levelOptions()[$this->level] ?? 'Unknown';
    }

    public function getActorSummaryAttribute(): ?string
    {
        return $this->formatEntitySummary($this->actor_type, $this->actor_id);
    }

    public function getSubjectSummaryAttribute(): ?string
    {
        return $this->formatEntitySummary($this->subject_type, $this->subject_id);
    }

    public function typeColor(): string
    {
        return match ($this->type) {
            static::TYPE_ACTIVITY => 'primary',
            static::TYPE_AUTH => 'info',
            static::TYPE_ERROR => 'danger',
            static::TYPE_SYSTEM => 'gray',
            default => 'gray',
        };
    }

    public function levelColor(): string
    {
        return match ($this->level) {
            static::LEVEL_INFO => 'success',
            static::LEVEL_WARNING => 'warning',
            static::LEVEL_ERROR => 'danger',
            default => 'gray',
        };
    }

    private function formatEntitySummary(?string $type, ?int $id): ?string
    {
        if (blank($type) && blank($id)) {
            return null;
        }

        $label = filled($type) ? Str::headline(str_replace('.', ' ', $type)) : 'Record';

        return filled($id) ? "{$label} #{$id}" : $label;
    }
}
