<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FooterSettings extends Settings
{
    // Kolom 1 — deskripsi
    public bool $show_description;

    // Kolom 2 — tautan
    public bool $show_links_column;
    public string $links_column_title;
    public bool $use_custom_links;
    public array $custom_links;

    // Kolom 3 — kontak
    public bool $show_contact_column;
    public string $contact_column_title;

    // Kolom 4 — sosial & ekstra
    public bool $show_social_column;
    public string $social_column_title;

    // Kolom tambahan bebas
    public array $extra_columns;

    public static function group(): string
    {
        return 'footer';
    }
}
