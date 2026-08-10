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
    protected static ?string $navigationGroup = 'Fees & Billing';
    protected static ?string $navigationLabel = 'Fee Payments';
    protected static ?int    $navigationSort  = 4;
    protected static ?string $recordTitleAttribute = 'challan_number';

    /** Lets admins ⌘K-search a challan/receipt directly, or by student name/roll. */
    public static function getGloballySearchableAttributes(): array
    {
        return ['challan_number', 'receipt_number', 'student.name', 'student.roll_number'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Challan / Payment Details')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('student_id')
                        ->label('Student')
                        ->options(fn() => Student::where('is_active', true)->orderBy('name')
                            ->get()->mapWithKeys(fn($s) => [$s->id => $s->roll_number . ' — ' . $s->name]))
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

                    Forms\Components\TextInput::make('amount_due')
                        ->label('Amount Due (PKR)')->numeric()->required()->prefix('Rs.')
                        ->helperText('Locked once any payment is recorded — generate a new challan instead of retyping this.')
                        ->disabled(fn (?FeePayment $record) => $record && ((float) $record->amount_paid > 0 || $record->payment_status !== PaymentStatusEnum::Pending))
                        ->dehydrated(),
                    Forms\Components\TextInput::make('amount_paid')->label('Amount Paid (PKR)')->numeric()->default(0)->prefix('Rs.'),
                    Forms\Components\TextInput::make('fine_amount')
                        ->label('Late Fine (PKR)')->numeric()->default(0)->prefix('Rs.')
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Read-only — use the "Waive Late Fee" action on the table to adjust this with a recorded reason.'),
                    Forms\Components\TextInput::make('discount_amount')
                        ->label('Discount (PKR)')->numeric()->default(0)->prefix('Rs.')
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Read-only — use the "Apply Discount" action on the table to adjust this with a recorded reason.'),

                    Forms\Components\Select::make('payment_status')
                        ->label('Payment Status')
                        ->options(PaymentStatusEnum::options())
                        ->default(PaymentStatusEnum::Pending->value)
                        ->required()
                        ->disabled(fn (?FeePayment $record) => $record && $record->payment_status === PaymentStatusEnum::Paid)
                        ->dehydrated(),

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
                        ->maxSize(5120)
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull()
                        ->helperText('Student-uploaded bank receipt or payment proof (max 5 MB)'),
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
                Tables\Columns\TextColumn::make('student.registration_number')
                    ->label('Reg No.')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('student.roll_number')
                    ->label('Roll No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable()
                    ->wrap()
                    ->description(fn (FeePayment $r) => $r->student?->scholarship_label ? '🎓 ' . $r->student->scholarship_label : null),
                Tables\Columns\TextColumn::make('scholarship_applied')
                    ->label('Scholarship')
                    ->badge()
                    ->formatStateUsing(fn (bool $state, FeePayment $r) => match (true) {
                        ! $r->student?->has_scholarship => '—',
                        $state => 'Applied',
                        default => 'Not Applied',
                    })
                    ->color(fn (bool $state, FeePayment $r) => match (true) {
                        ! $r->student?->has_scholarship => 'gray',
                        $state => 'success',
                        default => 'warning',
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('fee_type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof FeeTypeEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof FeeTypeEnum ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('semester_number')->label('Sem')->prefix('S')->placeholder('—'),
                Tables\Columns\TextColumn::make('installment_no')
                    ->label('Installment')
                    ->formatStateUsing(fn (FeePayment $r) => 'S' . ($r->semester_number ?? '—') . ' · #' . $r->installment_no)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount_due')->label('Due')->money('PKR')->sortable()
                    ->description(function (FeePayment $r) {
                        $b = $r->fee_breakdown;
                        if ($b['scholarship_discount'] <= 0 && $b['manual_discount'] <= 0) {
                            return null;
                        }
                        $parts = ['Original: Rs. ' . number_format($b['original_fee'])];
                        if ($b['scholarship_discount'] > 0) {
                            $parts[] = '🎓 -Rs. ' . number_format($b['scholarship_discount']) . ($b['scholarship_percent'] ? ' (' . rtrim(rtrim(number_format($b['scholarship_percent'], 2), '0'), '.') . '%)' : '');
                        }
                        if ($b['manual_discount'] > 0) {
                            $parts[] = 'Discount: -Rs. ' . number_format($b['manual_discount']);
                        }
                        return implode(' · ', $parts);
                    }),
                Tables\Columns\TextColumn::make('amount_paid')->label('Paid')->money('PKR')->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance')
                    ->state(fn (FeePayment $r) => $r->balance)
                    ->money('PKR')
                    ->color(fn (FeePayment $r) => $r->balance > 0 ? 'danger' : 'success')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof PaymentStatusEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof PaymentStatusEnum ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('due_date')->label('Due Date')->date('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_date')->label('Paid On')->date('d M Y')->sortable()->placeholder('—'),
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
                Tables\Filters\TernaryFilter::make('has_scholarship')
                    ->label('Scholarship')
                    ->placeholder('All students')
                    ->trueLabel('Scholarship students only')
                    ->falseLabel('No scholarship')
                    ->queries(
                        true: fn ($query) => $query->whereHas('student', fn ($q) => $q->whereNotNull('scholarship_type')->whereNotNull('scholarship_value')),
                        false: fn ($query) => $query->whereHas('student', fn ($q) => $q->whereNull('scholarship_type')->orWhereNull('scholarship_value')),
                    ),
                Tables\Filters\SelectFilter::make('payment_status')->options(PaymentStatusEnum::options()),
                Tables\Filters\SelectFilter::make('fee_type')->options(FeeTypeEnum::options()),
                Tables\Filters\SelectFilter::make('academic_year_id')
                    ->label('Academic Year')
                    ->options(fn () => AcademicYear::selectOptions()),
                Tables\Filters\SelectFilter::make('due_month')
                    ->label('Month (Due Date)')
                    ->options(function () {
                        $months = [];
                        $cursor = now()->startOfMonth();
                        for ($i = 0; $i < 24; $i++) {
                            $months[$cursor->format('Y-m')] = $cursor->format('F Y');
                            $cursor->subMonth();
                        }
                        return $months;
                    })
                    ->query(function ($query, array $data) {
                        if (blank($data['value'] ?? null)) {
                            return $query;
                        }
                        [$year, $month] = explode('-', $data['value']);
                        return $query->whereYear('due_date', $year)->whereMonth('due_date', $month);
                    }),
                Tables\Filters\Filter::make('has_proof')
                    ->label('Has Payment Proof')
                    ->query(fn($query) => $query->whereNotNull('payment_proof_path')),
                Tables\Filters\Filter::make('proof_pending_verification')
                    ->label('Proof — Awaiting Verification')
                    ->query(fn($query) => $query->whereNotNull('payment_proof_path')
                        ->where('payment_status', '!=', PaymentStatusEnum::Paid->value)),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordUrl(null)
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
                ...self::proofReviewActions(),

                Tables\Actions\Action::make('markPaid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Paid')
                    ->modalDescription('This will mark the fee as paid and notify the student.')
                    ->visible(fn(FeePayment $r) => $r->payment_status !== PaymentStatusEnum::Paid)
                    ->action(fn (FeePayment $r) => $r->markAsPaid(auth()->id())),

                Tables\Actions\Action::make('waiveFine')
                    ->label('Waive Late Fee')
                    ->icon('heroicon-o-receipt-refund')
                    ->color('warning')
                    ->iconButton()
                    ->tooltip('Waive Late Fee')
                    ->visible(fn (FeePayment $r) => (float) $r->fine_amount > 0 && $r->payment_status !== PaymentStatusEnum::Paid)
                    ->form(fn (FeePayment $r) => [
                        Forms\Components\TextInput::make('waived_amount')
                            ->label('Amount to Waive (PKR)')
                            ->numeric()->prefix('Rs.')->required()
                            ->default(fn () => (float) $r->fine_amount)
                            ->maxValue(fn () => (float) $r->fine_amount),
                        Forms\Components\Textarea::make('reason')
                            ->label('Reason')->required()->rows(2),
                    ])
                    ->action(function (FeePayment $r, array $data) {
                        $before = (float) $r->fine_amount;
                        $waived = (float) $data['waived_amount'];
                        $r->fine_amount = max(0, round($before - $waived, 2));
                        $r->save();

                        \App\Support\ActivityLogWriter::activity(
                            'fee.fine_waived',
                            subject: $r,
                            message: "Waived Rs. " . number_format($waived) . " late fee on challan {$r->challan_number}. Reason: {$data['reason']}",
                            meta: ['before' => $before, 'after' => (float) $r->fine_amount, 'waived' => $waived, 'reason' => $data['reason']],
                        );

                        \Filament\Notifications\Notification::make()->title('Late fee waived')->success()->send();
                    }),

                Tables\Actions\Action::make('applyDiscount')
                    ->label('Apply Discount')
                    ->icon('heroicon-o-tag')
                    ->color('info')
                    ->iconButton()
                    ->tooltip('Apply Discount')
                    ->visible(fn (FeePayment $r) => $r->payment_status !== PaymentStatusEnum::Paid)
                    ->form(fn (FeePayment $r) => [
                        Forms\Components\TextInput::make('manual_discount_amount')
                            ->label('Additional Discount (PKR)')
                            ->helperText('This is on top of any scholarship discount already applied — Rs. ' . number_format((float) $r->scholarship_discount_amount) . ' scholarship discount is tracked separately and untouched here.')
                            ->numeric()->prefix('Rs.')->required()
                            ->default(fn () => (float) $r->manual_discount_amount)
                            ->maxValue(fn () => (float) $r->amount_due - (float) $r->scholarship_discount_amount),
                        Forms\Components\Textarea::make('reason')
                            ->label('Reason')->required()->rows(2),
                    ])
                    ->action(function (FeePayment $r, array $data) {
                        $before = (float) $r->manual_discount_amount;
                        $after  = (float) $data['manual_discount_amount'];
                        $r->manual_discount_amount = $after;
                        $r->save();

                        \App\Support\ActivityLogWriter::activity(
                            'fee.discount_applied',
                            subject: $r,
                            message: "Set additional discount to Rs. " . number_format($after) . " on challan {$r->challan_number}. Reason: {$data['reason']}",
                            meta: ['before' => $before, 'after' => $after, 'reason' => $data['reason']],
                        );

                        \Filament\Notifications\Notification::make()->title('Discount applied')->success()->send();
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

    /**
     * The proof-review actions (view uploaded proof, confirm the student's claimed
     * payment, or reject and ask them to re-upload) — shared verbatim between this
     * resource's table and the dedicated ProofReview dashboard page, so there's one
     * implementation of "how admin reviews an uploaded proof," not two.
     *
     * @return array<int, Tables\Actions\Action>
     */
    public static function proofReviewActions(): array
    {
        return [
            Tables\Actions\Action::make('viewProof')
                ->label('View Proof')
                ->icon('heroicon-o-paper-clip')
                ->color('warning')
                ->iconButton()
                ->visible(fn (FeePayment $r) => !empty($r->payment_proof_path))
                ->url(fn (FeePayment $r) => asset('storage/' . $r->payment_proof_path))
                ->openUrlInNewTab()
                ->tooltip('View student-uploaded payment proof'),

            Tables\Actions\Action::make('confirmProofPayment')
                ->label('Confirm Payment')
                ->icon('heroicon-o-shield-check')
                ->color('success')
                ->iconButton()
                ->tooltip('Student claims this was paid — review and confirm')
                ->visible(fn (FeePayment $r) => $r->payment_status !== PaymentStatusEnum::Paid && ! empty($r->payment_proof_path))
                ->form(fn (FeePayment $r) => [
                    Forms\Components\Placeholder::make('claimed')
                        ->label('Student Claims')
                        ->content(fn () => 'Rs. ' . number_format((float) ($r->proof_claimed_amount ?? $r->amount_due))
                            . ' on ' . ($r->proof_claimed_date?->format('d M Y') ?? '—')),
                    Forms\Components\TextInput::make('payment_date')
                        ->label('Payment Date')
                        ->default(fn () => $r->proof_claimed_date?->toDateString() ?? now()->toDateString())
                        ->required(),
                    Forms\Components\Select::make('payment_method')
                        ->label('Payment Method')
                        ->options(PaymentMethodEnum::options())
                        ->default(PaymentMethodEnum::BankDraft->value),
                ])
                ->action(fn (FeePayment $r, array $data) => $r->markAsPaid(
                    auth()->id(),
                    $data['payment_date'] ?? null,
                    $data['payment_method'] ?? null,
                )),

            Tables\Actions\Action::make('rejectProof')
                ->label('Reject Proof')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->iconButton()
                ->tooltip('Reject this proof — student will need to re-upload')
                ->visible(fn (FeePayment $r) => $r->payment_status !== PaymentStatusEnum::Paid && ! empty($r->payment_proof_path))
                ->requiresConfirmation()
                ->modalHeading('Reject Payment Proof')
                ->modalDescription('This clears the uploaded proof so the student can upload a corrected one. The challan itself is not changed.')
                ->form([
                    Forms\Components\Textarea::make('reason')->label('Reason')->required()->rows(2),
                ])
                ->action(function (FeePayment $r, array $data) {
                    \App\Support\ActivityLogWriter::activity(
                        'fee.proof_rejected',
                        subject: $r,
                        message: "Rejected payment proof on challan {$r->challan_number}. Reason: {$data['reason']}",
                        meta: ['reason' => $data['reason']],
                    );

                    $r->payment_proof_path    = null;
                    $r->proof_uploaded_at     = null;
                    $r->proof_claimed_amount  = null;
                    $r->proof_claimed_date    = null;
                    $r->save();

                    if ($r->student) {
                        app(\App\Services\NotificationService::class)->send($r->student, 'fee_proof_rejected', [
                            'student_name' => $r->student->name,
                            'challan'      => $r->challan_number,
                            'reason'       => $data['reason'],
                        ]);
                    }

                    \Filament\Notifications\Notification::make()->title('Proof rejected')->success()->send();
                }),
        ];
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
