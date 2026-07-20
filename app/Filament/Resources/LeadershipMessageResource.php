<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadershipMessageResource\Pages;
use App\Models\LeadershipMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadershipMessageResource extends Resource
{
    protected static ?string $model = LeadershipMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Website Management';

    protected static ?string $navigationLabel = 'Message Desk';

    protected static ?string $modelLabel = 'Leadership Message';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Leader')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Name')->required()->maxLength(150),
                    Forms\Components\TextInput::make('designation')
                        ->label('Designation')->required()->maxLength(150)
                        ->placeholder('e.g. Vice Chancellor / Director / Principal'),
                    Forms\Components\TextInput::make('organization')
                        ->label('Organization (optional)')->maxLength(200)
                        ->placeholder('e.g. Karakoram International University'),
                    Forms\Components\FileUpload::make('photo')
                        ->label('Photo')->image()->disk('public')->directory('leadership')
                        ->maxSize(10240)->imageEditor()
                        ->helperText('Square photo works best (PNG/JPG, up to 10 MB). Leave empty to show initials.')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('message')
                        ->label('Message')->required()->rows(6)->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Display')
                ->schema([
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Order')->numeric()->default(0)
                        ->helperText('Lower number shows first (VC=1, Director=2, Principal=3).'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Show on website')->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('photo')->label('')->circular()->height(44)
                    ->defaultImageUrl(fn () => null),
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('designation')->label('Designation')->searchable(),
                Tables\Columns\TextColumn::make('organization')->label('Organization')->toggleable()->placeholder('—'),
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable()->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
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
            'index'  => Pages\ListLeadershipMessages::route('/'),
            'create' => Pages\CreateLeadershipMessage::route('/create'),
            'edit'   => Pages\EditLeadershipMessage::route('/{record}/edit'),
        ];
    }
}
