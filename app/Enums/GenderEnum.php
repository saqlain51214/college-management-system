<?php

namespace App\Enums;

enum GenderEnum: string
{
    case Male   = 'male';
    case Female = 'female';
    case Other  = 'other';

    public function label(): string
    {
        return match($this) {
            self::Male   => 'Male',
            self::Female => 'Female',
            self::Other  => 'Other',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Male   => 'blue',
            self::Female => 'pink',
            self::Other  => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
