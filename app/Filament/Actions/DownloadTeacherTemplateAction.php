<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class DownloadTeacherTemplateAction extends Action
{
    public static function make(?string $name = 'downloadTemplate'): static
    {
        return parent::make($name)
            ->label('Download Template')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(function () {
                $headers = [
                    'NAME',
                    'FATHER NAME',
                    'EMPLOYEE ID',
                    'CNIC',
                    'GENDER',
                    'PHONE',
                    'EMAIL',
                    'DEPARTMENT',
                    'DESIGNATION',
                    'QUALIFICATION',
                    'SPECIALIZATION',
                    'EMPLOYMENT TYPE',
                    'JOINING DATE',
                    'CITY',
                    'ADDRESS',
                    'REMARKS',
                ];

                $examples = [
                    ['Muhammad Bilal', 'Ghulam Nabi', 'JDCA-T-001', '71601-1234567-9', 'male', '+923001234567', 'bilal@example.com', 'Computer Science', 'Lecturer', 'MS Computer Science', 'Software Engineering', 'permanent', '2021-08-15', 'Astore', 'Village ABC, Astore', ''],
                    ['Ayesha Khan', 'Abdul Rahman', 'JDCA-T-002', '71601-9876543-1', 'female', '+923009876543', 'ayesha@example.com', 'Education', 'Assistant Professor', 'M.Ed', 'Curriculum Studies', 'visiting', '2022-09-01', 'Gilgit', 'Village XYZ, Gilgit', ''],
                ];

                $output = fopen('php://temp', 'r+');
                fputcsv($output, $headers);
                foreach ($examples as $row) {
                    fputcsv($output, $row);
                }
                rewind($output);
                $csv = stream_get_contents($output);
                fclose($output);

                return response()->streamDownload(
                    fn() => print($csv),
                    'teachers-import-template.csv',
                    ['Content-Type' => 'text/csv']
                );
            });
    }
}
