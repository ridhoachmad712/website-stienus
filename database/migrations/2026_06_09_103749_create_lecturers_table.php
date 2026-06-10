<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('nidn')->unique();
            $table->string('name');
            $table->string('title')->nullable(); // academic title / gelar, e.g. S.Kom., M.Kom.
            $table->string('photo')->nullable();
            $table->text('expertise')->nullable(); // bidang keahlian
            $table->string('google_scholar_link')->nullable();
            $table->string('sinta_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
