<?php

namespace App\Filament\Resources;

use App\Enums\FeeTypeEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Filament\Resources\FeePaymentResource\Pages;
use App\Models\AcademicYear;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Services\NotificationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FeePaymentResource extends Resource
{
    protected static ?string $model = FeePayment::class;

    protected static ?string $navigationIcon  = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Fee Payments';
    protected static ?int    $navigationSort  = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Challan / Payment Details')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('student_id')
                        ->label('Student')
                        ->options(fn() => Student::where('is_active', true)->orderBy('name')
                            ->get()->mapWithKeys(fn($s) => [$s->id => $s->roll_number . ' â€” ' . $s->name]))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\TextInput::make('challan_number')
                        ->label('Challan Number')
                        ->required()
                        ->unique(table: 'fee_payments', column: 'challan_number',
                            modifyRuleUsing: fn(\Illuminate\Validation\Rules\Unique $rule, ?FeePayment $record) =>
                                $record ? $rule->ignore($record->id) : $rule
                        )
                        ->default(fn() => 'CHN-' . strtoupper(Str::random(8)))
                        ->maxLength(50),

                    Forms\Components\Select::make('fee_type')
                        ->label('Fee Type')
                        ->options(FeeTypeEnum::options())
                        ->default(FeeTypeEnum::Tuition->value)
                        ->required(),

                    Forms\Components\Select::make('semester_number')
                        ->label('Semester')
                        ->options(collect(range(1, 8))->mapWithKeys(fn($n) => [$n => "Semester $n"])->all())
                        ->placeholder('N/A'),

                    Forms\Components\Select::make('academic_year_id')
                        ->label('Academic Year')
                        ->options(fn() => AcademicYear::selectOptions())
                        ->searchable(),

                    Forms\Components\Select::make('fee_structure_id')
                        ->label('Fee Structure (Optional)')
                        ->options(fn() => FeeStructure::active()->pluck('title', 'id'))
                        ->searchable()
                        ->placeholder('Select if applicable'),

                    Forms\Components\TextInput::make('amount_due')->label('Amount Due (PKR)')->numeric()->required()->prefix('Rs.'),
                    Forms\Components\TextInput::make('amount_paid')->label('Amount Paid (PKR)')->numeric()->default(0)->prefix('Rs.'),
                    Forms\Components\TextInput::make('fine_amount')->label('Late Fine (PKR)')->numeric()->default(0)->prefix('Rs.'),
                    Forms\Components\TextInput::make('discount_amount')->label('Discount (PKR)')->numeric()->default(0)->prefix('Rs.'),

                    Forms\Components\Select::make('payment_status')
                        ->label('Payment Status')
                        ->options(PaymentStatusEnum::options())
                        ->default(PaymentStatusEnum::Pending->value)
                        ->required(),

                    Forms\Components\Select::make('payment_method')
                        ->label('Payment Method')
                        ->options(PaymentMethodEnum::options())
                        ->placeholder('Select Method'),

                    Forms\Components\DatePicker::make('due_date')->label('Due Date')->displayFormat('d M Y')->native(false),
                    Forms\Components\DatePicker::make('payment_date')->label('Payment Date')->displayFormat('d M Y')->native(false),

                    Forms\Components\TextInput::make('transaction_id')->label('Transaction / Reference ID')->maxLength(100)->placeholder('Bank reference / transaction number'),
                    Forms\Components\TextInput::make('bank_name')->label('Bank Name')->maxLength(100)->placeholder('e.g. HBL, MCB'),

                    Forms\Components\Textarea::make('remarks')->label('Remarks')->rows(2)->columnSpanFull(),

                    Forms\Components\FileUpload::make('payment_proof_path')
                        ->label('Payment Proof')
                        ->disk('public')
                        ->directory('payment-proofs')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull()
                        ->helperText('Student-uploaded bank receipt or payment proof'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('challan_number')->label('Challan No.')->searchable()->badge()->color('gray'),
                Tables\Columns\TextColumn::make('receipt_number')->label('Receipt No.')->searchable()->badge()->color('success')->placeholder('—')->toggleable(),
                Tables\Columns\ImageColumn::make('payment_proof_path')
                    ->label('Proof')
                    ->disk('public')
                    ->circular(false)
                    ->width(40)
                    ->height(30)
                    ->defaultImageUrl(null)
                    ->toggleable()
                    ->tooltip('Payment proof uploaded by student'),
                Tables\Columns\TextColumn::make('student.roll_number')->label('Roll No.')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('student.name')->label('Student')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('fee_type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof FeeTypeEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof FeeTypeEnum ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('semester_number')->label('Sem')->prefix('S')->placeholder('â€”'),
                Tables\Columns\TextColumn::make('amount_due')->label('Due')->money('PKR')->sortable(),
                Tables\Columns\TextColumn::make('amount_paid')->label('Paid')->money('PKR')->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof PaymentStatusEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof PaymentStatusEnum ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('due_date')->label('Due Date')->date('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_date')->label('Paid On')->date('d M Y')->sortable()->placeholder('â€”'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department')
                    ->label('Department')
                    ->options(fn () => \App\Models\Department::orderBy('name')->pluck('name', 'id')->all())
                    ->query(fn ($query, array $data) => filled($data['value'] ?? null)
                        ? $query->whereHas('student', fn ($q) => $q->where('department_id', $data['value']))
                        : $query),
                Tables\Filters\SelectFilter::make('program')
                    ->label('Program')
                    ->options(fn () => \App\Models\AcademicProgram::orderBy('name')->pluck('name', 'id')->all())
                    ->query(fn ($query, array $data) => filled($data['value'] ?? null)
                        ? $query->whereHas('student', fn ($q) => $q->where('academic_program_id', $data['value']))
                        : $query),
                Tables\Filters\SelectFilter::make('payment_status')->options(PaymentStatusEnum::options()),
                Tables\Filters\SelectFilter::make('fee_type')->options(FeeTypeEnum::options()),
                Tables\Filters\Filter::make('has_proof')
                    ->label('Has Payment Proof')
                    ->query(fn($query) => $query->whereNotNull('payment_proof_path')),
                Tables\Filters\Filter::make('proof_pending_verification')
                    ->label('Proof — Awaiting Verification')
                    ->query(fn($query) => $query->whereNotNull('payment_proof_path')
                        ->where('payment_status', '!=', PaymentStatusEnum::Paid->value)),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn(FeePayment $r) => ! $r->isLocked() || (auth()->user()?->hasRole('super_admin') ?? false)),
                Tables\Actions\Action::make('previewChallan')
                    ->label('Preview Challan')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->iconButton()
                    ->url(fn(FeePayment $r) => route('pdf.challan.preview', $r))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('printChallan')
                    ->label('Download PDF')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->iconButton()
                    ->url(fn(FeePayment $r) => route('pdf.challan', $r))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('viewProof')
                    ->label('View Proof')
                    ->icon('heroicon-o-paper-clip')
                    ->color('warning')
                    ->iconButton()
                    ->visible(fn(FeePayment $r) => !empty($r->payment_proof_path))
                    ->url(fn(FeePayment $r) => asset('storage/' . $r->payment_proof_path))
                    ->openUrlInNewTab()
                    ->tooltip('View student-uploaded payment proof'),

                Tables\Actions\Action::make('markPaid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Paid')
                    ->modalDescription('This will mark the fee as paid and notify the student.')
                    ->visible(fn(FeePayment $r) => $r->payment_status !== PaymentStatusEnum::Paid)
                    ->action(function (FeePayment $r) {
                        $r->markAsPaid(auth()->id());
                        if ($r->student) {
                            $feeType = $r->fee_type instanceof FeeTypeEnum ? $r->fee_type->label() : ($r->fee_type ?? 'Fee');
                            app(NotificationService::class)->send($r->student, 'fee_payment_confirmed', [
                                'student_name' => $r->student->name,
                                'amount'       => number_format((float) $r->amount_due),
                                'fee_type'     => $feeType,
                                'payment_date' => now()->format('d M Y'),
                            ]);
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(FeePayment $r) => ! $r->isLocked()),
                Tables\Actions\ForceDeleteAction::make()
                    ->visible(fn(FeePayment $r) => ! $r->isLocked()),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\BulkAction::make('bulkMarkPaid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Mark Selected as Paid')
                    ->modalDescription('This will mark all selected fee payments as Paid and set today as payment date.')
                    ->action(function (\Illuminate\Support\Collection $records) {
                        $records->each(function (FeePayment $r) {
                            if ($r->payment_status !== PaymentStatusEnum::Paid) {
                                $r->markAsPaid(auth()->id());
                            }
                        });
                        \Filament\Notifications\Notification::make()
                            ->title($records->count() . ' payments marked as paid.')
                            ->success()->send();
                    }),
                Tables\Actions\BulkAction::make('downloadChallans')
                    ->label('Download Challans (1 PDF)')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->modalHeading('Generate Combined Challan PDF')
                    ->modalDescription('All selected students\' fee challans are combined into a single PDF for one-click printing. Tip: filter by Department first, then Select All.')
                    ->modalSubmitActionLabel('Download PDF')
                    ->action(function (\Illuminate\Support\Collection $records) {
                        if ($records->count() > 200) {
                            \Filament\Notifications\Notification::make()
                                ->title('Too many at once')
                                ->body('Please select 200 or fewer challans (e.g. one department at a time) to avoid timeouts.')
                                ->warning()->send();
                            return;
                        }
                        $pdf = app(\App\Http\Controllers\PdfController::class)->bulkChallansPdf($records);
                        return response()->streamDownload(
                            fn () => print($pdf),
                            'challans-' . now()->format('Y-m-d-His') . '.pdf',
                            ['Content-Type' => 'application/pdf'],
                        );
                    })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\DeleteBulkAction::make(),
            ])])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            return (string) FeePayment::where('payment_status', PaymentStatusEnum::Overdue->value)->count() ?: null;
        } catch (\Exception) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): ?string { return 'danger'; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFeePayments::route('/'),
            'create' => Pages\CreateFeePayment::route('/create'),
            'edit'   => Pages\EditFeePayment::route('/{record}/edit'),
        ];
    }
}
