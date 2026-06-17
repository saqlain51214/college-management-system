<?php

namespace App\Filament\Resources\FeePaymentResource\Pages;

use App\Filament\Resources\FeePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListFeePayments extends ListRecords
{
    protected static string $resource = FeePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
