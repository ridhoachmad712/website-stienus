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
        'student_name', 'student_number', 'diploma_number', 'institution_name',
        'program_name', 'degree', 'graduation_date', 'issue_date',
        'source_reference', 'internal_notes', 'replaces_id', 'correction_reason',
    ];

    protected $fillable = self::FIELDS;

    protected $hidden = ['public_token', 'verification_url'];

    protected function casts(): array
    {
        return ['graduation_date' => 'date', 'issue_date' => 'date', 'issued_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::updating(function (self $diploma): void {
            if ($diploma->getOriginal('status') !== 'draft' && $diploma->isDirty([
                ...self::FIELDS, 'public_token', 'verification_url', 'issued_by', 'issued_at',
            ])) {
                throw new LogicException('Data ijazah yang telah diterbitkan tidak boleh ditimpa. Buat catatan penggantian.');
            }
        });
        static::deleting(fn () => throw new LogicException('Riwayat ijazah tidak boleh dihapus.'));
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
