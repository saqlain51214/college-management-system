<?php

namespace App\Enums;

enum ExamTypeEnum: string
{
    case Midterm    = 'midterm';
    case Final      = 'final';
    case Quiz       = 'quiz';
    case Assignment = 'assignment';
    case Lab        = 'lab';
    case Viva       = 'viva';
    case Practical  = 'practical';

    public function label(): string
    {
        return match($this) {
            self::Midterm    => 'Midterm Exam',
            self::Final      => 'Final Exam',
            self::Quiz       => 'Quiz',
            self::Assignment => 'Assignment',
            self::Lab        => 'Lab Exam',
            self::Viva       => 'Viva / Oral',
            self::Practical  => 'Practical',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Midterm    => 'warning',
            self::Final      => 'danger',
            self::Quiz       => 'info',
            self::Assignment => 'success',
            self::Lab        => 'primary',
            self::Viva       => 'gray',
            self::Practical  => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($e) => [$e->value => $e->label()])->all();
    }
}
