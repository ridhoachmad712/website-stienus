<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // ---- General ----
        $this->migrator->add('general.site_name', 'STIE Nusantara Makassar');
        $this->migrator->add('general.site_full_name', 'Sekolah Tinggi Ilmu Ekonomi Nusantara Makassar');
        $this->migrator->add('general.logo', null);
        $this->migrator->add('general.favicon', null);
        $this->migrator->add('general.address', 'Makassar, Sulawesi Selatan');
        $this->migrator->add('general.phone', '(0411) 123-456');
        $this->migrator->add('general.email', 'info@stienusantara.ac.id');
        $this->migrator->add('general.social_facebook', null);
        $this->migrator->add('general.social_instagram', null);
        $this->migrator->add('general.social_youtube', null);
        $this->migrator->add('general.social_x', null);
        $this->migrator->add('general.footer_description', 'Sekolah Tinggi Ilmu Ekonomi yang berkomitmen menghasilkan lulusan unggul di bidang akuntansi dan manajemen, profesional, dan berdaya saing.');
        $this->migrator->add('general.copyright_text', 'STIE Nusantara Makassar. Hak cipta dilindungi.');

        // ---- Theme ----
        $this->migrator->add('theme.primary_color', '#4f46e5');
        $this->migrator->add('theme.hero_image', null);

        // ---- Homepage ----
        $this->migrator->add('home.hero_badge', 'Penerimaan Mahasiswa Baru 2026 Dibuka');
        $this->migrator->add('home.hero_title', 'Membangun Profesional Ekonomi Masa Depan');
        $this->migrator->add('home.hero_highlight', 'Profesional Ekonomi');
        $this->migrator->add('home.hero_subtitle', 'STIE Nusantara Makassar menghadirkan program studi Akuntansi dan Manajemen yang unggul, dosen berpengalaman, dan lingkungan akademik yang inspiratif untuk mengembangkan potensimu.');
        $this->migrator->add('home.hero_cta1_text', 'Jelajahi Program Studi');
        $this->migrator->add('home.hero_cta1_url', '/program-studi');
        $this->migrator->add('home.hero_cta2_text', 'Baca Berita Terkini');
        $this->migrator->add('home.hero_cta2_url', '/berita');

        $this->migrator->add('home.news_eyebrow', 'Informasi Terkini');
        $this->migrator->add('home.news_title', 'Berita & Pengumuman');

        $this->migrator->add('home.programs_eyebrow', 'Akademik');
        $this->migrator->add('home.programs_title', 'Program Studi Unggulan');
        $this->migrator->add('home.programs_subtitle', 'Pilihan program studi terakreditasi dengan kurikulum relevan dan dosen kompeten di bidangnya.');

        $this->migrator->add('home.agenda_eyebrow', 'Jadwal');
        $this->migrator->add('home.agenda_title', 'Agenda Mendatang');

        $this->migrator->add('home.cta_title', 'Siap Menjadi Bagian dari Kami?');
        $this->migrator->add('home.cta_subtitle', 'Bergabunglah bersama STIE Nusantara Makassar dan bangun karier cemerlang di bidang akuntansi dan manajemen.');
        $this->migrator->add('home.cta_button_text', 'Daftar Sekarang');
        $this->migrator->add('home.cta_button_url', '/admin');
    }
};
