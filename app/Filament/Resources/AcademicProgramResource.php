<?php

namespace App\Filament\Resources;

use App\Enums\AdmissionCategoryEnum;
use App\Enums\DegreeTypeEnum;
use App\Filament\Resources\AcademicProgramResource\Pages;
use App\Helpers\ValidationHelper;
use App\Models\AcademicProgram;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class AcademicProgramResource extends Resource
{
    protected static ?string $model = AcademicProgram::class;

    protected static ?string $navigationIcon  = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'College Setup';
    protected static ?string $navigationLabel = 'Academic Programs';
    protected static ?int    $navigationSort  = 2;

    // ─── Form ────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Section 1: Program Identity ───────────────────────────────────
            Forms\Components\Section::make('Program Identity')
                ->description('Core program details.')
                ->columns(2)
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->label('Program Full Name')
                        ->placeholder('e.g. Bachelor of Science in Computer Science')
                        ->required()
                        ->minLength(5)
                        ->maxLength(150)
                        ->unique(
                            table: 'academic_programs',
                            column: 'name',
                            modifyRuleUsing: fn(Unique $rule, ?AcademicProgram $record) => $record
                                ? $rule->ignore($record->id)
                                : $rule
                        )
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::textAttrs(min: 5, max: 150, required: true))
                        ->afterStateUpdated(fn($state, Forms\Set $set) =>
                            $set('slug', Str::slug($state))
                        )
                        ->validationMessages([
                            'required' => 'Program name is required.',
                            'min'      => 'Name must be at least 5 characters.',
                            'max'      => 'Name cannot exceed 150 characters.',
                            'unique'   => 'A program with this name already exists.',
                        ])
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('short_name')
                        ->label('Short Name / Abbreviation')
                        ->placeholder('e.g. BS CS')
                        ->maxLength(50)
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::textAttrs(max: 50))
                        ->helperText('e.g. BS CS, M.Ed, MA Eng — used in reports and tables.')
                        ->validationMessages([
                            'max' => 'Short name cannot exceed 50 characters.',
                        ]),

                    Forms\Components\TextInput::make('name_urdu')
                        ->label('Program Name (Urdu)')
                        ->placeholder('بیچلر آف سائنس')
                        ->maxLength(200)
                        ->extraAttributes(ValidationHelper::textAttrs(max: 200))
                        ->helperText('Optional — for public website Urdu section.'),

                    Forms\Components\TextInput::make('code')
                        ->label('Program Code')
                        ->placeholder('BS-CS')
                        ->minLength(2)
                        ->maxLength(30)
                        ->unique(
                            table: 'academic_programs',
                            column: 'code',
                            modifyRuleUsing: fn(Unique $rule, ?AcademicProgram $record) => $record
                                ? $rule->ignore($record->id)
                                : $rule
                        )
                        ->regex('/^[A-Z0-9\-\.]+$/')
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::codeAttrs())
                        ->dehydrateStateUsing(fn($state) => $state ? strtoupper($state) : null)
                        ->helperText('Uppercase, e.g. BS-CS, MED, MA-ENG')
                        ->validationMessages([
                            'regex'  => 'Code must be uppercase letters, numbers, hyphens, dots only.',
                            'unique' => 'This program code is already in use.',
                            'min'    => 'Code must be at least 2 characters.',
                            'max'    => 'Code cannot exceed 30 characters.',
                        ]),

                    Forms\Components\TextInput::make('slug')
                        ->label('URL Slug')
                        ->placeholder('bs-computer-science')
                        ->maxLength(150)
                        ->unique(
                            table: 'academic_programs',
                            column: 'slug',
                            modifyRuleUsing: fn(Unique $rule, ?AcademicProgram $record) => $record
                                ? $rule->ignore($record->id)
                                : $rule
                        )
                        ->regex('/^[a-z0-9\-]+$/')
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::slugAttrs())
                        ->helperText('Auto-generated from name. Lowercase, hyphens only.')
                        ->validationMessages([
                            'unique' => 'This slug is already taken.',
                            'regex'  => 'Slug must be lowercase letters, numbers and hyphens only.',
                            'max'    => 'Slug cannot exceed 150 characters.',
                        ]),
                ]),

            // ── Section 2: Classification ─────────────────────────────────────
            Forms\Components\Section::make('Classification & Structure')
                ->columns(2)
                ->schema([

                    Forms\Components\Select::make('department_id')
                        ->label('Department')
                        ->options(fn() => Department::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->placeholder('Select Department')
                        ->helperText('Which department offers this program?'),

                    Forms\Components\Select::make('degree_type')
                        ->label('Degree Type')
                        ->options(DegreeTypeEnum::options())
                        ->required()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state) {
                                $type = DegreeTypeEnum::from($state);
                                $set('duration_years',  $type->defaultDuration());
                                $set('total_semesters', $type->defaultSemesters());
                                $set('admission_category', AcademicProgram::inferAdmissionCategory(null, null, null, $type)->value);
                            }
                        })
                        ->validationMessages([
                            'required' => 'Please select a degree type.',
                        ]),

                    Forms\Components\Select::make('admission_category')
                        ->label('Admission Category')
                        ->options(AdmissionCategoryEnum::options())
                        ->required()
                        ->native(false)
                        ->helperText('Controls where this programme appears in the online admission form.')
                        ->validationMessages([
                            'required' => 'Please select where this programme should appear in admissions.',
                        ]),

                    Forms\Components\TextInput::make('duration_years')
                        ->label('Duration (Years)')
                        ->numeric()
                        ->required()
                        ->default(4)
                        ->minValue(1)
                        ->maxValue(10)
                        ->suffix('years')
                        ->extraAttributes(ValidationHelper::numberAttrs(min: 1, max: 10, required: true))
                        ->validationMessages([
                            'required' => 'Duration is required.',
                            'min'      => 'Minimum duration is 1 year.',
                            'max'      => 'Maximum duration is 10 years.',
                        ]),

                    Forms\Components\TextInput::make('total_semesters')
                        ->label('Total Semesters')
                        ->numeric()
                        ->required()
                        ->default(8)
                        ->minValue(1)
                        ->maxValue(20)
                        ->suffix('semesters')
                        ->extraAttributes(ValidationHelper::numberAttrs(min: 1, max: 20, required: true))
                        ->validationMessages([
                            'required' => 'Total semesters is required.',
                            'min'      => 'Minimum is 1 semester.',
                            'max'      => 'Maximum is 20 semesters.',
                        ]),

                    Forms\Components\TextInput::make('total_credit_hours')
                        ->label('Total Credit Hours')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(300)
                        ->placeholder('e.g. 130')
                        ->suffix('credit hours')
                        ->extraAttributes(ValidationHelper::numberAttrs(min: 1, max: 300))
                        ->helperText('HEC recommended for BS: 124–130 credit hours.')
                        ->validationMessages([
                            'min' => 'Minimum 1 credit hour.',
                            'max' => 'Cannot exceed 300 credit hours.',
                        ]),
                ]),

            // ── Section 3: Program Content ────────────────────────────────────
            Forms\Components\Section::make('Program Description')
                ->schema([

                    Forms\Components\Textarea::make('description')
                        ->label('Program Overview')
                        ->placeholder('Brief description of the program, its goals and outcomes...')
                        ->rows(4)
                        ->minLength(10)
                        ->maxLength(3000)
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::textAttrs(min: 10, max: 3000))
                        ->helperText('10 – 3000 characters.')
                        ->columnSpanFull()
                        ->validationMessages([
                            'min' => 'Description must be at least 10 characters.',
                            'max' => 'Description cannot exceed 3000 characters.',
                        ]),

                    Forms\Components\Textarea::make('eligibility')
                        ->label('Admission Eligibility')
                        ->placeholder('e.g. F.A / F.Sc with at least 45% marks from HEC recognized board...')
                        ->rows(3)
                        ->maxLength(3000)
                        ->extraAttributes(ValidationHelper::textAttrs(max: 3000))
                        ->helperText('Admission requirements shown to prospective students.')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('scope')
                        ->label('Career Scope')
                        ->placeholder('e.g. Graduates can pursue careers in teaching, research, software development...')
                        ->rows(3)
                        ->maxLength(3000)
                        ->extraAttributes(ValidationHelper::textAttrs(max: 3000))
                        ->helperText('Career opportunities after completing this program.')
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('banner_image')
                        ->label('Program Banner Image')
                        ->image()
                        ->imageEditor()
                        ->disk('public')
                        ->directory('programs/banners')
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->helperText('Max 2MB · JPG, PNG, WebP · Recommended: 1200×400px.')
                        ->deleteUploadedFileUsing(fn($file) =>
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($file)
                        )
                        ->columnSpanFull(),
                ]),

            // ── Section 4: Display Settings ───────────────────────────────────
            Forms\Components\Section::make('Display Settings')
                ->columns(3)
                ->schema([

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->maxValue(999)
                        ->extraAttributes(ValidationHelper::numberAttrs(min: 0, max: 999))
                        ->helperText('Lower = shown first.'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->onColor('success')
                        ->helperText('Inactive programs hidden from all portals.'),

                    Forms\Components\Toggle::make('show_on_website')
                        ->label('Show on Website')
                        ->default(true)
                        ->onColor('success')
                        ->helperText('Hide from public website only.'),
                ]),
        ]);
    }

    // ─── Table ───────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Program')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(AcademicProgram $r) => $r->short_name ?? $r->code ?? '—')
                    ->wrap(),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('degree_type')
                    ->label('Degree')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof DegreeTypeEnum ? $state->shortLabel() : $state)
                    ->color(fn($state) => $state instanceof DegreeTypeEnum ? $state->color() : 'gray'),

                Tables\Columns\TextColumn::make('admission_category')
                    ->label('Admission')
                    ->badge()
                    ->formatStateUsing(fn (AcademicProgram $record) => $record->admission_category_label)
                    ->color(fn ($state) => match ($state?->value ?? $state) {
                        AdmissionCategoryEnum::Intermediate->value => 'success',
                        AdmissionCategoryEnum::Undergraduate->value => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('duration_label')
                    ->label('Duration')
                    ->state(fn(AcademicProgram $r) => "{$r->duration_years}yr / {$r->total_semesters}sem"),

                Tables\Columns\TextColumn::make('total_credit_hours')
                    ->label('Credits')
                    ->placeholder('—')
                    ->suffix(' cr')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->onColor('success')
                    ->offColor('danger'),

                Tables\Columns\ToggleColumn::make('show_on_website')
                    ->label('Website')
                    ->onColor('success')
                    ->offColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name'),

                Tables\Filters\SelectFilter::make('degree_type')
                    ->options(DegreeTypeEnum::options()),

                Tables\Filters\SelectFilter::make('admission_category')
                    ->options(AdmissionCategoryEnum::options()),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),

                Tables\Filters\TernaryFilter::make('show_on_website')
                    ->label('Website Visibility'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make()
                    ->modalDescription('Soft delete — record can be restored later.'),

                Tables\Actions\ForceDeleteAction::make()
                    ->modalDescription('PERMANENT delete — cannot be undone.'),

                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(10)
            ->striped();
    }

    // ─── Pages ───────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAcademicPrograms::route('/'),
            'create' => Pages\CreateAcademicProgram::route('/create'),
            'edit'   => Pages\EditAcademicProgram::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            return (string) static::getModel()::where('is_active', true)->count() ?: null;
        } catch (\Exception) {
            return null;
        }
    }
}
