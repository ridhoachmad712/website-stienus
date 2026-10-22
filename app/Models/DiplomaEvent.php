<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LogicException;

class DiplomaEvent extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['before' => 'array', 'after' => 'array', 'created_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('Riwayat ijazah tidak boleh diubah.'));
        static::deleting(fn () => throw new LogicException('Riwayat ijazah tidak boleh dihapus.'));
    }
}
