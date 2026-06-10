<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ProfileSettings extends Settings
{
    // Tentang & Visi-Misi
    public ?string $about;

    public ?string $vision;

    public ?string $mission;

    // Sejarah
    public ?string $history;

    // Sambutan Ketua/Pimpinan
    public ?string $leader_name;

    public ?string $leader_title;

    public ?string $leader_photo;

    public ?string $leader_speech;

    // Struktur Organisasi
    public ?string $org_structure_image;

    public ?string $org_structure_text;

    public static function group(): string
    {
        return 'profile';
    }
}
