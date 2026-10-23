<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diplomas', function (Blueprint $table) {
            // Tempat & tanggal lahir dibutuhkan untuk badan ijazah cetak
            // ("Lahir di Makassar, 2 Mei 2003"). Nullable agar aman untuk
            // catatan/impor lama yang belum memilikinya; kelengkapan untuk
            // cetak ditegakkan di form dan sebelum mencetak.
            $table->string('birth_place', 120)->nullable()->after('student_number');
            $table->date('birth_date')->nullable()->after('birth_place');
        });
    }

    public function down(): void
    {
        Schema::table('diplomas', function (Blueprint $table) {
            $table->dropColumn(['birth_place', 'birth_date']);
        });
    }
};
