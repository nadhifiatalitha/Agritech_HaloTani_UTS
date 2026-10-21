<?php

namespace App\Providers\Filament;

use App\Filament\Auth\EditProfile;
use App\Filament\Auth\LoginPetani;
use App\Filament\Auth\RegisterPetani;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\JadwalTanam;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PetaniPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('petani')
            ->path('petani')
            ->login(LoginPetani::class)
            ->brandName('Agritech')
            ->brandLogo(fn () => view('filament.components.brand'))
            ->sidebarWidth('15rem')
            // ->userMenu(false)
            ->darkMode(false)
            ->registration(RegisterPetani::class)
            ->profile(EditProfile::class, isSimple: false)
            ->passwordReset()
            ->colors([
                'primary' => '#2E7D32',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
                JadwalTanam::class, 
            ])
            // ->navigationItems([
            //     NavigationItem::make('Profil Saya')
            //         ->icon('heroicon-o-user')
            //         ->sort(3)
            //         ->url(fn (): string => filament()->getProfileUrl())
            //         ->isActiveWhen(fn (): bool => request()->routeIs('filament.petani.auth.profile')),
            // ])
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => view('filament.components.sidebar-styles'))
            ->renderHook(PanelsRenderHook::SIDEBAR_FOOTER, fn () => view('filament.components.sidebar-footer'))
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}