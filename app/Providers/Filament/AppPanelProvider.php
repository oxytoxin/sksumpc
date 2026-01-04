<?php

    namespace App\Providers\Filament;

    use Illuminate\View\View;
    use Throwable;
    use App\Http\Middleware\EnsurePresentBookkeeperTransactionDate;
    use App\Models\TransactionDateHistory;
    use Filament\Http\Middleware\Authenticate;
    use Filament\Http\Middleware\DisableBladeIconComponents;
    use Filament\Http\Middleware\DispatchServingFilamentEvent;
    use Filament\Navigation\NavigationGroup;
    use Filament\Panel;
    use Filament\PanelProvider;
    use Filament\Support\Assets\Js;
    use Filament\Support\Facades\FilamentAsset;
    use Filament\Support\Facades\FilamentView;
    use Filament\View\PanelsRenderHook;
    use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
    use Illuminate\Cookie\Middleware\EncryptCookies;
    use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
    use Illuminate\Routing\Middleware\SubstituteBindings;
    use Illuminate\Session\Middleware\AuthenticateSession;
    use Illuminate\Session\Middleware\StartSession;
    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\Facades\Vite;
    use Illuminate\View\Middleware\ShareErrorsFromSession;

    class AppPanelProvider extends PanelProvider
    {
        public function panel(Panel $panel): Panel
        {
            FilamentAsset::register([
                Js::make('app', __DIR__.'/../../../resources/js/app.js'),
            ]);

            try {
                $transaction_date = TransactionDateHistory::current_date();
            } catch (Throwable $th) {
            }
            config(['app.transaction_date' => $transaction_date ?? null]);
            if (isset($transaction_date)) {
                FilamentView::registerRenderHook(
                    PanelsRenderHook::SIDEBAR_LOGO_AFTER,
                    fn() => Blade::render('<strong>Transaction Date: '.$transaction_date->format('m/d/Y').'</strong>')
                );
            } else {
                FilamentView::registerRenderHook(
                    PanelsRenderHook::SIDEBAR_LOGO_AFTER,
                    fn() => Blade::render('<strong>Transaction Date Not Set</strong>')
                );
            }
            FilamentView::registerRenderHook(
                PanelsRenderHook::CONTENT_START,
                fn() => Blade::render("@livewire('bookkeeper-transaction-date-checker')")
            );
            FilamentView::registerRenderHook(
                PanelsRenderHook::CONTENT_START,
                fn() => Blade::render("@livewire('cashier-revolving-fund-replenishment-checker')")
            );

            return $panel
                ->id('app')
                ->path('')
                ->spa()
                ->colors([
                    'primary' => '#3F9FEB',
                ])
                ->topbar(false)
                ->login()
                ->navigationGroups([
                    NavigationGroup::make()
                        ->label('Cashier')
                        ->icon('icon-membership'),
                    NavigationGroup::make()
                        ->label('Loan')
                        ->icon('icon-loan'),
                    NavigationGroup::make()
                        ->label('Share Capital')
                        ->icon('icon-share-capital'),
                    NavigationGroup::make()
                        ->label('Bookkeeping')
                        ->icon('icon-registry'),
                    NavigationGroup::make()
                        ->label('Management')
                        ->icon('heroicon-o-cog-6-tooth'),
                ])
                ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
                ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
                ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
                ->maxContentWidth('full')
                ->middleware([
                    EnsurePresentBookkeeperTransactionDate::class,
                ], true)
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
                    fn(): View => view('filament.app.views.body-end-render-hook')
                )
                ->favicon(asset('images/logo.jpg'));
        }
    }
