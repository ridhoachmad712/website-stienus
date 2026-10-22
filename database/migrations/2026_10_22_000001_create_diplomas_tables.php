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
        Schema::create('diplomas', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_number', 50);
            $table->string('diploma_number', 100)->unique();
            $table->string('institution_name');
            $table->string('program_name');
            $table->string('degree', 100);
            $table->date('graduation_date');
            $table->date('issue_date');
            $table->string('source_reference', 255);
            $table->text('internal_notes')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->string('public_token', 64)->nullable()->unique();
            $table->string('verification_url', 1000)->nullable();
            $table->foreignId('replaces_id')->nullable()->constrained('diplomas')->restrictOnDelete();
            $table->text('correction_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });

        Schema::create('diploma_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diploma_id')->constrained()->restrictOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor_name');
            $table->string('action', 40);
            $table->text('reason')->nullable();
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->timestamp('created_at');
        });

        foreach (['view_any', 'view', 'create', 'update', 'issue', 'revoke', 'download'] as $ability) {
            DB::table(config('permission.table_names.permissions', 'permissions'))->insertOrIgnore([
                'name' => $ability.'_diploma', 'guard_name' => 'web',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Schema::dropIfExists('diploma_events');
        Schema::dropIfExists('diplomas');
    }
};
