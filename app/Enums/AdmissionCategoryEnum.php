<?php

namespace App\Enums;

enum AdmissionCategoryEnum: string
{
    case Intermediate = 'intermediate';
    case Undergraduate = 'undergraduate';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Intermediate => 'Intermediate',
            self::Undergraduate => 'Undergraduate',
            self::Other => 'Other / Not for Online Admission',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Intermediate => 'For FA, FSc, ICS, I.Com and similar 11th/12th admission tracks.',
            self::Undergraduate => 'For BS, ADP and similar bachelor-level first year admissions.',
            self::Other => 'Keeps postgraduate, diploma, certificate or internal-only programmes out of this form.',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $category) => [$category->value => $category->label()])
            ->all();
    }
}
