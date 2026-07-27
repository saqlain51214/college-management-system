<?php

namespace App\Filament\Resources;

use App\Enums\RefundStatusEnum;
use App\Filament\Resources\FeeRefundResource\Pages;
use App\Models\FeePayment;
use App\Models\FeeRefund;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeeRefundResource extends Resource
{
    protected static ?string $model = FeeRefund::class;

    protected static ?string $navigationIcon  = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'Fees & Billing';
    protected static ?string $navigationLabel = 'Refunds';
    protected static ?int    $navigationSort  = 9;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Refund Details')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('student_id')
                        ->label('Student')
                        ->options(fn () => Student::where('is_active', true)->orderBy('name')
                            ->get()->mapWithKeys(fn ($s) => [$s->id => $s->roll_number . ' — ' . $s->name]))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->disabled(fn (?FeeRefund $record) => $record !== null),

                    Forms\Components\Select::make('fee_payment_id')
                        ->label('Related Challan (optional)')
                        ->options(fn (Forms\Get $get) => $get('student_id')
                            ? FeePayment::where('student_id', $get('student_id'))->orderByDesc('due_date')
                                ->get()->mapWithKeys(fn ($p) => [$p->id => $p->challan_number . ' — Rs. ' . number_format((float) $p->amount_due)])
                            : [])
                        ->searchable()
                        ->placeholder('Not tied to a specific challan'),

                    Forms\Components\TextInput::make('amount')
                        ->label('Refund Amount (PKR)')
                        ->numeric()->prefix('Rs.')->required()->minValue(1),

                    Forms\Components\Select::make('status')
                        ->options(RefundStatusEnum::options())
                        ->default(RefundStatusEnum::Pending->value)
                        ->required()
                        ->disabled(fn (?FeeRefund $record) => $record === null)
                        ->dehydrated(),

                    Forms\Components\Textarea::make('reason')
                        ->label('Reason (e.g. withdrawal, duplicate payment, security deposit)')
                        ->required()
                        ->rows(2)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('remarks')
                        ->label('Remarks')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.roll_number')->label('Roll No.')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('student.name')->label('Student')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('feePayment.challan_number')->label('Challan')->placeholder('—'),
                Tables\Columns\TextColumn::make('amount')->label('Amount')->money('PKR')->sortable(),
                Tables\Columns\TextColumn::make('reason')->wrap()->limit(40),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof RefundStatusEnum ? $state->label() : $state)
                    ->color(fn ($state) => $state instanceof RefundStatusEnum ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('requestedBy.name')->label('Requested By')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('approvedBy.name')->label('Approved By')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label('Requested')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(RefundStatusEnum::options()),
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (FeeRefund $r) => $r->status === RefundStatusEnum::Pending)
                    ->action(function (FeeRefund $r) {
                        $r->approve(auth()->id());
                        Notification::make()->title('Refund approved')->success()->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (FeeRefund $r) => $r->status === RefundStatusEnum::Pending)
                    ->form([
                        Forms\Components\Textarea::make('remarks')->label('Reason for rejection')->required()->rows(2),
                    ])
                    ->action(function (FeeRefund $r, array $data) {
                        $r->reject(auth()->id(), $data['remarks']);
                        Notification::make()->title('Refund rejected')->success()->send();
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn (FeeRefund $r) => $r->status === RefundStatusEnum::Pending),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (FeeRefund $r) => $r->status === RefundStatusEnum::Pending),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFeeRefunds::route('/'),
            'create' => Pages\CreateFeeRefund::route('/create'),
            'edit'   => Pages\EditFeeRefund::route('/{record}/edit'),
        ];
    }
}
