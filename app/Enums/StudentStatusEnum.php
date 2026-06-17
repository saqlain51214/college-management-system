<?php

namespace App\Enums;

enum StudentStatusEnum: string
{
    case Active      = 'active';
    case Inactive    = 'inactive';
    case Graduated   = 'graduated';
    case Dropped     = 'dropped';
    case Expelled    = 'expelled';
    case Transferred = 'transferred';
    case OnLeave     = 'on_leave';

    public function label(): string
    {
        return match($this) {
            self::Active      => 'Active',
            self::Inactive    => 'Inactive',
            self::Graduated   => 'Graduated',
            self::Dropped     => 'Dropped Out',
            self::Expelled    => 'Expelled',
            self::Transferred => 'Transferred',
            self::OnLeave     => 'On Leave',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active      => 'success',
            self::Inactive    => 'gray',
            self::Graduated   => 'info',
            self::Dropped     => 'danger',
            self::Expelled    => 'danger',
            self::Transferred => 'warning',
            self::OnLeave     => 'warning',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Active      => 'heroicon-o-check-circle',
            self::Inactive    => 'heroicon-o-x-circle',
            self::Graduated   => 'heroicon-o-academic-cap',
            self::Dropped     => 'heroicon-o-arrow-uturn-left',
            self::Expelled    => 'heroicon-o-no-symbol',
            self::Transferred => 'heroicon-o-arrow-right-circle',
            self::OnLeave     => 'heroicon-o-pause-circle',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
