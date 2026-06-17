<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -1;

    // Konten statis (tanpa query) — render langsung tanpa skeleton lazy.
    protected static bool $isLazy = false;

    /**
     * Greeting + quick links surfaced to the admin on login.
     *
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $hour = (int) now()->format('H');
        $greeting = match (true) {
            $hour < 11 => 'Selamat pagi',
            $hour < 15 => 'Selamat siang',
            $hour < 19 => 'Selamat sore',
            default => 'Selamat malam',
        };

        return [
            'greeting' => $greeting,
            'name' => Auth::user()?->name ?? 'Admin',
            'today' => now()->translatedFormat('l, d F Y'),
        ];
    }
}
