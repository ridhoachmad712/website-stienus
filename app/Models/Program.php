<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    /** @use HasFactory<\Database\Factories\ProgramFactory> */
    use HasFactory, RecordsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'degree',
        'accreditation',
        'vision_mission',
        'profile_image',
    ];

    /**
     * Get the lecturers that belong to this study program.
     *
     * @return HasMany<Lecturer, $this>
     */
    public function lecturers(): HasMany
    {
        return $this->hasMany(Lecturer::class)->orderBy('order')->orderBy('name');
    }
}
