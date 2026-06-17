<?php

namespace App\Enums;

enum DegreeTypeEnum: string
{
    case BS          = 'bs';
    case BEd         = 'bed';
    case MS          = 'ms';
    case MEd         = 'med';
    case MA          = 'ma';
    case MSc         = 'msc';
    case PhD         = 'phd';
    case Diploma     = 'diploma';
    case Certificate = 'certificate';

    public function label(): string
    {
        return match($this) {
            self::BS          => 'BS (Bachelor of Science)',
            self::BEd         => 'B.Ed (Bachelor of Education)',
            self::MS          => 'MS (Master of Science)',
            self::MEd         => 'M.Ed (Master of Education)',
            self::MA          => 'MA (Master of Arts)',
            self::MSc         => 'M.Sc (Master of Science)',
            self::PhD         => 'PhD',
            self::Diploma     => 'Diploma',
            self::Certificate => 'Certificate',
        };
    }

    public function shortLabel(): string
    {
        return match($this) {
            self::BS          => 'BS',
            self::BEd         => 'B.Ed',
            self::MS          => 'MS',
            self::MEd         => 'M.Ed',
            self::MA          => 'MA',
            self::MSc         => 'M.Sc',
            self::PhD         => 'PhD',
            self::Diploma     => 'Diploma',
            self::Certificate => 'Certificate',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::BS, self::BEd         => 'blue',
            self::MS, self::MEd         => 'violet',
            self::MA, self::MSc         => 'indigo',
            self::PhD                   => 'purple',
            self::Diploma               => 'amber',
            self::Certificate           => 'lime',
        };
    }

    public function defaultDuration(): int
    {
        return match($this) {
            self::BS, self::BEd         => 4,
            self::MS, self::MEd,
            self::MA, self::MSc         => 2,
            self::PhD                   => 3,
            self::Diploma               => 1,
            self::Certificate           => 1,
        };
    }

    public function defaultSemesters(): int
    {
        return $this->defaultDuration() * 2;
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($e) => [$e->value => $e->label()])
            ->all();
    }
}
