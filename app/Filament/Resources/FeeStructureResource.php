<?php

namespace App\Filament\Resources;

use App\Enums\FeeTypeEnum;
use App\Filament\Resources\FeeStructureResource\Pages;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\FeeStructure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeeStructureResource extends Resource
{
    protected static ?string $model = FeeStructure::class;

    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Fees & Billing';
    protected static ?string $navigationLabel = 'Fee Structure';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Fee Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Fee Title')
                        ->required()
                        ->maxLength(150)
                        ->placeholder('e.g. BS CS Semester 1 Tuition Fee 2024-25')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('fee_type')
                        ->label('Fee Type')
                        ->options(FeeTypeEnum::options())
                        ->required()
                        ->default(FeeTypeEnum::Tuition->value),

                    Forms\Components\Select::make('semester_number')
                        ->label('Semester')
                        ->options(collect(range(1, 8))->mapWithKeys(fn($n) => [$n => "Semester $n"])->all())
                        ->placeholder('All Semesters'),

                    Forms\Components\Select::make('academic_program_id')
                        ->label('Academic Program')
                        ->options(fn() => AcademicProgram::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->placeholder('All Programs'),

                    Forms\Components\Select::make('academic_year_id')
                        ->label('Academic Year')
                        ->options(fn() => AcademicYear::active()->orderByDesc('start_date')->pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('Select Year'),

                    Forms\Components\TextInput::make('amount')
                        ->label('Fee Amount (PKR)')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->prefix('Rs.')
                        ->placeholder('e.g. 25000')
                        ->disabled(fn (?FeeStructure $record) => $record !== null)
                        ->dehydrated()
                        ->helperText(fn (?FeeStructure $record) => $record
                            ? 'Use the "Update Amount" action on the table to change this — keeps a history and never affects already-generated challans.'
                            : null),

                    Forms\Components\TextInput::make('late_fine_per_day')
                        ->label('Late Fine Per Day (PKR)')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->prefix('Rs.'),

                    Forms\Components\DatePicker::make('due_date')
                        ->label('Due Date')
                        ->displayFormat('d M Y')
                        ->native(false),

                    Forms\Components\Select::make('frequency')
                        ->label('Frequency')
                        ->options(fn() => \App\Models\ListItem::getOptions('fee_frequency'))
                        ->default('semester'),

                    Forms\Components\Toggle::make('is_mandatory')->label('Mandatory')->default(true)->onColor('warning'),
                    Forms\Components\Toggle::make('is_active')->label('Active')->default(true)->onColor('success'),

                    Forms\Components\Textarea::make('description')
                        ->label('Description / Notes')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->wrap()->sortable(),
                Tables\Columns\TextColumn::make('fee_type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof FeeTypeEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof FeeTypeEnum ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('academicProgram.short_name')->label('Program')->badge()->color('info')->placeholder('All'),
                Tables\Columns\TextColumn::make('academicYear.name')->label('Year')->placeholder('—'),
                Tables\Columns\TextColumn::make('semester_number')->label('Sem')->prefix('S')->placeholder('All'),
                Tables\Columns\TextColumn::make('amount')->label('Amount')->money('PKR')->sortable(),
                Tables\Columns\TextColumn::make('due_date')->label('Due Date')->date('d M Y')->placeholder('—'),
                Tables\Columns\IconColumn::make('is_mandatory')->label('Mandatory')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('fee_type')->options(FeeTypeEnum::options()),
                Tables\Filters\SelectFilter::make('academic_program_id')->label('Program')->relationship('academicProgram', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\Action::make('updateAmount')
                    ->label('Update Amount')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form(fn (FeeStructure $r) => [
                        Forms\Components\Placeholder::make('current')
                            ->label('Current Amount')
                            ->content(fn () => 'Rs. ' . number_format((float) $r->amount)),
                        Forms\Components\TextInput::make('new_amount')
                            ->label('New Amount (PKR)')
                            ->numeric()->prefix('Rs.')->required()->minValue(0),
                        Forms\Components\DatePicker::make('effective_from')
                            ->label('Effective From')->default(now())->native(false)->displayFormat('d M Y')->required(),
                        Forms\Components\Textarea::make('reason')
                            ->label('Reason')->required()->rows(2),
                    ])
                    ->action(function (FeeStructure $r, array $data) {
                        $oldAmount = (float) $r->amount;

                        \App\Models\FeeStructureRevision::create([
                            'fee_structure_id' => $r->id,
                            'old_amount'       => $oldAmount,
                            'new_amount'       => $data['new_amount'],
                            'effective_from'   => $data['effective_from'],
                            'reason'           => $data['reason'],
                            'changed_by'       => auth()->id(),
                        ]);

                        $r->amount = $data['new_amount'];
                        $r->save();

                        // Only query roles that actually exist for this guard — mirrors
                        // the same pattern used by FeeRefund's admin alert.
                        $existingRoles = \Spatie\Permission\Models\Role::whereIn('name', ['super_admin', 'Developer'])
                            ->where('guard_name', 'web')->pluck('name')->all();
                        $otherAdmins = $existingRoles
                            ? \App\Models\User::role($existingRoles)->where('id', '!=', auth()->id())->get()
                            : collect();

                        if ($otherAdmins->isNotEmpty()) {
                            \Filament\Notifications\Notification::make()
                                ->info()
                                ->title('Fee Structure Amount Changed')
                                ->body(
                                    ($r->name ?? 'A fee structure') . ' changed from Rs. ' . number_format($oldAmount) .
                                    ' to Rs. ' . number_format((float) $data['new_amount']) . ' by ' . (auth()->user()->name ?? 'an admin') .
                                    '. Reason: ' . $data['reason']
                                )
                                ->sendToDatabase($otherAdmins);
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Amount updated')
                            ->body('Already-generated challans keep their original amount — this only applies to new challans from now on.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('revisionHistory')
                    ->label('History')
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->modalHeading('Amount Revision History')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->visible(fn (FeeStructure $r) => $r->revisions()->exists())
                    ->modalContent(fn (FeeStructure $r) => view('filament.resources.fee-structure-resource.revision-history', ['revisions' => $r->revisions()->with('changedBy')->get()])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('fee_type')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFeeStructures::route('/'),
            'create' => Pages\CreateFeeStructure::route('/create'),
            'edit'   => Pages\EditFeeStructure::route('/{record}/edit'),
        ];
    }
}
