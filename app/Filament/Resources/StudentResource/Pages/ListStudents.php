<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Actions\DownloadImportTemplateAction;
use App\Filament\Actions\ImportStudentsFromExcelAction;
use App\Filament\Resources\StudentResource;
use App\Models\Student;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    /**
     * Visible filter tabs at the top of the Students page so staff can quickly
     * switch between all / unique / flagged-duplicate / missing-number students.
     */
    public function getTabs(): array
    {
        $missingReg  = fn (Builder $q) => $q->whereNull('registration_number')->orWhere('registration_number', '');
        $missingRoll = fn (Builder $q) => $q->whereNull('roll_number')->orWhere('roll_number', '');
        $isDuplicate = fn (Builder $q) => $q->where('remarks', 'like', '%Duplicate%');

        return [
            'all' => Tab::make('All')
                ->badge(Student::count()),

            'unique' => Tab::make('Unique')
                ->modifyQueryUsing(fn (Builder $q) => $q
                    ->whereNotNull('registration_number')->where('registration_number', '!=', '')
                    ->where(fn (Builder $x) => $x->whereNull('remarks')->orWhere('remarks', 'not like', '%Duplicate%')))
                ->badge(Student::whereNotNull('registration_number')->where('registration_number', '!=', '')
                    ->where(fn (Builder $x) => $x->whereNull('remarks')->orWhere('remarks', 'not like', '%Duplicate%'))->count()),

            'duplicates' => Tab::make('Duplicates')
                ->modifyQueryUsing($isDuplicate)
                ->badge(Student::where('remarks', 'like', '%Duplicate%')->count())
                ->badgeColor('warning'),

            'no_registration' => Tab::make('No Registration #')
                ->modifyQueryUsing(fn (Builder $q) => $q->where($missingReg))
                ->badge(Student::where($missingReg)->count())
                ->badgeColor('danger'),

            'no_roll' => Tab::make('No Roll #')
                ->modifyQueryUsing(fn (Builder $q) => $q->where($missingRoll))
                ->badge(Student::where($missingRoll)->count())
                ->badgeColor('danger'),
        ];
    }

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
            DownloadImportTemplateAction::make(),
            ImportStudentsFromExcelAction::make(),
            Actions\CreateAction::make(),
        ];
    }
}
