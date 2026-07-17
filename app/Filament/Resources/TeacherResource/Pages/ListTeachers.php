<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Actions\DownloadTeacherTemplateAction;
use App\Filament\Actions\ImportTeachersFromExcelAction;
use App\Filament\Resources\TeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListTeachers extends ListRecords
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()->exports([
                ExcelExport::make('teachers')
                    ->fromTable()
                    ->withFilename('teachers-' . date('Y-m-d'))
                    ->withColumns([
                        Column::make('employee_id')->heading('Employee ID'),
                        Column::make('name')->heading('Name'),
                        Column::make('department.name')->heading('Department'),
                        Column::make('designation')->heading('Designation'),
                        Column::make('qualification')->heading('Qualification'),
                        Column::make('specialization')->heading('Specialization'),
                        Column::make('employment_type')->heading('Employment Type'),
                        Column::make('status')->heading('Status'),
                        Column::make('phone')->heading('Phone'),
                        Column::make('email')->heading('Email'),
                        Column::make('joining_date')->heading('Joining Date'),
                    ]),
            ]),
            DownloadTeacherTemplateAction::make(),
            ImportTeachersFromExcelAction::make(),
            Actions\CreateAction::make(),
        ];
    }
}
