<?php

use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Reindex nilai "order" menu menjadi unik & runtut secara hierarkis
     * (induk lalu anak-anaknya), agar tabel admin tampil rapi dan
     * pengurutan via geser (drag-and-drop) bekerja konsisten.
     */
    public function up(): void
    {
        $order = 0;

        MenuItem::query()
            ->whereNull('parent_id')
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->each(function (MenuItem $parent) use (&$order): void {
                $parent->updateQuietly(['order' => ++$order]);

                $parent->children()
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get()
                    ->each(function (MenuItem $child) use (&$order): void {
                        $child->updateQuietly(['order' => ++$order]);
                    });
            });
    }

    public function down(): void
    {
        // Tidak perlu dikembalikan; urutan tetap valid.
    }
};
