<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SystemMaintenance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Maintenance';

    protected static ?string $title = 'System Maintenance';

    protected static ?int $navigationSort = 21;

    protected static string $view = 'filament.pages.system-maintenance';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'Developer']) ?? false;
    }

    /**
     * Everything shown on the page — cache/config status, PHP upload limits,
     * and writable checks for the folders uploads/imports actually use. Built
     * fresh on every page load since there is no server terminal to run
     * `php artisan` diagnostics by hand.
     */
    public function diagnostics(): array
    {
        $paths = [
            'Livewire temp uploads' => storage_path('app/public/livewire-tmp'),
            'Student import uploads' => storage_path('app/private/imports/students'),
            'Teacher import uploads' => storage_path('app/private/imports/teachers'),
            'Compiled views' => storage_path('framework/views'),
            'Framework cache' => storage_path('framework/cache'),
            'Bootstrap cache' => base_path('bootstrap/cache'),
        ];

        $writable = [];
        foreach ($paths as $label => $path) {
            $writable[] = [
                'label' => $label,
                'path' => $path,
                'exists' => File::isDirectory($path),
                'writable' => File::isDirectory($path) && is_writable($path),
            ];
        }

        $logPath = storage_path('logs/laravel.log');
        $logSize = File::exists($logPath) ? File::size($logPath) : 0;
        $logsDirSize = 0;
        if (File::isDirectory(storage_path('logs'))) {
            foreach (File::allFiles(storage_path('logs')) as $file) {
                $logsDirSize += $file->getSize();
            }
        }

        $freeBytes = @disk_free_space(storage_path());

        return [
            'env' => [
                'APP_ENV' => config('app.env'),
                'APP_DEBUG' => config('app.debug') ? 'true (should be false in production)' : 'false',
                'QUEUE_CONNECTION' => config('queue.default'),
                'LOG_CHANNEL' => config('logging.default'),
                'LOG_STACK_CHANNELS' => implode(', ', config('logging.channels.stack.channels', [])),
            ],
            'php' => [
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'max_execution_time' => ini_get('max_execution_time') . 's',
                'memory_limit' => ini_get('memory_limit'),
            ],
            'writable' => $writable,
            'storage_symlink' => is_link(public_path('storage')) || File::exists(public_path('storage')),
            'log_file_size' => $this->formatBytes($logSize),
            'logs_dir_total' => $this->formatBytes($logsDirSize),
            'disk_free' => $freeBytes ? $this->formatBytes((int) $freeBytes) : 'unknown',
        ];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('clearConfigCache')
                    ->label('Clear Config Cache')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->action(fn () => $this->runArtisan('config:clear', 'Config cache cleared.')),

                Action::make('clearRouteCache')
                    ->label('Clear Route Cache')
                    ->icon('heroicon-o-map')
                    ->action(fn () => $this->runArtisan('route:clear', 'Route cache cleared.')),

                Action::make('clearViewCache')
                    ->label('Clear View Cache')
                    ->icon('heroicon-o-document-text')
                    ->action(fn () => $this->runArtisan('view:clear', 'Compiled views cleared.')),

                Action::make('clearAppCache')
                    ->label('Clear App Cache')
                    ->icon('heroicon-o-trash')
                    ->action(fn () => $this->runArtisan('cache:clear', 'Application cache cleared.')),
            ])
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->button()
                ->color('gray'),

            ActionGroup::make([
                Action::make('restartQueue')
                    ->label('Restart Queue Workers')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->modalDescription('Signals running queue:work processes to finish their current job and restart, so they pick up the latest code and .env changes.')
                    ->action(fn () => $this->runArtisan('queue:restart', 'Queue restart signal sent.')),

                Action::make('createStorageLink')
                    ->label('Re-create Storage Link')
                    ->icon('heroicon-o-link')
                    ->requiresConfirmation()
                    ->modalDescription('Only needed if uploaded images/files stopped showing on the site (broken public/storage link).')
                    ->action(fn () => $this->runArtisan('storage:link', 'Storage link re-created.')),

                Action::make('reseedNotificationTemplates')
                    ->label('Reset Notification Templates to Default')
                    ->icon('heroicon-o-envelope')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('Overwrites every notification template (subject/body) back to the built-in defaults, discarding any custom edits made in Notification Templates.')
                    ->action(fn () => $this->runArtisan('db:seed --class=NotificationTemplateSeeder --force', 'Notification templates reset to default.')),
            ])
                ->label('More Actions')
                ->icon('heroicon-o-ellipsis-vertical')
                ->button()
                ->color('gray'),
        ];
    }

    private function runArtisan(string $command, string $successMessage): void
    {
        try {
            [$name, $arguments] = $this->parseCommand($command);
            Artisan::call($name, $arguments);

            Notification::make()
                ->title($successMessage)
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Command failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function parseCommand(string $command): array
    {
        $parts = explode(' --class=', $command);

        if (count($parts) === 2) {
            $className = trim(strtok($parts[1], ' '));

            return [trim($parts[0]), ['--class' => $className, '--force' => true]];
        }

        return [trim($command), []];
    }
}
