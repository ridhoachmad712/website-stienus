<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Download extends Model
{
    /** @use HasFactory<\Database\Factories\DownloadFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'file',
        'category',
        'downloads_count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'downloads_count' => 'integer',
        ];
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::get(fn (): string => Str::startsWith($this->file, ['http://', 'https://'])
            ? $this->file
            : Storage::disk('public')->url($this->file));
    }
}
