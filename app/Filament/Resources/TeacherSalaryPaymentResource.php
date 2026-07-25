<?php

namespace App\Filament\Resources;

use App\Enums\PaymentMethodEnum;
use App\Filament\Resources\TeacherSalaryPaymentResource\Pages;
use App\Models\Teacher;
use App\Models\TeacherSalaryPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class TeacherSalaryPaymentResource extends Resource
{
    protected static ?string $model = TeacherSalaryPayment::class;
    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Teacher Salaries';
    protected static ?int    $navigationSort  = 15;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Salary Period')
                ->columns(3)
                ->schema([
                    Forms\Components\Select::make('teacher_id')
                        ->label('Teacher')
                        ->options(fn () => Teacher::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->disabled(fn (?TeacherSalaryPayment $record) => $record !== null),

                    Forms\Components\Select::make('month')
                        ->options(collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => Carbon::create(2000, $m, 1)->format('F')])->all())
                        ->required()
                        ->disabled(fn (?TeacherSalaryPayment $record) => $record !== null),

                    Forms\Components\TextInput::make('year')
                        ->numeric()
                        ->default(now()->year)
                        ->required()
                        ->disabled(fn (?TeacherSalaryPayment $record) => $record !== null),
                ]),

            Forms\Components\Section::make('Amount')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('basic_salary')
                        ->numeric()->prefix('Rs.')->required()->live()
                        ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => $set('net_amount',
                            round((float) $get('basic_salary') + (float) $get('allowances') - (float) $get('deductions'), 2))),

                    Forms\Components\TextInput::make('allowances')
                        ->numeric()->prefix('Rs.')->default(0)->live()
                        ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => $set('net_amount',
                            round((float) $get('basic_salary') + (float) $get('allowances') - (float) $get('deductions'), 2))),

                    Forms\Components\TextInput::make('deductions')
                        ->numeric()->prefix('Rs.')->default(0)->live()
                        ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => $set('net_amount',
                            round((float) $get('basic_salary') + (float) $get('allowances') - (float) $get('deductions'), 2))),

                    Forms\Components\TextInput::make('net_amount')
                        ->label('Net Payable')
                        ->numeric()->prefix('Rs.')->required()
                        ->helperText('Basic + Allowances − Deductions. Auto-calculated, but you can override it.'),

                    Forms\Components\DatePicker::make('due_date')
                        ->native(false)->displayFormat('d M Y'),
                ]),

            Forms\Components\Section::make('Payment')
                ->columns(3)
                ->schema([
                    Forms\Components\Select::make('payment_status')
                        ->options(['pending' => 'Pending', 'paid' => 'Paid'])
                        ->required()
                        ->default('pending'),

                    Forms\Components\TextInput::make('amount_paid')
                        ->numeric()->prefix('Rs.')->default(0),

                    Forms\Components\DatePicker::make('payment_date')
                        ->native(false)->displayFormat('d M Y'),

                    Forms\Components\Select::make('payment_method')
                        ->options(PaymentMethodEnum::options())
                        ->placeholder('Not paid yet'),

                    Forms\Components\Textarea::make('remarks')
                        ->columnSpanFull()
                        ->rows(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Teacher')
                    ->searchable()
                    ->sortable()
                    ->description(fn (TeacherSalaryPayment $r) => $r->teacher?->employee_id),

                Tables\Columns\TextColumn::make('month_label')
                    ->label('Period')
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('year', $direction)->orderBy('month', $direction)),

                Tables\Columns\TextColumn::make('net_amount')
                    ->label('Net Amount')
                    ->money('PKR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount_paid')
                    ->label('Paid')
                    ->money('PKR')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance')
                    ->money('PKR')
                    ->color(fn (TeacherSalaryPayment $r) => $r->balance > 0 ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->color(fn (string $state) => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('due_date')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Paid On')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('teacher_id')
                    ->label('Teacher')
                    ->relationship('teacher', 'name')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid']),

                Tables\Filters\SelectFilter::make('month')
                    ->options(collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => Carbon::create(2000, $m, 1)->format('F')])->all()),

                Tables\Filters\SelectFilter::make('year')
                    ->options(collect(range(now()->year - 2, now()->year + 1))->mapWithKeys(fn ($y) => [$y => $y])->all()),
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\Action::make('markPaid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (TeacherSalaryPayment $r) => $r->payment_status !== 'paid')
                    ->form([
                        Forms\Components\DatePicker::make('payment_date')
                            ->default(now())->native(false)->displayFormat('d M Y')->required(),
                        Forms\Components\Select::make('payment_method')
                            ->options(PaymentMethodEnum::options())->required(),
                    ])
                    ->action(function (TeacherSalaryPayment $r, array $data) {
                        $r->markAsPaid(auth()->id(), $data['payment_date'], $data['payment_method']);
                        Notification::make()->title('Salary marked as paid')->success()->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('due_date', 'desc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTeacherSalaryPayments::route('/'),
            'create' => Pages\CreateTeacherSalaryPayment::route('/create'),
            'edit'   => Pages\EditTeacherSalaryPayment::route('/{record}/edit'),
        ];
    }
}
