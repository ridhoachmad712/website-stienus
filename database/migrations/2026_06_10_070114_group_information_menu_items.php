<?php

use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Kelompokkan menu Berita, Agenda, Galeri, Unduhan ke dalam satu dropdown
     * "Informasi". Hanya berjalan untuk instalasi yang sudah memiliki struktur
     * menu lama (datar); pada DB kosong dibiarkan agar MenuSeeder yang mengisi.
     */
    public function up(): void
    {
        $childUrls = ['/berita', '/agenda', '/galeri', '/unduhan'];

        $berita = MenuItem::query()->whereNull('parent_id')->where('url', '/berita')->first();

        // Tidak ada struktur lama (DB kosong / sudah dikelompokkan) → lewati.
        if (! $berita) {
            return;
        }

        $parent = MenuItem::query()->firstOrCreate(
            ['label' => 'Informasi', 'parent_id' => null],
            ['url' => null, 'order' => $berita->order, 'is_active' => true],
        );

        $order = 1;
        foreach ($childUrls as $url) {
            MenuItem::query()
                ->whereNull('parent_id')
                ->where('url', $url)
                ->update(['parent_id' => $parent->id, 'order' => $order++]);
        }
    }

    /**
     * Kembalikan item menjadi menu utama dan hapus induk "Informasi".
     */
    public function down(): void
    {
        $parent = MenuItem::query()->whereNull('parent_id')->where('label', 'Informasi')->first();

        if (! $parent) {
            return;
        }

        MenuItem::query()->where('parent_id', $parent->id)->update(['parent_id' => null]);
        $parent->delete();
    }
};
