<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('home.leaders_eyebrow', 'Profil');
        $this->migrator->add('home.leaders_title', 'Pimpinan');
        $this->migrator->add('home.leaders_subtitle', 'Jajaran pimpinan yang mengarahkan visi dan pengembangan kampus.');
    }
};
