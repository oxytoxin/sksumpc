<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $customcss = Vite::asset('resources/css/filament/app/theme.css');
        return $panel
            ->id('app')
            ->path('')
            ->domain(config('app.env') == "local" ? "localhost" : config('app.url'))
            ->colors([
                'primary' => "#3F9FEB",
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Management')
                    ->icon('heroicon-o-cog-6-tooth')
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->maxContentWidth('full')
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
            ->viteTheme('resources/css/filament/app/theme.css')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->breadcrumbs(false)
            ->darkMode(false)
            ->renderHook(
                'panels::body.end',
                fn (): string => Blade::render("
                <div x-data='{
                init(){
                    Livewire.hook(`commit`, ({ succeed }) => {
                        succeed(() => {
                            setTimeout(() => {
                                const firstErrorMessage = document.querySelector(`[data-validation-error]`)
                    
                                if (firstErrorMessage !== null) {
                                    firstErrorMessage.scrollIntoView({ block: `center`, inline: `center` })
                                }
                            }, 0)
                        })
                    })
                    }
                }'>
                </div>
                <script>
                    function printOut(data, title) {
                        var mywindow = window.open('', title, 'height=1000,width=1000');
                        mywindow.document.write('<html><head>');
                        mywindow.document.write('<title>' + title + '</title>');
                        mywindow.document.write(`<link rel='stylesheet' href='{$customcss}' /></head><body >`);
                        mywindow.document.write(data);
                        mywindow.document.close();
                        mywindow.focus();
                        setTimeout(() => {
                            mywindow.print();
                        }, 1000);
                        return false;
                    }
                </script>
                ")
            )
            ->favicon(asset('images/logo.jpg'));
    }
}
