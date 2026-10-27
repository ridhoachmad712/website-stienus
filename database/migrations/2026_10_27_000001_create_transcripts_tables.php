<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transcripts', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number', 100)->nullable()->unique();
            $table->string('student_name');
            $table->string('student_number', 50);
            $table->string('birth_place', 120)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();      // Jurusan
            $table->string('study_program')->nullable();   // Program Studi
            $table->string('education_program')->nullable(); // Program Pendidikan (mis. Strata - 1)
            $table->string('concentration')->nullable();
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->date('graduation_date')->nullable();
            $table->string('issued_place', 120)->nullable();
            $table->date('issued_date')->nullable();
            $table->string('thesis_title', 500)->nullable();
            $table->string('predicate_override')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('transcript_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transcript_id')->constrained()->cascadeOnDelete();
            $table->string('category')->nullable();        // kelompok mata kuliah
            $table->integer('category_order')->default(0);
            $table->string('kode', 50)->nullable();
            $table->string('nama');
            $table->unsignedTinyInteger('sks')->default(0);
            $table->string('grade', 2)->nullable();         // A/B/C/D/E
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        foreach (['view_any', 'view', 'create', 'update', 'delete', 'print'] as $ability) {
            DB::table(config('permission.table_names.permissions', 'permissions'))->insertOrIgnore([
                'name' => $ability.'_transcript', 'guard_name' => 'web',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Schema::dropIfExists('transcript_courses');
        Schema::dropIfExists('transcripts');
    }
};
