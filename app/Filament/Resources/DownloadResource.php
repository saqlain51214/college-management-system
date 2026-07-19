<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadResource\Pages;
use App\Models\Download;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DownloadResource extends Resource
{
    protected static ?string $model = Download::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationGroup = 'Website Management';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->columnSpanFull(),

                Forms\Components\Select::make('category')
                    ->options([
                        'admission' => 'Admission Documents',
                        'academic' => 'Academic Documents',
                        'administrative' => 'Administrative Forms',
                        'general' => 'General',
                    ])
                    ->default('general')
                    ->required(),

                Forms\Components\FileUpload::make('file_path')
                    ->label('File')
                    ->disk('public')
                    ->directory('downloads')
                    ->required()
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $filename = is_array($state) ? array_key_first($state) : $state;
                            if ($filename) {
                                $extension = strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
                                $set('original_filename', $filename);
                                $set('file_type', $extension ?: 'PDF');
                            }
                        }
                    })
                    ->reactive(),

                Forms\Components\TextInput::make('original_filename')
                    ->label('Original Filename')
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\TextInput::make('file_type')
                    ->label('File Type')
                    ->default('PDF')
                    ->maxLength(20),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admission' => 'primary',
                        'academic' => 'success',
                        'administrative' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('file_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PDF' => 'danger',
                        'DOC', 'DOCX' => 'primary',
                        'XLSX', 'XLS' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'admission' => 'Admission Documents',
                        'academic' => 'Academic Documents',
                        'administrative' => 'Administrative Forms',
                        'general' => 'General',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDownloads::route('/'),
            'create' => Pages\CreateDownload::route('/create'),
            'edit' => Pages\EditDownload::route('/{record}/edit'),
        ];
    }
}
