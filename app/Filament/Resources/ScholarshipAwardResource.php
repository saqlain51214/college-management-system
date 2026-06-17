<?php

namespace App\Filament\Resources;

use App\Enums\ScholarshipStatusEnum;
use App\Filament\Resources\ScholarshipAwardResource\Pages;
use App\Models\AcademicYear;
use App\Models\Scholarship;
use App\Models\ScholarshipAward;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScholarshipAwardResource extends Resource
{
    protected static ?string $model = ScholarshipAward::class;

    protected static ?string $navigationIcon  = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Students & Admissions';
    protected static ?string $navigationLabel = 'Scholarship Awards';
    protected static ?int    $navigationSort  = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Award Details')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('scholarship_id')
                        ->label('Scholarship')
                        ->options(fn() => Scholarship::active()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('student_id')
                        ->label('Student')
                        ->options(fn() => Student::where('is_active', true)->orderBy('name')
                            ->get()->mapWithKeys(fn($s) => [$s->id => $s->roll_number . ' — ' . $s->name]))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('academic_year_id')
                        ->label('Academic Year')
                        ->options(fn() => AcademicYear::active()->orderByDesc('start_date')->pluck('name', 'id'))
                        ->searchable(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(ScholarshipStatusEnum::options())
                        ->default(ScholarshipStatusEnum::Applied->value)
                        ->required(),

                    Forms\Components\TextInput::make('amount_awarded')->label('Amount Awarded (PKR)')->numeric()->prefix('Rs.'),

                    Forms\Components\DatePicker::make('application_date')->label('Application Date')->displayFormat('d M Y')->native(false)->default(now()),
                    Forms\Components\DatePicker::make('approval_date')->label('Approval Date')->displayFormat('d M Y')->native(false),
                    Forms\Components\DatePicker::make('disbursement_date')->label('Disbursement Date')->displayFormat('d M Y')->native(false),
                    Forms\Components\DatePicker::make('expiry_date')->label('Expiry Date')->displayFormat('d M Y')->native(false),

                    Forms\Components\Textarea::make('reason')->label('Reason / Justification')->rows(2)->columnSpanFull(),
                    Forms\Components\Textarea::make('remarks')->label('Remarks')->rows(2)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.roll_number')->label('Roll No.')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('student.name')->label('Student')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('scholarship.name')->label('Scholarship')->wrap()->sortable(),
                Tables\Columns\TextColumn::make('academicYear.name')->label('Year')->placeholder('—'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof ScholarshipStatusEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof ScholarshipStatusEnum ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('amount_awarded')->label('Amount')->money('PKR')->placeholder('—'),
                Tables\Columns\TextColumn::make('application_date')->label('Applied')->date('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(ScholarshipStatusEnum::options()),
                Tables\Filters\SelectFilter::make('scholarship_id')->label('Scholarship')->relationship('scholarship', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->visible(fn(ScholarshipAward $r) => $r->status === ScholarshipStatusEnum::Applied || $r->status === ScholarshipStatusEnum::UnderReview)
                    ->action(fn(ScholarshipAward $r) => $r->update(['status' => ScholarshipStatusEnum::Approved->value, 'approval_date' => now()->toDateString()])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('application_date', 'desc')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListScholarshipAwards::route('/'),
            'create' => Pages\CreateScholarshipAward::route('/create'),
            'edit'   => Pages\EditScholarshipAward::route('/{record}/edit'),
        ];
    }
}
