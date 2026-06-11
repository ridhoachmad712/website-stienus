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

        // Counter global agar nilai "order" unik & runtut (induk lalu anak-anaknya),
        // sehingga tabel admin tampil rapi dan mudah digeser.
        $order = 0;
        $create = fn (array $attributes) => MenuItem::create([...$attributes, 'order' => ++$order]);

        $create(['label' => 'Beranda', 'url' => '/']);

        $profil = $create(['label' => 'Profil', 'url' => null]);
        foreach ([
            ['Tentang Kami', '/profil'],
            ['Sejarah', '/profil/sejarah'],
            ['Sambutan Pimpinan', '/profil/sambutan'],
            ['Struktur Organisasi', '/profil/struktur'],
        ] as [$label, $url]) {
            $create(['parent_id' => $profil->id, 'label' => $label, 'url' => $url]);
        }

        $akademik = $create(['label' => 'Akademik', 'url' => null]);
        foreach ([
            ['Program Studi', '/program-studi'],
            ['Direktori Dosen', '/dosen'],
            ['Tenaga Kependidikan', '/tendik'],
        ] as [$label, $url]) {
            $create(['parent_id' => $akademik->id, 'label' => $label, 'url' => $url]);
        }

        $informasi = $create(['label' => 'Informasi', 'url' => null]);
        foreach ([
            ['Berita', '/berita'],
            ['Pengumuman', '/pengumuman'],
            ['Agenda', '/agenda'],
            ['Prestasi', '/prestasi'],
            ['Galeri', '/galeri'],
            ['Unduhan', '/unduhan'],
            ['FAQ', '/faq'],
        ] as [$label, $url]) {
            $create(['parent_id' => $informasi->id, 'label' => $label, 'url' => $url]);
        }

        $create(['label' => 'Kontak', 'url' => '/kontak']);

        $create(['label' => 'Daftar PMB', 'url' => '/pmb', 'is_button' => true]);
    }
}
