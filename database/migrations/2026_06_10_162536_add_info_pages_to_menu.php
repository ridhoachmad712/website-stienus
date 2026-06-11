<?php

use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Tambahkan Pengumuman, Prestasi, FAQ ke dropdown "Informasi" (jika ada).
     * DB kosong ditangani MenuSeeder.
     */
    public function up(): void
    {
        $informasi = MenuItem::query()->whereNull('parent_id')->where('label', 'Informasi')->first();

        if (! $informasi) {
            return;
        }

        $order = (int) MenuItem::query()->where('parent_id', $informasi->id)->max('order');

        foreach ([
            ['Pengumuman', '/pengumuman'],
            ['Prestasi', '/prestasi'],
            ['FAQ', '/faq'],
        ] as [$label, $url]) {
            if (MenuItem::query()->where('url', $url)->exists()) {
                continue;
            }

            MenuItem::create([
                'parent_id' => $informasi->id,
                'label' => $label,
                'url' => $url,
                'order' => ++$order,
                'is_active' => true,
            ]);
        }
    }

    public function down(): void
    {
        MenuItem::query()->whereIn('url', ['/pengumuman', '/prestasi', '/faq'])->delete();
    }
};
