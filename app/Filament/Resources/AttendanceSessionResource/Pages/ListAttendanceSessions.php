<?php

namespace App\Filament\Resources\AttendanceSessionResource\Pages;

use App\Filament\Resources\AttendanceSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListAttendanceSessions extends ListRecords
{
    protected static string $resource = AttendanceSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()->exports([ExcelExport::make('attendance')->fromTable()->withFilename('attendance-sessions-' . date('Y-m-d'))]),
            Actions\CreateAction::make()->label('New Session'),
        ];
    }
}
