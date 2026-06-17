<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Foto di sisi kanan hero (menggantikan kartu statistik bila diisi).
        $this->migrator->add('home.hero_side_image', null);
    }
};
