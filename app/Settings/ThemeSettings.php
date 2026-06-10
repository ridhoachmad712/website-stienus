<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ThemeSettings extends Settings
{
    /** Primary brand color (hex), drives the whole brand palette. */
    public string $primary_color;

    /** Optional hero background image path (public disk). */
    public ?string $hero_image;

    public static function group(): string
    {
        return 'theme';
    }
}
