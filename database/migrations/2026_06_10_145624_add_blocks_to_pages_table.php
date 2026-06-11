<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->json('blocks')->nullable()->after('content');
        });

        // Konversi konten lama menjadi satu blok "rich_text" agar tetap tampil.
        foreach (DB::table('pages')->whereNotNull('content')->get(['id', 'content']) as $page) {
            if (blank($page->content)) {
                continue;
            }

            DB::table('pages')->where('id', $page->id)->update([
                'blocks' => json_encode([
                    ['type' => 'rich_text', 'data' => ['content' => $page->content]],
                ]),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('blocks');
        });
    }
};
