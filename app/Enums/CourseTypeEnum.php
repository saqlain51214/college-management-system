<?php

namespace App\Enums;

enum CourseTypeEnum: string
{
    case Core       = 'core';
    case Elective   = 'elective';
    case Lab        = 'lab';
    case Seminar    = 'seminar';
    case Internship = 'internship';
    case Project    = 'project';
    case Thesis     = 'thesis';

    public function label(): string
    {
        return match($this) {
            self::Core       => 'Core',
            self::Elective   => 'Elective',
            self::Lab        => 'Lab',
            self::Seminar    => 'Seminar',
            self::Internship => 'Internship',
            self::Project    => 'Project',
            self::Thesis     => 'Thesis',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Core       => 'blue',
            self::Elective   => 'violet',
            self::Lab        => 'cyan',
            self::Seminar    => 'indigo',
            self::Internship => 'amber',
            self::Project    => 'orange',
            self::Thesis     => 'rose',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($e) => [$e->value => $e->label()])
            ->all();
    }
}
