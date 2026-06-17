<?php

namespace App\Providers;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // All table row actions → icon-only with label as tooltip on hover
        EditAction::configureUsing(fn($a)        => $a->iconButton());
        DeleteAction::configureUsing(fn($a)      => $a->iconButton());
        ForceDeleteAction::configureUsing(fn($a) => $a->iconButton());
        RestoreAction::configureUsing(fn($a)     => $a->iconButton());
    }
}
