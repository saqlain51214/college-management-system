<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteEventResource\Pages;
use App\Models\WebsiteEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class WebsiteEventResource extends Resource
{
    protected static ?string $model = WebsiteEvent::class;

    protected static ?string $navigationIcon  = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Events & Activities';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Event Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(200)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($state, Forms\Set $set) => $set('slug', Str::slug($state)))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('slug')->required()->maxLength(200)->columnSpanFull(),

                    Forms\Components\DateTimePicker::make('start_datetime')->label('Start Date & Time')->required()->displayFormat('d M Y, h:i A')->native(false),
                    Forms\Components\DateTimePicker::make('end_datetime')->label('End Date & Time')->displayFormat('d M Y, h:i A')->native(false),

                    Forms\Components\TextInput::make('venue')->maxLength(150)->placeholder('e.g. Main Auditorium, Block A'),
                    Forms\Components\TextInput::make('organizer')->maxLength(100)->placeholder('e.g. Computer Science Department'),

                    Forms\Components\Toggle::make('is_published')->label('Published')->default(true)->onColor('success'),
                    Forms\Components\Toggle::make('is_featured')->label('Featured on Homepage')->default(false)->onColor('warning'),

                    Forms\Components\FileUpload::make('featured_image')->image()->directory('website/events')->maxSize(2048)->columnSpanFull(),
                    Forms\Components\Textarea::make('description')->label('Event Description')->rows(4)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->wrap()->sortable(),
                Tables\Columns\TextColumn::make('start_datetime')->label('Date')->dateTime('d M Y, h:i A')->sortable(),
                Tables\Columns\TextColumn::make('venue')->placeholder('â€”')->toggleable(),
                Tables\Columns\TextColumn::make('organizer')->placeholder('â€”')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean(),
                Tables\Columns\IconColumn::make('is_published')->label('Published')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published'),
                Tables\Filters\TernaryFilter::make('is_featured'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('start_datetime', 'desc')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWebsiteEvents::route('/'),
            'create' => Pages\CreateWebsiteEvent::route('/create'),
            'edit'   => Pages\EditWebsiteEvent::route('/{record}/edit'),
        ];
    }
}


