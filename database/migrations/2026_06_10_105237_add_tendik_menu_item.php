<?php

use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Tambahkan menu "Tenaga Kependidikan" (/tendik) sebagai submenu Akademik,
     * tepat setelah "Direktori Dosen". Hanya untuk instalasi yang sudah punya
     * menu; DB kosong ditangani MenuSeeder.
     */
    public function up(): void
    {
        $dosen = MenuItem::query()->where('url', '/dosen')->whereNotNull('parent_id')->first();

        if (! $dosen || MenuItem::query()->where('url', '/tendik')->exists()) {
            return;
        }

        // Beri ruang: geser semua item setelah "Direktori Dosen".
        MenuItem::query()->where('order', '>', $dosen->order)->increment('order');

        MenuItem::create([
            'parent_id' => $dosen->parent_id,
            'label' => 'Tenaga Kependidikan',
            'url' => '/tendik',
            'order' => $dosen->order + 1,
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        MenuItem::query()->where('url', '/tendik')->delete();
    }
};
