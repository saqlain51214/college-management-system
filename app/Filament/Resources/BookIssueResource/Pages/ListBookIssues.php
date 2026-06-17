<?php

namespace App\Filament\Resources\BookIssueResource\Pages;

use App\Filament\Resources\BookIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListBookIssues extends ListRecords
{
    protected static string $resource = BookIssueResource::class;
    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()->exports([ExcelExport::make('book-issues')->fromTable()->withFilename('book-issues-' . date('Y-m-d'))]),
            Actions\CreateAction::make()->label('Issue Book'),
        ];
    }
}
