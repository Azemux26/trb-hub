<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
// use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
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

            // Brand
           
            ->brandLogo(fn () => view('filament.brand'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/logo-ami.png'))

            // Colors
            ->colors([
                'primary' => [
                    50  => '#e8e8f8',
                    100 => '#c5c5ed',
                    200 => '#9999dd',
                    300 => '#6666cc',
                    400 => '#4444b8',
                    500 => '#2e2e9e',
                    600 => '#1a1a7a',
                    700 => '#141460',
                    800 => '#0e0e46',
                    900 => '#08082c',
                    950 => '#04041a',
                ],
                'warning' => [
                    50  => '#fdfae6',
                    100 => '#faf3b3',
                    200 => '#f5e76b',
                    300 => '#edd922',
                    400 => '#e0c800',
                    500 => '#c4ad00',
                    600 => '#9e8b00',
                    700 => '#786900',
                    800 => '#524800',
                    900 => '#2c2700',
                    950 => '#161300',
                ],
                'danger' => [
                    50  => '#fce8e8',
                    100 => '#f5bcbc',
                    200 => '#ec8585',
                    300 => '#e04e4e',
                    400 => '#d42424',
                    500 => '#cc1a1a',
                    600 => '#a81414',
                    700 => '#840f0f',
                    800 => '#5e0909',
                    900 => '#3a0404',
                    950 => '#1e0202',
                ],
                'success' => [
                    50  => '#e8f5ec',
                    100 => '#bce4c8',
                    200 => '#85cc9e',
                    300 => '#4eb274',
                    400 => '#269950',
                    500 => '#1a7a3a',
                    600 => '#136030',
                    700 => '#0d4822',
                    800 => '#083016',
                    900 => '#03180a',
                    950 => '#010c05',
                ],
                'info' => [
                    50  => '#e8f0fa',
                    100 => '#bcd3f0',
                    200 => '#85aee4',
                    300 => '#4e88d6',
                    400 => '#2669c4',
                    500 => '#1a5a9a',
                    600 => '#134878',
                    700 => '#0d3659',
                    800 => '#08243c',
                    900 => '#03121e',
                    950 => '#010810',
                ],
            ])

            // Dark mode — ikut OS, user bisa toggle
            ->darkMode(true, isForced: false)

            ->viteTheme('resources/css/filament/admin/theme.css')

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
                \App\Filament\Pages\LaporanPdf::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
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