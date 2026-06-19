<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListItemResource\Pages;
use App\Models\ListItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ListItemResource extends Resource
{
    protected static ?string $model           = ListItem::class;
    protected static ?string $navigationIcon  = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Lookup Values';
    protected static ?string $navigationGroup = 'System';
    protected static ?int    $navigationSort  = 9;
    protected static ?string $modelLabel      = 'Lookup Value';
    protected static ?string $pluralModelLabel = 'Lookup Values';

    public static function form(Form $form): Form
    {
        $categories = [
            'province'             => 'Province / Region',
            'qualification_level'  => 'Student Qualification Level',
            'teacher_qualification'=> 'Teacher Qualification',
            'teacher_designation'  => 'Teacher Designation',
            'student_group'        => 'Student Group',
            'education_board'      => 'Education Board',
            'campus_location'      => 'Campus Location',
            'book_category'        => 'Book Category',
            'book_language'        => 'Book Language',
            'book_condition'       => 'Book Condition',
            'fee_frequency'        => 'Fee Frequency',
            'lms_material_type'    => 'LMS Material Type',
            'lms_submission_type'  => 'Assignment Submission Type',
            'news_category'        => 'News Category',
            'priority_level'       => 'Priority Level',
        ];

        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\Select::make('category')
                    ->label('Category')
                    ->options($categories)
                    ->searchable()
                    ->required()
                    ->columnSpan(1),

                Forms\Components\TextInput::make('value')
                    ->label('Value (stored in DB)')
                    ->required()
                    ->maxLength(100)
                    ->helperText('The key stored in database. Use lowercase with underscores, e.g. "mphil"')
                    ->columnSpan(1),

                Forms\Components\TextInput::make('label')
                    ->label('Display Label')
                    ->required()
                    ->maxLength(255)
                    ->helperText('What the user sees in the dropdown')
                    ->columnSpan(1),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first')
                    ->columnSpan(1),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Inactive items are hidden from dropdowns')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $categoryLabels = [
            'province'             => 'Province / Region',
            'qualification_level'  => 'Student Qualification',
            'teacher_qualification'=> 'Teacher Qualification',
            'teacher_designation'  => 'Teacher Designation',
            'student_group'        => 'Student Group',
            'education_board'      => 'Education Board',
            'campus_location'      => 'Campus Location',
            'book_category'        => 'Book Category',
            'book_language'        => 'Book Language',
            'book_condition'       => 'Book Condition',
            'fee_frequency'        => 'Fee Frequency',
            'lms_material_type'    => 'LMS Material Type',
            'lms_submission_type'  => 'Submission Type',
            'news_category'        => 'News Category',
            'priority_level'       => 'Priority Level',
        ];

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->formatStateUsing(fn($state) => $categoryLabels[$state] ?? ucwords(str_replace('_', ' ', $state)))
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('label')
                    ->label('Display Label')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('DB Value')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('category')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Category')
                    ->options($categoryLabels),

                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(fn($record) => \App\Models\ListItem::clearCache($record->category)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([25, 50, 100]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListListItems::route('/'),
            'create' => Pages\CreateListItem::route('/create'),
            'edit'   => Pages\EditListItem::route('/{record}/edit'),
        ];
    }
}
