<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class CollegeHelper
{
    public static function generateSlug(string $name): string
    {
        return Str::slug($name);
    }

    public static function generateCode(string $name, string $prefix = ''): string
    {
        $words   = explode(' ', strtoupper($name));
        $acronym = implode('', array_map(fn($w) => substr($w, 0, 1), $words));

        return $prefix ? "{$prefix}-{$acronym}" : $acronym;
    }

    public static function formatPhone(string $phone): string
    {
        return preg_replace('/[^0-9+]/', '', $phone);
    }

    public static function formatCnic(string $cnic): string
    {
        $digits = preg_replace('/[^0-9]/', '', $cnic);

        if (strlen($digits) === 13) {
            return substr($digits, 0, 5) . '-' . substr($digits, 5, 7) . '-' . substr($digits, 12, 1);
        }

        return $cnic;
    }

    public static function gradeFromPercentage(float $percentage): array
    {
        return match(true) {
            $percentage >= 85 => ['grade' => 'A',  'points' => 4.00, 'label' => 'Excellent'],
            $percentage >= 80 => ['grade' => 'A-', 'points' => 3.67, 'label' => 'Very Good'],
            $percentage >= 75 => ['grade' => 'B+', 'points' => 3.33, 'label' => 'Good Plus'],
            $percentage >= 70 => ['grade' => 'B',  'points' => 3.00, 'label' => 'Good'],
            $percentage >= 65 => ['grade' => 'B-', 'points' => 2.67, 'label' => 'Satisfactory'],
            $percentage >= 61 => ['grade' => 'C+', 'points' => 2.33, 'label' => 'Average Plus'],
            $percentage >= 57 => ['grade' => 'C',  'points' => 2.00, 'label' => 'Average'],
            $percentage >= 53 => ['grade' => 'C-', 'points' => 1.67, 'label' => 'Below Average'],
            $percentage >= 50 => ['grade' => 'D+', 'points' => 1.33, 'label' => 'Poor Plus'],
            $percentage >= 45 => ['grade' => 'D',  'points' => 1.00, 'label' => 'Poor'],
            default           => ['grade' => 'F',  'points' => 0.00, 'label' => 'Fail'],
        };
    }

    public static function cgpaToPercentage(float $cgpa): float
    {
        return round($cgpa * 25, 2);
    }

    public static function calculateGpa(array $courses): float
    {
        $totalPoints = 0;
        $totalHours  = 0;

        foreach ($courses as $course) {
            $totalPoints += ($course['grade_points'] * $course['credit_hours']);
            $totalHours  += $course['credit_hours'];
        }

        return $totalHours > 0 ? round($totalPoints / $totalHours, 2) : 0.00;
    }

    public static function generateRollNumber(string $deptCode, int $year, int $sequence): string
    {
        return sprintf('%s-%d-%04d', strtoupper($deptCode), $year, $sequence);
    }
}
