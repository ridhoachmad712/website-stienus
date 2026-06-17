<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomeSettings extends Settings
{
    // Tipe tampilan hero: 'text' atau 'slider'
    public string $hero_type;

    // Hero
    public string $hero_badge;

    public string $hero_title;

    public string $hero_highlight;

    public string $hero_subtitle;

    public string $hero_cta1_text;

    public string $hero_cta1_url;

    public string $hero_cta2_text;

    public string $hero_cta2_url;

    // Foto sisi kanan hero (opsional; menggantikan kartu statistik bila diisi).
    public ?string $hero_side_image;

    // News section
    public string $news_eyebrow;

    public string $news_title;

    // Programs section
    public string $programs_eyebrow;

    public string $programs_title;

    public string $programs_subtitle;

    // Leaders / Pimpinan section
    public string $leaders_eyebrow;

    public string $leaders_title;

    public string $leaders_subtitle;

    // Agenda section
    public string $agenda_eyebrow;

    public string $agenda_title;

    // Video profil (YouTube)
    public ?string $video_url;

    public string $video_title;

    public string $video_subtitle;

    // Call to action
    public string $cta_title;

    public string $cta_subtitle;

    public string $cta_button_text;

    public string $cta_button_url;

    public static function group(): string
    {
        return 'home';
    }
}
