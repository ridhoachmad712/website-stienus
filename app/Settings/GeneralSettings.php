<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public string $site_full_name;

    public ?string $logo;

    public ?string $favicon;

    public string $address;

    public string $phone;

    public string $email;

    public ?string $whatsapp;

    public ?string $map_embed;

    public ?string $social_facebook;

    public ?string $social_instagram;

    public ?string $social_youtube;

    public ?string $social_x;

    public string $footer_description;

    public string $copyright_text;

    public bool $announcement_enabled;

    public ?string $announcement_text;

    public ?string $announcement_url;

    public static function group(): string
    {
        return 'general';
    }
}
