<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('content');
            $table->json('blocks')->nullable()->after('excerpt');
        });

        // Konversi konten lama → blok teks + ringkasan untuk daftar/SEO.
        foreach (DB::table('posts')->get(['id', 'content']) as $post) {
            if (blank($post->content)) {
                continue;
            }

            DB::table('posts')->where('id', $post->id)->update([
                'excerpt' => Str::limit(trim(strip_tags($post->content)), 200),
                'blocks' => json_encode([
                    ['type' => 'rich_text', 'data' => ['content' => $post->content]],
                ]),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'blocks']);
        });
    }
};
