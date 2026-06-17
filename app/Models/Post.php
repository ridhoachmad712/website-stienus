<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, RecordsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'blocks',
        'featured_image',
        'status',
        'views_count',
        'created_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'views_count' => 'integer',
            'blocks' => 'array',
        ];
    }

    /**
     * Ringkasan untuk daftar & meta SEO (pakai excerpt, jatuh ke konten lama).
     */
    public function getSummaryAttribute(): string
    {
        return $this->excerpt
            ?: \Illuminate\Support\Str::limit(trim(strip_tags((string) $this->content)), 160);
    }

    /**
     * Get the category that owns the post.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
