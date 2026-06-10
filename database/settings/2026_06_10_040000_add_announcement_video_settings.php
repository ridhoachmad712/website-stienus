<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Announcement bar (top of every page)
        $this->migrator->add('general.announcement_enabled', false);
        $this->migrator->add('general.announcement_text', 'Pendaftaran Mahasiswa Baru 2026/2027 telah dibuka!');
        $this->migrator->add('general.announcement_url', '/pmb');

        // Homepage profile video (YouTube)
        $this->migrator->add('home.video_url', null);
        $this->migrator->add('home.video_title', 'Mengenal Lebih Dekat');
        $this->migrator->add('home.video_subtitle', 'Tonton video profil singkat STIE Nusantara Makassar.');
    }
};
