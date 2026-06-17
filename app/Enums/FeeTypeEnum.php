<?php

namespace App\Enums;

enum FeeTypeEnum: string
{
    case Tuition   = 'tuition';
    case Admission = 'admission';
    case Exam      = 'exam';
    case Library   = 'library';
    case Sports    = 'sports';
    case Lab       = 'lab';
    case Hostel    = 'hostel';
    case Transport = 'transport';
    case Other     = 'other';

    public function label(): string
    {
        return match($this) {
            self::Tuition   => 'Tuition Fee',
            self::Admission => 'Admission Fee',
            self::Exam      => 'Examination Fee',
            self::Library   => 'Library Fee',
            self::Sports    => 'Sports Fee',
            self::Lab       => 'Lab Fee',
            self::Hostel    => 'Hostel Fee',
            self::Transport => 'Transport Fee',
            self::Other     => 'Other',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Tuition   => 'primary',
            self::Admission => 'info',
            self::Exam      => 'warning',
            self::Library   => 'success',
            self::Sports    => 'success',
            self::Lab       => 'info',
            self::Hostel    => 'gray',
            self::Transport => 'gray',
            self::Other     => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
