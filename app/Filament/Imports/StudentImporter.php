<?php

namespace App\Filament\Imports;

use App\Models\AcademicProgram;
use App\Models\Student;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Str;

class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Full Name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:200'])
                ->example('Ali Raza'),

            ImportColumn::make('father_name')
                ->label("Father's Name")
                ->rules(['nullable', 'string', 'max:200'])
                ->example('Muhammad Raza'),

            ImportColumn::make('registration_number')
                ->label('Registration Number')
                ->rules(['nullable', 'string', 'max:100'])
                ->example('2024-KIU-ADP-3048'),

            ImportColumn::make('roll_number')
                ->label('Roll Number / Reg No')
                ->rules(['nullable', 'string', 'max:100'])
                ->example('JDCA-2026-0001'),

            ImportColumn::make('batch_year')
                ->label('Session / Batch')
                ->rules(['nullable', 'string', 'max:50'])
                ->example('2024-2026'),

            ImportColumn::make('phone')
                ->label('Phone')
                ->rules(['nullable', 'string', 'max:30'])
                ->example('+92300000000'),

            ImportColumn::make('gender')
                ->label('Gender')
                ->rules(['nullable', 'string'])
                ->example('Male'),

            ImportColumn::make('cnic')
                ->label('CNIC')
                ->rules(['nullable', 'string', 'max:20'])
                ->example('71601-0000000-9'),

            ImportColumn::make('city')
                ->label('City')
                ->rules(['nullable', 'string', 'max:100'])
                ->example('Astore'),

            ImportColumn::make('address')
                ->label('Address')
                ->rules(['nullable', 'string'])
                ->example('Village ABC, Astore'),

            ImportColumn::make('current_semester')
                ->label('Current Semester')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:1', 'max:8'])
                ->example('1'),

            ImportColumn::make('remarks')
                ->label('Remarks / Notes')
                ->rules(['nullable', 'string'])
                ->example(''),
        ];
    }

    public function resolveRecord(): ?Student
    {
        // Try to find by registration_number or roll_number first (update existing)
        if (!empty($this->data['registration_number'])) {
            $student = Student::where('registration_number', $this->data['registration_number'])->first();
            if ($student) return $student;
        }
        if (!empty($this->data['roll_number'])) {
            $student = Student::where('roll_number', $this->data['roll_number'])->first();
            if ($student) return $student;
        }

        // Generate a roll number if not provided
        if (empty($this->data['roll_number'])) {
            $this->data['roll_number'] = 'JDCA-' . date('Y') . '-' . str_pad(Student::count() + 1, 4, '0', STR_PAD_LEFT);
        }

        return new Student();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your student import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';
        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }
        return $body;
    }
}
