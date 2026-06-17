<?php

namespace App\Filament\Resources;

use App\Enums\BookStatusEnum;
use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon  = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Library';
    protected static ?string $navigationLabel = 'Books Catalog';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Book Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Book Title')
                        ->required()
                        ->maxLength(250)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('accession_number')
                        ->label('Accession Number')
                        ->required()
                        ->maxLength(30)
                        ->unique(table: 'books', column: 'accession_number',
                            modifyRuleUsing: fn(\Illuminate\Validation\Rules\Unique $rule, ?Book $record) =>
                                $record ? $rule->ignore($record->id) : $rule
                        )
                        ->placeholder('e.g. LIB-2024-001'),

                    Forms\Components\TextInput::make('isbn')->label('ISBN')->maxLength(30)->placeholder('978-XXXXXXXXXX'),
                    Forms\Components\TextInput::make('author')->label('Author(s)')->maxLength(200)->placeholder('e.g. Dennis Ritchie'),
                    Forms\Components\TextInput::make('publisher')->label('Publisher')->maxLength(150),
                    Forms\Components\TextInput::make('publication_year')->label('Pub. Year')->numeric()->minValue(1800)->maxValue(now()->year),
                    Forms\Components\TextInput::make('edition')->label('Edition')->maxLength(30)->placeholder('e.g. 5th Edition'),

                    Forms\Components\Select::make('language')
                        ->options(fn() => \App\Models\ListItem::getOptions('book_language'))
                        ->default('English'),

                    Forms\Components\Select::make('category')
                        ->label('Category')
                        ->options(fn() => \App\Models\ListItem::getOptions('book_category'))
                        ->searchable(),

                    Forms\Components\TextInput::make('subject')->label('Subject')->maxLength(150),

                    Forms\Components\Select::make('department_id')
                        ->label('Department')
                        ->options(fn() => Department::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('General / All Departments'),

                    Forms\Components\TextInput::make('rack_location')->label('Rack / Shelf Location')->maxLength(50)->placeholder('e.g. A-3-12'),
                    Forms\Components\TextInput::make('total_copies')->label('Total Copies')->numeric()->default(1)->minValue(1),
                    Forms\Components\TextInput::make('available_copies')->label('Available Copies')->numeric()->default(1)->minValue(0),
                    Forms\Components\TextInput::make('price')->label('Price (PKR)')->numeric()->prefix('Rs.'),

                    Forms\Components\Select::make('status')
                        ->options(BookStatusEnum::options())
                        ->default(BookStatusEnum::Available->value)
                        ->required(),

                    Forms\Components\Toggle::make('is_reference_only')->label('Reference Only (Cannot be issued)')->default(false)->onColor('warning'),
                    Forms\Components\Toggle::make('is_active')->label('Active')->default(true)->onColor('success'),

                    Forms\Components\FileUpload::make('cover_image')
                        ->label('Cover Image')
                        ->image()
                        ->directory('library/covers')
                        ->maxSize(1024)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')->label('Description / Summary')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('accession_number')->label('Acc. No.')->badge()->color('gray')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable()->wrap()->sortable(),
                Tables\Columns\TextColumn::make('author')->searchable()->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('category')->badge()->color('info')->placeholder('—'),
                Tables\Columns\TextColumn::make('publication_year')->label('Year')->placeholder('—'),
                Tables\Columns\TextColumn::make('total_copies')->label('Total')->alignCenter(),
                Tables\Columns\TextColumn::make('available_copies')->label('Available')->alignCenter()
                    ->color(fn(Book $r) => $r->available_copies === 0 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof BookStatusEnum ? $state->label() : $state)
                    ->color(fn($state) => $state instanceof BookStatusEnum ? $state->color() : 'gray'),
                Tables\Columns\IconColumn::make('is_reference_only')->label('Ref Only')->boolean()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn() => \App\Models\ListItem::getOptions('book_category')),
                Tables\Filters\SelectFilter::make('status')->options(BookStatusEnum::options()),
                Tables\Filters\SelectFilter::make('department_id')->label('Department')->relationship('department', 'name'),
                Tables\Filters\TernaryFilter::make('is_reference_only')->label('Reference Only'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('accession_number')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getNavigationBadge(): ?string
    {
        try { return (string) Book::where('is_active', true)->count() ?: null; }
        catch (\Exception) { return null; }
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit'   => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
