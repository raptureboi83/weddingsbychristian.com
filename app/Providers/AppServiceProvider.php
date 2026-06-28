<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        FilamentView::registerRenderHook(
            'panels::global-search.before',
            fn (): string => '<a href="' . e(config('app.url')) . '?v=' . time() . '" target="_blank" style="display:inline-flex;align-items:center;gap:0.25rem;padding:0 0.75rem;font-size:0.875rem;font-weight:500;color:var(--gray-400);text-decoration:none;white-space:nowrap;transition:color 0.15s" onmouseover="this.style.color=\'var(--primary-500)\'" onmouseout="this.style.color=\'var(--gray-400)\'">&larrhk; View Site</a>',
        );
    }
}