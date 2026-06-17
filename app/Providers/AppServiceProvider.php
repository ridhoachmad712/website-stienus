<?php

namespace App\Providers;

use App\Models\MenuItem;
use App\Settings\GeneralSettings;
use App\Settings\ThemeSettings;
use Carbon\Carbon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema;
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

        // Default tampilan SEMUA tabel Filament dalam satu tempat: baris belang,
        // opsi paginasi konsisten, dan empty state ber-Bahasa Indonesia. Resource
        // tertentu masih bebas menimpa (mis. emptyStateHeading khusus).
        Table::configureUsing(function (Table $table): void {
            $table
                ->striped()
                ->paginationPageOptions([10, 25, 50, 100])
                ->emptyStateHeading('Belum ada data')
                ->emptyStateDescription('Data akan tampil di sini setelah ditambahkan.')
                ->emptyStateIcon('heroicon-o-inbox');
        });

        // Inject site settings into the public layout (runs only when the
        // front-end layout is rendered, so console/Filament are unaffected).
        View::composer('components.layouts.app', function (\Illuminate\View\View $view): void {
            $view->with('general', app(GeneralSettings::class));
            $view->with('theme', app(ThemeSettings::class));

            // Navigasi dikelola admin via MenuItem; guard agar aman sebelum migrasi.
            $items = Schema::hasTable('menu_items')
                ? MenuItem::query()->topLevel()->get()
                : collect();

            $view->with('menuLinks', $items->where('is_button', false)->values());
            $view->with('menuButtons', $items->where('is_button', true)->values());
        });
    }
}
