<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->unsignedInteger('order')->default(0)->after('program_id');
        });

        // Isi urutan awal mengikuti abjad nama agar nilainya unik & rapi.
        $order = 0;
        foreach (DB::table('lecturers')->orderBy('name')->pluck('id') as $id) {
            DB::table('lecturers')->where('id', $id)->update(['order' => ++$order]);
        }
    }

    public function down(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
