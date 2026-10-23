<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LogicException;

class Diploma extends Model
{
    public const STATUSES = [
        'draft' => 'Draf', 'issued' => 'Berlaku',
        'revoked' => 'Dicabut', 'superseded' => 'Diganti / diperbaiki',
    ];

    public const FIELDS = [
        'student_name', 'student_number', 'birth_place', 'birth_date',
        'diploma_number', 'institution_name',
        'program_name', 'degree', 'graduation_date', 'issue_date',
        'source_reference', 'internal_notes', 'replaces_id', 'correction_reason',
    ];

    protected $fillable = self::FIELDS;

    protected $hidden = ['public_token', 'verification_url'];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date', 'graduation_date' => 'date',
            'issue_date' => 'date', 'issued_at' => 'datetime',
        ];
    }

    /**
     * Prodi yang dipakai untuk memilih template ijazah: 'manajemen' bila
     * nama program mengandung "manaj", selain itu 'akuntansi'. Sama dengan
     * logika activeProgram() pada editor statis.
     */
    public function prodiKey(): string
    {
        return str_contains(mb_strtolower((string) $this->program_name), 'manaj')
            ? 'manajemen' : 'akuntansi';
    }

    protected static function booted(): void
    {
        static::updating(function (self $diploma): void {
            // Issued and superseded records back printed QR codes, so their data
            // stays locked. Drafts and revoked records may still be corrected.
            if (in_array($diploma->getOriginal('status'), ['issued', 'superseded'], true) && $diploma->isDirty([
                ...self::FIELDS, 'public_token', 'verification_url', 'issued_by', 'issued_at',
            ])) {
                throw new LogicException('Data ijazah yang telah diterbitkan tidak boleh ditimpa. Buat catatan penggantian.');
            }
        });
        static::deleting(function (self $diploma): void {
            // Live and chained records must never disappear from under a printed QR.
            if (in_array($diploma->status, ['issued', 'superseded'], true)) {
                throw new LogicException('Ijazah yang berlaku atau menjadi rujukan penggantian tidak boleh dihapus; cabut terlebih dahulu.');
            }
            // Detach any draft that still points here, then drop this record's own audit trail.
            static::where('replaces_id', $diploma->id)->update(['replaces_id' => null]);
            $diploma->events()->delete();
        });
    }

    public function events(): HasMany
    {
        return $this->hasMany(DiplomaEvent::class)->latest('id');
    }

    public function snapshot(): array
    {
        return $this->only([...self::FIELDS, 'status', 'issued_by', 'issued_at']);
    }
}
