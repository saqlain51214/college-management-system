<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobApplicationResource\Pages;
use App\Models\JobApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JobApplicationResource extends Resource
{
    protected static ?string $model = JobApplication::class;

    protected static ?string $navigationIcon  = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Faculty & Staff';
    protected static ?string $navigationLabel = 'Job Applications';
    protected static ?int    $navigationSort  = 5;

    protected static array $statusOptions = [
        'new'         => 'New',
        'reviewed'    => 'Reviewed',
        'shortlisted' => 'Shortlisted',
        'hired'       => 'Hired',
        'rejected'    => 'Rejected',
    ];

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_read', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string { return 'warning'; }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Application Details')
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('position'),
                    Infolists\Components\TextEntry::make('name'),
                    Infolists\Components\TextEntry::make('email'),
                    Infolists\Components\TextEntry::make('phone'),
                    Infolists\Components\TextEntry::make('education'),
                    Infolists\Components\TextEntry::make('experience')->placeholder('Not specified'),
                    Infolists\Components\TextEntry::make('message')->label('Cover Letter')->columnSpanFull(),
                    Infolists\Components\TextEntry::make('created_at')->label('Submitted')->dateTime('d M Y, h:i A'),
                    Infolists\Components\TextEntry::make('status')->badge()->formatStateUsing(fn ($state) => static::$statusOptions[$state] ?? $state),
                ]),
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->options(self::$statusOptions)
                ->required(),
            Forms\Components\Textarea::make('admin_notes')
                ->label('Internal Notes')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('position')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => self::$statusOptions[$state] ?? $state)
                    ->color(fn (JobApplication $r) => $r->status_color),
                Tables\Columns\IconColumn::make('is_read')->label('Read')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Submitted')->dateTime('d M Y, h:i A')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(self::$statusOptions),
                Tables\Filters\SelectFilter::make('position')
                    ->options(fn () => JobApplication::distinct()->pluck('position', 'position')),
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\Action::make('downloadCv')
                    ->label('CV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->iconButton()
                    ->visible(fn (JobApplication $r) => filled($r->cv_path))
                    ->url(fn (JobApplication $r) => $r->cv_url)
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make()
                    ->after(fn (JobApplication $r) => $r->update(['is_read' => true])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobApplications::route('/'),
            'view'  => Pages\ViewJobApplication::route('/{record}'),
            'edit'  => Pages\EditJobApplication::route('/{record}/edit'),
        ];
    }
}
