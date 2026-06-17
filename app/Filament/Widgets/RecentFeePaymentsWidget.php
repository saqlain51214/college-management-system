<?php

namespace App\Filament\Widgets;

use App\Models\FeePayment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentFeePaymentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Fee Payments';
    protected static ?int    $sort    = 6;
    protected int|string|array $columnSpan = 2;

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'Developer', 'panel_user']) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(FeePayment::with(['student', 'feeStructure'])->latest()->limit(8))
            ->columns([
                Tables\Columns\TextColumn::make('challan_number')
                    ->label('Challan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->limit(20),

                Tables\Columns\TextColumn::make('fee_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof \BackedEnum ? $state->label() : ucfirst($state))
                    ->color(fn($state): string => match($state instanceof \BackedEnum ? $state->value : $state) {
                        'tuition'   => 'primary',
                        'admission' => 'info',
                        'exam'      => 'warning',
                        'library'   => 'gray',
                        'sports'    => 'success',
                        'lab'       => 'danger',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('amount_due')
                    ->label('Amount')
                    ->money('PKR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof \BackedEnum ? $state->label() : ucfirst($state))
                    ->color(fn($state): string => match($state instanceof \BackedEnum ? $state->value : $state) {
                        'paid'    => 'success',
                        'overdue' => 'danger',
                        'pending' => 'warning',
                        'partial' => 'info',
                        'waived'  => 'gray',
                        default   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due')
                    ->date('d M Y'),
            ])
            ->paginated(false);
    }
}
