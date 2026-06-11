<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomForm extends Model
{
    /** @use HasFactory<\Database\Factories\CustomFormFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'title', 'slug', 'description', 'fields', 'success_message', 'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<FormSubmission, $this>
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function getUrlAttribute(): string
    {
        return url('/formulir/'.$this->slug);
    }
}
