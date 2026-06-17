<?php

namespace App\Enums;

enum DayOfWeekEnum: string
{
    case Monday    = 'monday';
    case Tuesday   = 'tuesday';
    case Wednesday = 'wednesday';
    case Thursday  = 'thursday';
    case Friday    = 'friday';
    case Saturday  = 'saturday';

    public function label(): string
    {
        return match($this) {
            self::Monday    => 'Monday',
            self::Tuesday   => 'Tuesday',
            self::Wednesday => 'Wednesday',
            self::Thursday  => 'Thursday',
            self::Friday    => 'Friday',
            self::Saturday  => 'Saturday',
        };
    }

    public function short(): string
    {
        return match($this) {
            self::Monday    => 'Mon',
            self::Tuesday   => 'Tue',
            self::Wednesday => 'Wed',
            self::Thursday  => 'Thu',
            self::Friday    => 'Fri',
            self::Saturday  => 'Sat',
        };
    }

    public function order(): int
    {
        return match($this) {
            self::Monday    => 1,
            self::Tuesday   => 2,
            self::Wednesday => 3,
            self::Thursday  => 4,
            self::Friday    => 5,
            self::Saturday  => 6,
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
