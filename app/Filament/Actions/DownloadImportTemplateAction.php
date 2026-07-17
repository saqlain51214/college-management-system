<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class DownloadImportTemplateAction extends Action
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
                    'REGISTRATION NUMBER',
                    'ROLL NUMBER',
                    'DEG. PROGRAM',
                    'SESSION',
                    'GENDER',
                    'PHONE',
                    'CNIC',
                    'CITY',
                    'ADDRESS',
                    'SEMESTER',
                    'REMARKS',
                ];

                $examples = [
                    ['Ali Raza', 'Muhammad Raza', '2024-KIU-ADP-3048', 'JDCA-2024-0001', 'ADP Computer Science', '2024', 'male', '+923001234567', '71601-1234567-9', 'Astore', 'Village ABC, Astore', '1', ''],
                    ['Fatima Bibi', 'Ghulam Hussain', '2024-KIU-ADP-3049', 'JDCA-2024-0002', 'BS English', '2024', 'female', '+923009876543', '71601-9876543-1', 'Gilgit', 'Village XYZ, Gilgit', '1', ''],
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
                    'students-import-template.csv',
                    ['Content-Type' => 'text/csv']
                );
            });
    }
}
