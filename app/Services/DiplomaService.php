<?php

namespace App\Services;

use App\Models\Diploma;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DiplomaService
{
    public function createDraft(array $data, User $actor): Diploma
    {
        Gate::forUser($actor)->authorize('create', Diploma::class);

        return DB::transaction(function () use ($data, $actor) {
            $diploma = new Diploma($this->validate($data));
            $diploma->created_by = $actor->id;
            $diploma->save();
            $diploma->refresh();
            $this->record($diploma, $actor, 'created', null, 'Data disimpan sebagai draf.');

            return $diploma;
        });
    }

    public function updateDraft(Diploma $diploma, array $data, User $actor): Diploma
    {
        return DB::transaction(function () use ($diploma, $data, $actor) {
            $record = Diploma::lockForUpdate()->findOrFail($diploma->id);
            Gate::forUser($actor)->authorize('update', $record);
            if (! in_array($record->status, ['draft', 'revoked'], true)) {
                throw ValidationException::withMessages(['status' => 'Hanya draf atau ijazah yang dicabut yang dapat diperbarui. Muat ulang halaman sebelum melanjutkan.']);
            }
            $before = $record->snapshot();
            $record->fill($this->validate($data, $record))->save();
            $this->record($record, $actor, 'updated', $before,
                $record->status === 'revoked' ? 'Data ijazah yang dicabut diperbarui.' : 'Draf diperbarui.');

            return $record;
        });
    }

    public function issue(Diploma $diploma, User $actor, bool $confirmed): Diploma
    {
        return DB::transaction(function () use ($diploma, $actor, $confirmed) {
            $record = Diploma::lockForUpdate()->findOrFail($diploma->id);
            Gate::forUser($actor)->authorize('issue', $record);
            $this->requireStatus($record, 'draft');
            if (! $confirmed) {
                throw ValidationException::withMessages(['confirmed' => 'Periksa data dengan arsip akademik sebelum menerbitkan.']);
            }
            $this->validate($record->only(Diploma::FIELDS), $record);
            $base = rtrim((string) config('diplomas.verification_base_url'), '/');
            $url = parse_url($base);
            if (! filter_var($base, FILTER_VALIDATE_URL) || ! in_array($url['scheme'] ?? '', ['http', 'https'])
                || isset($url['query']) || isset($url['fragment']) || isset($url['user'])
                || (app()->isProduction() && (($url['scheme'] ?? '') !== 'https'
                    || in_array($url['host'] ?? '', ['localhost', '127.0.0.1', 'domain-anda.com'])))) {
                throw ValidationException::withMessages(['confirmed' => 'Atur DIPLOMA_VERIFICATION_URL ke alamat HTTPS resmi kampus sebelum menerbitkan QR.']);
            }
            $before = $record->snapshot();
            if ($record->replaces_id) {
                $previous = Diploma::lockForUpdate()->findOrFail($record->replaces_id);
                Gate::forUser($actor)->authorize('revoke', $previous);
                $this->requireStatus($previous, 'issued');
                if ($previous->student_number !== $record->student_number) {
                    throw ValidationException::withMessages(['replaces_id' => 'Ijazah pengganti harus untuk NIM yang sama.']);
                }
                $old = $previous->snapshot();
                $previous->status = 'superseded';
                $previous->save();
                $this->record($previous, $actor, 'superseded', $old, 'Diganti oleh catatan #'.$record->id.'. '.$record->correction_reason);
            }
            $record->public_token = bin2hex(random_bytes(32));
            $record->verification_url = $base.'/verifikasi-ijazah/'.$record->public_token;
            $record->status = 'issued';
            $record->issued_by = $actor->id;
            $record->issued_at = now();
            $record->save();
            $this->record($record, $actor, 'issued', $before, 'Data telah dicocokkan dengan arsip akademik dan disetujui untuk publikasi.');

            return $record;
        });
    }

    public function revoke(Diploma $diploma, User $actor, string $reason): Diploma
    {
        Validator::make(['reason' => $reason], ['reason' => 'required|string|min:10|max:2000'])->validate();

        return DB::transaction(function () use ($diploma, $actor, $reason) {
            $record = Diploma::lockForUpdate()->findOrFail($diploma->id);
            Gate::forUser($actor)->authorize('revoke', $record);
            $this->requireStatus($record, 'issued');
            $before = $record->snapshot();
            $record->status = 'revoked';
            $record->save();
            $this->record($record, $actor, 'revoked', $before, $reason);

            return $record;
        });
    }

    private function validate(array $data, ?Diploma $record = null): array
    {
        $data = Arr::only($data, Diploma::FIELDS);
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $value = trim($value);
                // Sel kosong (mis. dari CSV/Excel) diperlakukan sebagai null
                // agar aturan "nullable" lolos dan "required" tetap menolak.
                $data[$key] = $value === '' ? null : $value;
            }
            if ($value instanceof \DateTimeInterface) {
                $data[$key] = $value->format('Y-m-d');
            }
        }

        return Validator::make($data, [
            'student_name' => 'required|string|max:255',
            'student_number' => 'required|string|max:50',
            'birth_place' => 'nullable|string|max:120',
            'birth_date' => 'nullable|date_format:Y-m-d|before_or_equal:today',
            'diploma_number' => ['required', 'string', 'max:100', Rule::unique('diplomas')->ignore($record?->id)],
            'institution_name' => 'required|string|max:255',
            'program_name' => 'required|string|max:255',
            'degree' => 'required|string|max:100',
            'graduation_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'issue_date' => 'required|date_format:Y-m-d|after_or_equal:graduation_date|before_or_equal:today',
            'source_reference' => 'required|string|max:255',
            'internal_notes' => 'nullable|string|max:5000',
            'replaces_id' => ['nullable', 'integer', Rule::exists('diplomas', 'id')->where('status', 'issued'), Rule::notIn(array_filter([$record?->id]))],
            'correction_reason' => 'required_with:replaces_id|nullable|string|min:10|max:2000',
        ])->validate();
    }

    private function requireStatus(Diploma $record, string $status): void
    {
        if ($record->status !== $status) {
            throw ValidationException::withMessages(['status' => 'Status ijazah sudah berubah. Muat ulang halaman sebelum melanjutkan.']);
        }
    }

    private function record(Diploma $record, User $actor, string $action, ?array $before, ?string $reason): void
    {
        $record->events()->create([
            'actor_id' => $actor->id, 'actor_name' => $actor->name, 'action' => $action,
            'reason' => $reason, 'before' => $before, 'after' => $record->snapshot(), 'created_at' => now(),
        ]);
    }
}
