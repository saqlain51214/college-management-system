<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;
    protected static ?string $navigationIcon  = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Contact Messages';
    protected static ?int    $navigationSort  = 10;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_read', false)->count() ?: null;
    }
    public static function getNavigationBadgeColor(): ?string { return 'warning'; }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Message Details')->columns(2)->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('email'),
                Infolists\Components\TextEntry::make('subject')->columnSpanFull(),
                Infolists\Components\TextEntry::make('message')->columnSpanFull(),
                Infolists\Components\TextEntry::make('created_at')->label('Received')->dateTime('d M Y, H:i'),
                Infolists\Components\IconEntry::make('is_read')->label('Read')->boolean(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('subject')->searchable()->limit(40),
                Tables\Columns\IconColumn::make('is_read')->label('Read')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Received')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')->label('Read'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->after(fn(ContactMessage $r) => $r->update(['is_read'=>true])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('created_at','desc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view'  => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
