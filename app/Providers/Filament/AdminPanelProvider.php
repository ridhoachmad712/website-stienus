<?php

namespace App\Providers\Filament;

use App\Settings\GeneralSettings;
use App\Settings\ThemeSettings;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // Ambil identitas dari Pengaturan. Settings spatie dimuat lazy saat properti
        // diakses, jadi seluruh akses dibungkus rescue() agar aman bila tabel
        // settings belum ada (mis. saat migrate / boot pengujian).
        $brand = rescue(function (): array {
            $general = app(GeneralSettings::class);
            $theme = app(ThemeSettings::class);

            return [
                'name' => $general->site_name,
                'primary' => $theme->primary_color,
                'logo' => $general->logo ? Storage::disk('public')->url($general->logo) : null,
                'favicon' => $general->favicon ? Storage::disk('public')->url($general->favicon) : null,
            ];
        }, [], report: false);

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName($brand['name'] ?? 'STIE Nusantara Makassar')
            ->brandLogo($brand['logo'] ?? null)
            ->brandLogoHeight('2.25rem')
            ->favicon($brand['favicon'] ?? null)
            ->colors([
                'primary' => Color::hex($brand['primary'] ?? '#4f46e5'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->navigationGroups([
                'Berita & Informasi',
                'Akademik',
                'Penerimaan Mahasiswa',
                'Tampilan Beranda',
                'Pengaturan',
                'Pengguna & Akses',
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])
            ->plugin(FilamentShieldPlugin::make());
    }
}
