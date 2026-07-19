<?php

namespace App\Filament\Resources\FeePaymentResource\Pages;

use App\Enums\FeeTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Filament\Resources\FeePaymentResource;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\FeePayment;
use App\Models\Student;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListFeePayments extends ListRecords
{
    protected static string $resource = FeePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // One-click bulk create — a challan for every active student in a department.
            Actions\Action::make('generateDeptChallans')
                ->label('Generate Dept-wise Challans')
                ->icon('heroicon-o-document-plus')
                ->color('success')
                ->modalHeading('Generate Fee Challans — Department-wise')
                ->modalDescription('Creates a fee challan for every active student in the selected department (and programme, if chosen). Students who already have an unpaid challan for the same fee type & semester are skipped to avoid duplicates.')
                ->modalSubmitActionLabel('Generate Challans')
                ->form([
                    Forms\Components\Select::make('department_id')
                        ->label('Department')
                        ->options(fn () => Department::orderBy('name')->pluck('name', 'id'))
                        ->searchable()->required()->live(),
                    Forms\Components\Select::make('academic_program_id')
                        ->label('Programme (optional — leave blank for whole department)')
                        ->options(fn (Forms\Get $get) => AcademicProgram::query()
                            ->when($get('department_id'), fn ($q, $d) => $q->where('department_id', $d))
                            ->orderBy('name')->pluck('name', 'id'))
                        ->searchable(),
                    Forms\Components\Select::make('fee_type')
                        ->label('Fee Type')->options(FeeTypeEnum::options())
                        ->default(FeeTypeEnum::Tuition->value)->required(),
                    Forms\Components\TextInput::make('amount_due')
                        ->label('Amount (Rs.)')->numeric()->prefix('Rs.')->minValue(1)->required()
                        ->placeholder('e.g. 28000'),
                    Forms\Components\Select::make('semester_number')
                        ->label('Semester')
                        ->options(collect(range(1, 8))->mapWithKeys(fn ($n) => [$n => "Semester $n"])->all())
                        ->placeholder('Not applicable'),
                    Forms\Components\DatePicker::make('due_date')
                        ->label('Due Date')->required()->native(false)->displayFormat('d M Y'),
                    Forms\Components\Select::make('academic_year_id')
                        ->label('Academic Year')
                        ->options(fn () => AcademicYear::selectOptions())->searchable(),
                    Forms\Components\TextInput::make('remarks')
                        ->label('Remarks (optional)')->placeholder('e.g. Fall 2025 Semester Fee'),
                ])
                ->action(function (array $data) {
                    $students = Student::query()
                        ->where('is_active', true)
                        ->where('department_id', $data['department_id'])
                        ->when($data['academic_program_id'] ?? null, fn ($q, $p) => $q->where('academic_program_id', $p))
                        ->get();

                    $created = 0;
                    $skipped = 0;

                    foreach ($students as $student) {
                        $exists = FeePayment::where('student_id', $student->id)
                            ->where('fee_type', $data['fee_type'])
                            ->when($data['semester_number'] ?? null, fn ($q, $s) => $q->where('semester_number', $s))
                            ->where('payment_status', '!=', PaymentStatusEnum::Paid->value)
                            ->exists();

                        if ($exists) {
                            $skipped++;
                            continue;
                        }

                        FeePayment::create([
                            'student_id'       => $student->id,
                            'challan_number'   => 'CHN-' . strtoupper(Str::random(8)),
                            'fee_type'         => $data['fee_type'],
                            'amount_due'       => $data['amount_due'],
                            'fine_amount'      => 0,
                            'discount_amount'  => 0,
                            'amount_paid'      => 0,
                            'payment_status'   => PaymentStatusEnum::Pending->value,
                            'due_date'         => $data['due_date'],
                            'semester_number'  => $data['semester_number'] ?? null,
                            'academic_year_id' => $data['academic_year_id'] ?? null,
                            'remarks'          => $data['remarks'] ?? null,
                        ]);
                        $created++;
                    }

                    Notification::make()
                        ->title("Generated {$created} challan(s)" . ($skipped ? " — {$skipped} skipped (already had an unpaid challan)" : ''))
                        ->success()->send();
                }),

            ExportAction::make()->exports([
                ExcelExport::make('fee-payments')
                    ->fromTable()
                    ->withFilename('fee-payments-' . date('Y-m-d'))
                    ->withColumns([
                        Column::make('challan_number')->heading('Challan No'),
                        Column::make('student.name')->heading('Student'),
                        Column::make('student.roll_number')->heading('Roll No'),
                        Column::make('fee_type')->heading('Fee Type'),
                        Column::make('semester_number')->heading('Semester'),
                        Column::make('amount_due')->heading('Amount Due (PKR)'),
                        Column::make('amount_paid')->heading('Amount Paid (PKR)'),
                        Column::make('fine_amount')->heading('Fine (PKR)'),
                        Column::make('payment_status')->heading('Status'),
                        Column::make('payment_method')->heading('Method'),
                        Column::make('due_date')->heading('Due Date'),
                        Column::make('payment_date')->heading('Payment Date'),
                    ]),
            ]),
            Actions\CreateAction::make(),
        ];
    }
}
