<?php

namespace App\Enums;

enum SemesterTypeEnum: string
{
    case Fall   = 'fall';
    case Spring = 'spring';
    case Summer = 'summer';

    public function label(): string
    {
        return match($this) {
            self::Fall   => 'Fall',
            self::Spring => 'Spring',
            self::Summer => 'Summer',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Fall   => 'orange',
            self::Spring => 'green',
            self::Summer => 'yellow',
        };
    }

    public function months(): string
    {
        return match($this) {
            self::Fall   => 'Aug – Dec',
            self::Spring => 'Jan – May',
            self::Summer => 'Jun – Jul',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($e) => [$e->value => $e->label() . ' (' . $e->months() . ')'])
            ->all();
    }
}
