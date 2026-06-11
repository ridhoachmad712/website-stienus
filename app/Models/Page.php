<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /** @use HasFactory<\Database\Factories\PageFactory> */
    use HasFactory, RecordsActivity;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'subtitle',
        'banner_image',
        'slug',
        'content',
        'blocks',
        'meta_description',
        'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'blocks' => 'array',
        ];
    }

    /**
     * Public URL untuk halaman ini.
     */
    public function getUrlAttribute(): string
    {
        return url('/halaman/'.$this->slug);
    }
}
