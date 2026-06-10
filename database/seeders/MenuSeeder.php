<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent: hanya isi bila menu masih kosong.
        if (MenuItem::query()->exists()) {
            return;
        }

        $order = 1;

        MenuItem::create(['label' => 'Beranda', 'url' => '/', 'order' => $order++]);

        $profil = MenuItem::create(['label' => 'Profil', 'url' => null, 'order' => $order++]);
        foreach ([
            ['Tentang Kami', '/profil'],
            ['Sejarah', '/profil/sejarah'],
            ['Sambutan Pimpinan', '/profil/sambutan'],
            ['Struktur Organisasi', '/profil/struktur'],
        ] as $i => [$label, $url]) {
            MenuItem::create(['parent_id' => $profil->id, 'label' => $label, 'url' => $url, 'order' => $i + 1]);
        }

        $akademik = MenuItem::create(['label' => 'Akademik', 'url' => null, 'order' => $order++]);
        foreach ([
            ['Program Studi', '/program-studi'],
            ['Direktori Dosen', '/dosen'],
        ] as $i => [$label, $url]) {
            MenuItem::create(['parent_id' => $akademik->id, 'label' => $label, 'url' => $url, 'order' => $i + 1]);
        }

        MenuItem::create(['label' => 'Berita', 'url' => '/berita', 'order' => $order++]);
        MenuItem::create(['label' => 'Agenda', 'url' => '/agenda', 'order' => $order++]);
        MenuItem::create(['label' => 'Galeri', 'url' => '/galeri', 'order' => $order++]);
        MenuItem::create(['label' => 'Unduhan', 'url' => '/unduhan', 'order' => $order++]);
        MenuItem::create(['label' => 'Kontak', 'url' => '/kontak', 'order' => $order++]);

        MenuItem::create(['label' => 'Daftar PMB', 'url' => '/pmb', 'order' => $order++, 'is_button' => true]);
    }
}
