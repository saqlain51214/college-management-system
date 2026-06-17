<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()->exports([
                ExcelExport::make('students')
                    ->fromTable()
                    ->withFilename('students-' . date('Y-m-d'))
                    ->withColumns([
                        Column::make('roll_number')->heading('Roll No'),
                        Column::make('name')->heading('Student Name'),
                        Column::make('father_name')->heading("Father's Name"),
                        Column::make('department.name')->heading('Department'),
                        Column::make('academicProgram.name')->heading('Program'),
                        Column::make('batch_year')->heading('Batch'),
                        Column::make('current_semester')->heading('Semester'),
                        Column::make('status')->heading('Status'),
                        Column::make('phone')->heading('Phone'),
                        Column::make('email')->heading('Email'),
                        Column::make('admission_date')->heading('Admission Date'),
                    ]),
            ]),
            Actions\CreateAction::make(),
        ];
    }
}
