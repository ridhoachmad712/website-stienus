<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'parent_id',
        'label',
        'url',
        'order',
        'is_active',
        'is_button',
        'open_in_new_tab',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_active' => 'boolean',
            'is_button' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<MenuItem, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * @return HasMany<MenuItem, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Top-level, active items ordered, with their active children eager-loaded.
     *
     * @param  Builder<MenuItem>  $query
     * @return Builder<MenuItem>
     */
    public function scopeTopLevel(Builder $query): Builder
    {
        return $query
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->with(['children' => fn ($q) => $q->where('is_active', true)]);
    }
}
