<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('clearLogs')
                ->label('Clear All Logs')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->visible(fn () => auth()->user()?->hasRole(config('filament-shield.super_admin.name')))
                ->requiresConfirmation()
                ->modalHeading('Clear all activity logs?')
                ->modalDescription('This permanently deletes every activity-log entry. This cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete all logs')
                ->action(function () {
                    $count = ActivityLog::query()->count();
                    ActivityLog::query()->delete();

                    Notification::make()
                        ->title("Cleared {$count} activity log entr" . ($count === 1 ? 'y' : 'ies'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
