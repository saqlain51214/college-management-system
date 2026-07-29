<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSlideResource\Pages;
use App\Models\HeroSlide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Website Management';

    protected static ?string $navigationLabel = 'Homepage Slider';

    protected static ?string $modelLabel = 'Hero Slide';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Slide')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Background Image')
                        ->image()
                        ->disk('public')
                        ->directory('hero-slides')
                        ->maxSize(10240)
                        ->imageEditor()
                        ->required()
                        ->helperText('Wide landscape photo works best (1920×1080 or similar), up to 10 MB.')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(2)
                        ->maxLength(500)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Buttons (Optional)')
                ->description('Route name to link to — e.g. "admissions", "programs", "departments", "contact". Leave blank to hide a button.')
                ->schema([
                    Forms\Components\TextInput::make('primary_btn_text')
                        ->label('Primary Button Text')
                        ->maxLength(60)
                        ->placeholder('e.g. Apply for Admission'),
                    Forms\Components\TextInput::make('primary_btn_link')
                        ->label('Primary Button Route')
                        ->maxLength(100)
                        ->placeholder('e.g. admissions'),
                    Forms\Components\TextInput::make('secondary_btn_text')
                        ->label('Secondary Button Text')
                        ->maxLength(60)
                        ->placeholder('e.g. Explore Programs'),
                    Forms\Components\TextInput::make('secondary_btn_link')
                        ->label('Secondary Button Route')
                        ->maxLength(100)
                        ->placeholder('e.g. programs'),
                ])->columns(2),

            Forms\Components\Section::make('Display')
                ->schema([
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower number shows first.'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Show on website')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('')->square()->height(50),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->limit(40),
                Tables\Columns\TextColumn::make('description')->limit(50)->toggleable(),
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable()->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHeroSlides::route('/'),
            'create' => Pages\CreateHeroSlide::route('/create'),
            'edit'   => Pages\EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
