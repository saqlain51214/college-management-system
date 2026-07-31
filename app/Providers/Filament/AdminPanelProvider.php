<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Enums\ThemeMode;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->darkMode(true)
            ->defaultThemeMode(ThemeMode::Dark)
            ->brandName(fn() => \App\Models\CollegeSetting::get('college_name', 'JDCA'))
            ->brandLogo(function () {
                $logo = \App\Models\CollegeSetting::get('college_logo');
                return $logo ? url('storage/' . $logo) : null;
            })
            ->brandLogoHeight('2.5rem')
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->profile(EditProfile::class, isSimple: false)
            ->colors([
                'primary' => Color::hex('#6B2D39'), // JDCA brand maroon
                'gray'    => Color::Slate,
            ])
            ->maxContentWidth(MaxWidth::Full)
            ->sidebarWidth('18rem')
            ->renderHook(
                'panels::head.end',
                fn (): string => '<style>
                    .jdca-review-row, .jdca-review-row td { background-color: rgba(251, 191, 36, 0.14) !important; }
                    /* Give tables the full content width to shrink before falling back to horizontal scroll */
                    .fi-ta-content { width: 100%; }
                    .fi-ta-table { width: 100%; table-layout: auto; }
                </style>',
            )
            ->plugins([
                FilamentShieldPlugin::make()
                    ->localizePermissionLabels()
                    ->gridColumns(1)
                    ->sectionColumns(1)
                    ->checkboxListColumns([
                        'md' => 2,
                        'xl' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'md' => 2,
                        'xl' => 3,
                    ]),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make('Website Pages')
                    ->icon('heroicon-o-globe-alt')
                    ->collapsed(false),

                NavigationGroup::make('Students & Admissions')
                    ->icon('heroicon-o-user-group')
                    ->collapsed(false),

                NavigationGroup::make('Faculty & Staff')
                    ->icon('heroicon-o-users')
                    ->collapsed(false),

                NavigationGroup::make('Fees & Billing')
                    ->icon('heroicon-o-banknotes')
                    ->collapsed(false),

                NavigationGroup::make('Scholarships')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsed(false),

                NavigationGroup::make('Payroll')
                    ->icon('heroicon-o-wallet')
                    ->collapsed(false),

                NavigationGroup::make('Communications')
                    ->icon('heroicon-o-envelope')
                    ->collapsed(true),

                NavigationGroup::make('System')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(true),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                \App\Http\Middleware\SetGuardSessionCookie::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
