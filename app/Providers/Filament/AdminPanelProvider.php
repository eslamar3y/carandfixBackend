<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\ClockWidget;
use App\Filament\Widgets\DateWidget;
use App\Filament\Widgets\OrdersByStatusChart;
use App\Filament\Widgets\OrdersOverTime;
use App\Filament\Resources\Shield\RoleResource;
use App\Filament\Widgets\StatsOverview;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\SetLocaleFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->brandName('Click & Fix')
            ->navigationGroups([
                NavigationGroup::make()->label(fn() => __('nav.System')),
                NavigationGroup::make()->label(fn() => __('nav.Orders')),
                NavigationGroup::make()->label(fn() => __('nav.Cars')),
                NavigationGroup::make()->label(fn() => __('nav.ServicesRepairs')),
                NavigationGroup::make()->label(fn() => __('nav.Invoices')),
                NavigationGroup::make()->label(fn() => __('nav.Content')),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->resources([
                RoleResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                DateWidget::class,
                ClockWidget::class,
                StatsOverview::class,
                OrdersOverTime::class,
                OrdersByStatusChart::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
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
                SetLocaleFromSession::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
