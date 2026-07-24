<?php

namespace App\Filament\Resources;

use App\Enums\DepartmentTypeEnum;
use App\Filament\Resources\DepartmentResource\Pages;
use App\Helpers\ValidationHelper;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon  = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'College Setup';
    protected static ?string $navigationLabel = 'Departments';
    protected static ?int    $navigationSort  = 1;

    // ─── Form ────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Section 1: Basic Information ─────────────────────────────────
            Forms\Components\Section::make('Basic Information')
                ->description('Core department identity details.')
                ->columns(2)
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->label('Department Name')
                        ->placeholder('e.g. Department of Computer Science')
                        ->required()
                        ->minLength(3)
                        ->maxLength(100)
                        ->regex('/^[\p{L}\s\-\.]+$/u')
                        ->unique(
                            table: 'departments',
                            column: 'name',
                            modifyRuleUsing: fn(Unique $rule, ?Department $record) => $record
                                ? $rule->ignore($record->id)
                                : $rule
                        )
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::nameAttrs(required: true))
                        ->afterStateUpdated(fn($state, Forms\Set $set) =>
                            $set('slug', Str::slug($state))
                        )
                        ->validationMessages([
                            'required' => 'Department name is required.',
                            'min'      => 'Name must be at least 3 characters.',
                            'max'      => 'Name cannot exceed 100 characters.',
                            'regex'    => 'Only letters, spaces, hyphens and dots are allowed.',
                            'unique'   => 'A department with this name already exists.',
                        ]),

                    Forms\Components\TextInput::make('name_urdu')
                        ->label('Department Name (Urdu)')
                        ->placeholder('شعبہ کمپیوٹر سائنس')
                        ->maxLength(200)
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::textAttrs(max: 200))
                        ->helperText('Optional — Urdu name for public website.')
                        ->validationMessages([
                            'max' => 'Urdu name cannot exceed 200 characters.',
                        ]),

                    Forms\Components\TextInput::make('code')
                        ->label('Department Code')
                        ->placeholder('DEPT-CS')
                        ->minLength(2)
                        ->maxLength(20)
                        ->unique(
                            table: 'departments',
                            column: 'code',
                            modifyRuleUsing: fn(Unique $rule, ?Department $record) => $record
                                ? $rule->ignore($record->id)
                                : $rule
                        )
                        ->regex('/^[A-Z0-9\-]+$/')
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::codeAttrs())
                        ->dehydrateStateUsing(fn($state) => $state ? strtoupper($state) : null)
                        ->helperText('Uppercase letters, numbers, hyphens only. e.g. DEPT-CS')
                        ->validationMessages([
                            'regex'  => 'Code must be uppercase letters, numbers and hyphens only.',
                            'unique' => 'This department code is already in use. Please enter a different code.',
                            'min'    => 'Code must be at least 2 characters.',
                            'max'    => 'Code cannot exceed 20 characters.',
                        ]),

                    Forms\Components\Select::make('type')
                        ->label('Department Type')
                        ->options(DepartmentTypeEnum::options())
                        ->default(DepartmentTypeEnum::Academic->value)
                        ->required()
                        ->live()
                        ->validationMessages([
                            'required' => 'Please select a department type.',
                        ]),

                    Forms\Components\TextInput::make('slug')
                        ->label('URL Slug')
                        ->placeholder('department-of-computer-science')
                        ->maxLength(150)
                        ->unique(
                            table: 'departments',
                            column: 'slug',
                            modifyRuleUsing: fn(Unique $rule, ?Department $record) => $record
                                ? $rule->ignore($record->id)
                                : $rule
                        )
                        ->regex('/^[a-z0-9\-]+$/')
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::slugAttrs())
                        ->helperText('Auto-generated. Lowercase letters, numbers, hyphens only.')
                        ->columnSpanFull()
                        ->validationMessages([
                            'unique' => 'This URL slug is already taken. Change the department name or edit the slug manually.',
                            'regex'  => 'Slug must be lowercase letters, numbers and hyphens only.',
                            'max'    => 'Slug cannot exceed 150 characters.',
                        ]),
                ]),

            // ── Section 2: Head of Department ────────────────────────────────
            Forms\Components\Section::make('Head of Department (HOD)')
                ->description('HOD information displayed on the public website.')
                ->columns(2)
                ->schema([

                    Forms\Components\TextInput::make('hod_name')
                        ->label('HOD Full Name')
                        ->placeholder('Prof. Dr. Muhammad Ali')
                        ->minLength(3)
                        ->maxLength(100)
                        ->regex('/^[\p{L}\s\-\.]+$/u')
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::nameAttrs(required: false))
                        ->validationMessages([
                            'min'   => 'HOD name must be at least 3 characters.',
                            'max'   => 'HOD name cannot exceed 100 characters.',
                            'regex' => 'Only letters, spaces, hyphens and dots allowed.',
                        ]),

                    Forms\Components\TextInput::make('hod_designation')
                        ->label('HOD Designation')
                        ->placeholder('Associate Professor')
                        ->minLength(3)
                        ->maxLength(100)
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::textAttrs(min: 3, max: 100))
                        ->validationMessages([
                            'min' => 'Designation must be at least 3 characters.',
                            'max' => 'Designation cannot exceed 100 characters.',
                        ]),

                    Forms\Components\FileUpload::make('hod_photo')
                        ->label('HOD Photo')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios(['1:1'])
                        ->disk('public')
                        ->directory('departments/hod')
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->helperText('Max 2MB · JPG, PNG, WebP · Cropped to 1:1 square.')
                        ->deleteUploadedFileUsing(fn($file) =>
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($file)
                        ),

                    Forms\Components\RichEditor::make('hod_message')
                        ->label('Message from HOD')
                        ->toolbarButtons(['bold', 'italic', 'underline', 'bulletList', 'orderedList', 'undo', 'redo'])
                        ->maxLength(3000)
                        ->helperText('Max 3000 characters.')
                        ->columnSpanFull(),
                ]),

            // ── Section 3: Department Content ────────────────────────────────
            Forms\Components\Section::make('Department Content')
                ->description('Content displayed on the public department page.')
                ->schema([

                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->placeholder('Brief overview of the department...')
                        ->rows(4)
                        ->minLength(10)
                        ->maxLength(2000)
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::textAttrs(min: 10, max: 2000))
                        ->helperText('10 – 2000 characters.')
                        ->columnSpanFull()
                        ->validationMessages([
                            'min' => 'Description must be at least 10 characters.',
                            'max' => 'Description cannot exceed 2000 characters.',
                        ]),

                    Forms\Components\RichEditor::make('vision')
                        ->label('Vision Statement')
                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                        ->helperText('Max 1000 characters.')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('mission')
                        ->label('Mission Statement')
                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                        ->helperText('Max 1000 characters.')
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('banner_image')
                        ->label('Banner Image')
                        ->image()
                        ->imageEditor()
                        ->disk('public')
                        ->directory('departments/banners')
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->helperText('Max 2MB · JPG, PNG, WebP · Recommended: 1200×400px.')
                        ->deleteUploadedFileUsing(fn($file) =>
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($file)
                        )
                        ->columnSpanFull(),
                ]),

            // ── Section 4: Contact & Display ─────────────────────────────────
            Forms\Components\Section::make('Contact & Display Settings')
                ->columns(2)
                ->schema([

                    Forms\Components\TextInput::make('email')
                        ->label('Department Email')
                        ->email()
                        ->placeholder('dept@college.edu.pk')
                        ->maxLength(150)
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::emailAttrs())
                        ->validationMessages([
                            'email' => 'Please enter a valid email address.',
                            'max'   => 'Email cannot exceed 150 characters.',
                        ]),

                    Forms\Components\TextInput::make('phone')
                        ->label('Phone Number')
                        ->tel()
                        ->placeholder('03001234567')
                        ->minLength(10)
                        ->maxLength(15)
                        ->regex('/^(\+92|0)[0-9]{9,10}$/')
                        ->live(onBlur: true)
                        ->extraAttributes(ValidationHelper::phoneAttrs())
                        ->helperText('Pakistani format: 03001234567 or +923001234567')
                        ->validationMessages([
                            'regex' => 'Enter a valid Pakistani number e.g. 03001234567.',
                            'min'   => 'Phone number is too short.',
                            'max'   => 'Phone number is too long.',
                        ]),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->maxValue(999)
                        ->extraAttributes(ValidationHelper::numberAttrs(min: 0, max: 999))
                        ->helperText('0–999. Lower = shown first on website.')
                        ->validationMessages([
                            'min' => 'Sort order cannot be negative.',
                            'max' => 'Sort order cannot exceed 999.',
                        ]),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->onColor('success')
                        ->helperText('Inactive departments hidden from all portals.'),

                    Forms\Components\Toggle::make('show_on_website')
                        ->label('Show on Public Website')
                        ->default(true)
                        ->onColor('success')
                        ->helperText('Uncheck to hide from public website only.'),
                ]),
        ]);
    }

    // ─── Table ───────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('hod_photo')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(asset('images/avatar-placeholder.svg')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Department $r) => $r->code ?? '—')
                    ->wrap(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof DepartmentTypeEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof DepartmentTypeEnum ? $state->color() : 'gray'),

                Tables\Columns\TextColumn::make('hod_name')
                    ->label('HOD')
                    ->searchable()
                    ->placeholder('—')
                    ->description(fn(Department $r) => $r->hod_designation ?? '')
                    ->toggleable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->onColor('success')
                    ->offColor('danger'),

                Tables\Columns\ToggleColumn::make('show_on_website')
                    ->label('Website')
                    ->onColor('success')
                    ->offColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(DepartmentTypeEnum::options()),

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
                    ->modalDescription('Soft delete — record can be restored. Images are kept.'),

                Tables\Actions\ForceDeleteAction::make()
                    ->modalDescription('PERMANENT delete — record AND images will be removed. Cannot be undone.'),

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
            'index'  => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit'   => Pages\EditDepartment::route('/{record}/edit'),
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
