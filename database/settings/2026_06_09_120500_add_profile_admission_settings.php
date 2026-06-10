<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // ---- General additions ----
        $this->migrator->add('general.whatsapp', '6281234567890');
        $this->migrator->add('general.map_embed', null);

        // ---- Profile ----
        $this->migrator->add('profile.about', '<p>STIE Nusantara Makassar adalah perguruan tinggi yang berfokus pada ilmu ekonomi, menyelenggarakan program studi Akuntansi dan Manajemen jenjang Sarjana (S1).</p>');
        $this->migrator->add('profile.vision', 'Menjadi sekolah tinggi ilmu ekonomi yang unggul, profesional, dan berdaya saing di kawasan timur Indonesia.');
        $this->migrator->add('profile.mission', "<ul><li>Menyelenggarakan pendidikan ekonomi berkualitas.</li><li>Mengembangkan penelitian yang berdampak.</li><li>Melaksanakan pengabdian kepada masyarakat.</li></ul>");
        $this->migrator->add('profile.history', '<p>Didirikan untuk menjawab kebutuhan tenaga profesional di bidang ekonomi dan bisnis di Makassar, STIE Nusantara terus berkembang melayani masyarakat.</p>');
        $this->migrator->add('profile.leader_name', 'Dr. Nama Ketua, S.E., M.M.');
        $this->migrator->add('profile.leader_title', 'Ketua STIE Nusantara Makassar');
        $this->migrator->add('profile.leader_photo', null);
        $this->migrator->add('profile.leader_speech', '<p>Selamat datang di portal resmi STIE Nusantara Makassar. Kami berkomitmen mencetak lulusan yang kompeten, berintegritas, dan siap menghadapi tantangan dunia kerja.</p>');
        $this->migrator->add('profile.org_structure_image', null);
        $this->migrator->add('profile.org_structure_text', '<p>Struktur organisasi STIE Nusantara Makassar terdiri dari Ketua, Wakil Ketua, Ketua Program Studi, dan unit penunjang akademik.</p>');

        // ---- Admission (PMB) ----
        $this->migrator->add('admission.headline', 'Penerimaan Mahasiswa Baru');
        $this->migrator->add('admission.subheadline', 'Bergabunglah bersama STIE Nusantara Makassar tahun akademik 2026/2027.');
        $this->migrator->add('admission.intro', '<p>Jadilah bagian dari kampus ekonomi unggulan di Makassar. Daftarkan dirimu sekarang dan raih masa depan cemerlang bersama kami.</p>');
        $this->migrator->add('admission.steps', [
            ['title' => 'Isi Formulir', 'description' => 'Lengkapi formulir pendaftaran online di halaman ini.'],
            ['title' => 'Verifikasi', 'description' => 'Tim PMB akan menghubungi Anda untuk verifikasi data.'],
            ['title' => 'Tes Seleksi', 'description' => 'Ikuti tes seleksi sesuai jadwal yang ditentukan.'],
            ['title' => 'Daftar Ulang', 'description' => 'Lakukan registrasi ulang setelah dinyatakan lulus.'],
        ]);
        $this->migrator->add('admission.schedule', '<p>Gelombang I: Januari - Maret<br>Gelombang II: April - Juni<br>Gelombang III: Juli - Agustus</p>');
        $this->migrator->add('admission.fee_info', '<p>Informasi biaya pendidikan dapat diperoleh dengan menghubungi panitia PMB.</p>');
        $this->migrator->add('admission.brochure', null);
        $this->migrator->add('admission.form_enabled', true);
    }
};
