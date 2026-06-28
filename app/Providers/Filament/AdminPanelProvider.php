<?php

namespace App\Providers\Filament;

use App\Http\Controllers\ChunkedFilmUploadController;
use App\Http\Controllers\ChunkedMediaUploadController;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
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
            ->passwordReset()
            ->brandName('WBC Admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                \App\Filament\Widgets\DashboardStatsWidget::class,
            ])
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
            ])
            ->authenticatedRoutes(function () {
                Route::post('/films/upload/chunk', [ChunkedFilmUploadController::class, 'chunk'])
                    ->name('films.upload.chunk');

                Route::post('/films/upload/finalize', [ChunkedFilmUploadController::class, 'finalize'])
                    ->name('films.upload.finalize');

                Route::post('/media/upload/chunk', [ChunkedMediaUploadController::class, 'chunk'])
                    ->name('media.upload.chunk');

                Route::post('/media/upload/finalize', [ChunkedMediaUploadController::class, 'finalize'])
                    ->name('media.upload.finalize');

                Route::get('/media/list', [ChunkedMediaUploadController::class, 'list'])
                    ->name('media.list');
            });
    }
}