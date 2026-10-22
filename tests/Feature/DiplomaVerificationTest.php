<?php

namespace Tests\Feature;

use App\Filament\Resources\DiplomaResource\Pages\CreateDiploma;
use App\Filament\Resources\DiplomaResource\Pages\EditDiploma;
use App\Filament\Resources\DiplomaResource\Pages\ListDiplomas;
use App\Models\Diploma;
use App\Models\User;
use App\Services\DiplomaCsvImporter;
use App\Services\DiplomaService;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DiplomaVerificationTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;

    private DiplomaService $service;

    protected function setUp(): void
    {
        parent::setUp();
        config(['diplomas.verification_base_url' => 'https://kampus.example']);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $role = Role::create(['name' => 'petugas_ijazah', 'guard_name' => 'web']);
        $role->givePermissionTo(['view_any_diploma', 'view_diploma', 'create_diploma', 'update_diploma', 'issue_diploma', 'revoke_diploma', 'download_diploma']);
        $this->officer = User::factory()->create();
        $this->officer->assignRole($role);
        $this->service = app(DiplomaService::class);
    }

    private function data(array $overrides = []): array
    {
        return array_merge([
            'student_name' => 'Nama Alumni Uji', 'student_number' => '0012345678',
            'diploma_number' => 'NIN-2025-0001', 'institution_name' => 'Kampus Uji',
            'program_name' => 'Akuntansi', 'degree' => 'S1 — S.Ak.',
            'graduation_date' => '2025-08-01', 'issue_date' => '2025-08-15',
            'source_reference' => 'ARSIP-RAHASIA-123', 'internal_notes' => 'CATATAN-INTERNAL-456',
        ], $overrides);
    }

    private function issued(array $overrides = []): Diploma
    {
        return $this->service->issue($this->service->createDraft($this->data($overrides), $this->officer), $this->officer, true);
    }

    public function test_public_verification_shows_only_approved_minimum_data_and_no_cache(): void
    {
        $diploma = $this->issued(['student_name' => '<script>alert(1)</script>']);
        $this->get('/verifikasi-ijazah/'.$diploma->public_token)->assertOk()
            ->assertSee('Terdaftar dan berlaku')->assertSee($diploma->student_name)
            ->assertDontSee('<script>alert(1)</script>', false)->assertDontSee('0012345678')
            ->assertDontSee('ARSIP-RAHASIA-123')->assertDontSee('CATATAN-INTERNAL-456')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive')
            ->assertHeader('Referrer-Policy', 'no-referrer');
        $this->assertStringContainsString('no-store', $this->get('/verifikasi-ijazah/'.$diploma->public_token)->headers->get('Cache-Control'));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $diploma->public_token);
        $this->assertSame('https://kampus.example/verifikasi-ijazah/'.$diploma->public_token, $diploma->verification_url);
        $this->assertSame(2, $diploma->events()->count());
    }

    public function test_draft_and_unknown_codes_do_not_expose_identity(): void
    {
        $draft = $this->service->createDraft($this->data(), $this->officer);
        $this->assertNull($draft->public_token);
        $this->get('/verifikasi-ijazah/'.str_repeat('a', 64))->assertNotFound()->assertSee('Catatan tidak ditemukan')->assertDontSee($draft->student_name);
        $this->get('/verifikasi-ijazah/1')->assertNotFound()->assertSee('tidak otomatis');
        $this->get('/verifikasi-ijazah')->assertOk()->assertSee('Kode verifikasi');
    }

    public function test_manual_code_lookup_redirects_only_to_local_verification(): void
    {
        $diploma = $this->issued();
        $this->get('/verifikasi-ijazah?kode='.$diploma->public_token)->assertRedirect(route('diplomas.verify', $diploma->public_token));
        $this->from('/verifikasi-ijazah')->get('/verifikasi-ijazah?kode=https://evil.example')->assertSessionHasErrors('kode');
    }

    public function test_issue_requires_confirmation_and_is_not_repeatable(): void
    {
        $draft = $this->service->createDraft($this->data(), $this->officer);
        try {
            $this->service->issue($draft, $this->officer, false);
            $this->fail('Confirmation must be required.');
        } catch (ValidationException) {
            $this->assertSame('draft', $draft->fresh()->status);
        }
        $issued = $this->service->issue($draft, $this->officer, true);
        try {
            $this->service->issue($draft, $this->officer, true);
            $this->fail('Second issue must fail.');
        } catch (AuthorizationException|ValidationException) {
            $this->assertSame($issued->public_token, $issued->fresh()->public_token);
        }
    }

    public function test_revocation_preserves_url_and_updates_public_status(): void
    {
        $diploma = $this->issued();
        $revoked = $this->service->revoke($diploma, $this->officer, 'Dasar pencabutan internal yang rahasia.');
        $this->assertSame($diploma->public_token, $revoked->public_token);
        $this->get('/verifikasi-ijazah/'.$diploma->public_token)->assertOk()->assertSee('Status penerbitan dicabut')->assertDontSee('Terdaftar dan berlaku')->assertDontSee('Dasar pencabutan internal');
        $this->actingAs($this->officer)->get(route('diplomas.qr', $diploma))->assertForbidden();
        $this->assertSame(3, $revoked->events()->count());
    }

    public function test_replacement_is_atomic_and_preserves_old_history(): void
    {
        $old = $this->issued();
        $new = $this->issued(['diploma_number' => 'NIN-2025-0002', 'replaces_id' => $old->id, 'correction_reason' => 'Perbaikan ejaan nama sesuai arsip.']);
        $this->assertNotSame($old->public_token, $new->public_token);
        $this->assertSame('superseded', $old->fresh()->status);
        $this->get('/verifikasi-ijazah/'.$old->public_token)->assertOk()->assertSee('Ijazah telah diganti / diperbaiki');
        $this->get('/verifikasi-ijazah/'.$new->public_token)->assertOk()->assertSee('Terdaftar dan berlaku');
        $this->assertSame('superseded', $old->events()->first()->action);
    }

    public function test_replacement_for_another_student_is_rejected_without_changing_original(): void
    {
        $old = $this->issued();
        $draft = $this->service->createDraft($this->data(['diploma_number' => 'OTHER', 'student_number' => 'OTHER-NIM', 'replaces_id' => $old->id, 'correction_reason' => 'Perbaikan data alumni berbeda.']), $this->officer);
        try {
            $this->service->issue($draft, $this->officer, true);
            $this->fail('Different student must fail.');
        } catch (ValidationException) {
            $this->assertSame('issued', $old->fresh()->status);
            $this->assertSame('draft', $draft->fresh()->status);
        }
    }

    public function test_editor_cannot_issue_revoke_or_download_without_specific_permission(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole(Role::create(['name' => 'operator', 'guard_name' => 'web']));
        $editor->givePermissionTo(['view_any_diploma', 'view_diploma', 'create_diploma', 'update_diploma']);
        $draft = $this->service->createDraft($this->data(), $editor);
        try {
            $this->service->issue($draft, $editor, true);
            $this->fail('Editor must not issue.');
        } catch (AuthorizationException) {
            $this->assertSame('draft', $draft->fresh()->status);
        }
        $issued = $this->service->issue($draft, $this->officer, true);
        $this->actingAs($editor)->get(route('diplomas.qr', $issued))->assertForbidden();
        $this->get(route('diplomas.label', $issued))->assertForbidden();
        try {
            $this->service->revoke($issued, $editor, 'Unauthorized change');
            $this->fail('Editor must not revoke.');
        } catch (AuthorizationException) {
            $this->assertSame('issued', $issued->fresh()->status);
        }
    }

    public function test_qr_and_print_label_require_authentication_and_render_locally(): void
    {
        $diploma = $this->issued();
        $this->get(route('diplomas.qr', $diploma))->assertRedirect(route('filament.admin.auth.login'));
        $this->actingAs($this->officer)->get(route('diplomas.qr', $diploma))->assertOk()->assertHeader('Content-Type', 'image/svg+xml')->assertSee('<svg', false);
        $this->get(route('diplomas.label', $diploma))->assertOk()->assertSee('data:image/svg+xml;base64', false)->assertSee($diploma->verification_url);
    }

    public function test_issued_identity_and_audit_cannot_be_overwritten(): void
    {
        $diploma = $this->issued();
        try {
            $diploma->update(['student_name' => 'Nama berbeda']);
            $this->fail('Issued identity must be immutable.');
        } catch (\LogicException) {
            $this->assertSame('Nama Alumni Uji', $diploma->fresh()->student_name);
        }
        try {
            $diploma->events()->first()->update(['reason' => 'Changed']);
            $this->fail('Audit must be immutable.');
        } catch (\LogicException) {
            $this->assertSame(2, $diploma->events()->count());
        }
        try {
            $diploma->delete();
            $this->fail('Diploma must not be deleted.');
        } catch (\LogicException) {
            $this->assertDatabaseCount('diplomas', 1);
        }
    }

    public function test_super_admin_cannot_bypass_draft_lock_with_stale_edit(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::create(['name' => 'super_admin', 'guard_name' => 'web']));
        $draft = $this->service->createDraft($this->data(), $admin);
        $this->service->issue($draft, $admin, true);
        $this->expectException(ValidationException::class);
        $this->service->updateDraft($draft, $this->data(['student_name' => 'Tampered']), $admin);
    }

    public function test_admin_forms_and_actions_work_with_real_permissions(): void
    {
        $this->actingAs($this->officer);
        $this->get('/admin/ijazah')->assertOk();
        $this->get('/admin/ijazah/create')->assertOk();
        Livewire::test(CreateDiploma::class)->fillForm($this->data())->call('create')->assertHasNoFormErrors();
        $draft = Diploma::firstOrFail();
        Livewire::test(EditDiploma::class, ['record' => $draft->id])->assertFormSet(['source_reference' => 'ARSIP-RAHASIA-123'])
            ->fillForm(['student_name' => 'Nama Diperiksa'])->call('save')->assertHasNoFormErrors();
        Livewire::test(ListDiplomas::class)->callTableAction('issue', $draft, data: ['confirmed' => true])->assertHasNoTableActionErrors();
        $this->assertSame('issued', $draft->fresh()->status);
        $this->get('/admin/ijazah/'.$draft->id)->assertOk();
        $this->get('/admin/ijazah/'.$draft->id.'/edit')->assertForbidden();
    }

    public function test_import_is_atomic_and_preserves_leading_zeroes(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'diplomas-test-');
        try {
            $handle = fopen($file, 'wb');
            fputcsv($handle, DiplomaCsvImporter::COLUMNS);
            $row = array_values(array_intersect_key($this->data(), array_flip(DiplomaCsvImporter::COLUMNS)));
            fputcsv($handle, $row);
            fputcsv($handle, $row);
            fclose($handle);
            try {
                app(DiplomaCsvImporter::class)->import($file, $this->officer);
                $this->fail('Duplicate import must fail.');
            } catch (ValidationException) {
                $this->assertDatabaseCount('diplomas', 0);
                $this->assertDatabaseCount('diploma_events', 0);
            }
            $handle = fopen($file, 'wb');
            fputcsv($handle, DiplomaCsvImporter::COLUMNS);
            fputcsv($handle, $row);
            fclose($handle);
            $this->assertSame(1, app(DiplomaCsvImporter::class)->import($file, $this->officer));
            $this->assertSame('0012345678', Diploma::first()->student_number);
            $this->assertNull(Diploma::first()->public_token);
        } finally {
            unlink($file);
        }
    }

    public function test_seeder_does_not_reset_existing_administrator_password(): void
    {
        $admin = User::factory()->create(['email' => 'admin@stienusantara.ac.id', 'password' => 'keep-this-unique-password']);
        $this->seed();
        $this->assertTrue(Hash::check('keep-this-unique-password', $admin->fresh()->password));
    }

    public function test_production_issue_rejects_http_address_without_changing_draft(): void
    {
        $draft = $this->service->createDraft($this->data(), $this->officer);
        $this->app->instance('env', 'production');
        config(['diplomas.verification_base_url' => 'http://127.0.0.1:8000']);
        try {
            $this->service->issue($draft, $this->officer, true);
            $this->fail('Production must require official HTTPS address.');
        } catch (ValidationException) {
            $this->assertSame('draft', $draft->fresh()->status);
            $this->assertNull($draft->fresh()->public_token);
        } finally {
            $this->app->instance('env', 'testing');
        }
    }

    public function test_invalid_chronology_and_duplicate_numbers_are_rejected(): void
    {
        $this->service->createDraft($this->data(), $this->officer);
        try {
            $this->service->createDraft($this->data(), $this->officer);
            $this->fail('Duplicate diploma number must fail.');
        } catch (ValidationException $error) {
            $this->assertArrayHasKey('diploma_number', $error->errors());
        }
        try {
            $this->service->createDraft($this->data(['diploma_number' => 'NEW', 'issue_date' => '2025-07-01']), $this->officer);
            $this->fail('Issue date before graduation must fail.');
        } catch (ValidationException $error) {
            $this->assertArrayHasKey('issue_date', $error->errors());
        }
        $this->assertDatabaseCount('diplomas', 1);
    }
}
