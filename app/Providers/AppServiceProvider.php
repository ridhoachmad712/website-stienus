<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use App\Settings\ThemeSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
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
        // Localized date formatting (Carbon::translatedFormat) in Bahasa Indonesia.
        Carbon::setLocale('id');

        // Inject site settings into the public layout (runs only when the
        // front-end layout is rendered, so console/Filament are unaffected).
        View::composer('components.layouts.app', function (\Illuminate\View\View $view): void {
            $view->with('general', app(GeneralSettings::class));
            $view->with('theme', app(ThemeSettings::class));
        });
    }
}
