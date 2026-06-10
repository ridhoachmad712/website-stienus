<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // 'text' = hero teks; 'slider' = slider gambar. Default slider bila
        // ada slide aktif, jika tidak otomatis fallback ke teks pada tampilan.
        $this->migrator->add('home.hero_type', 'slider');
    }
};
