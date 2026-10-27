<?php

declare(strict_types=1);

namespace App\Models;

use App\Settings\TranscriptSettings;
use App\Support\GradeScale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transcript extends Model
{
    public const STATUSES = ['draft' => 'Draf', 'final' => 'Final'];

    protected $fillable = [
        'serial_number', 'student_name', 'student_number', 'birth_place', 'birth_date',
        'faculty', 'department', 'study_program', 'education_program', 'concentration',
        'program_id', 'graduation_date', 'issued_place', 'issued_date', 'thesis_title',
        'predicate_override', 'photo_path', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'graduation_date' => 'date',
            'issued_date' => 'date',
        ];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(TranscriptCourse::class)
            ->orderBy('category_order')->orderBy('order')->orderBy('id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function totalCredits(): int
    {
        return (int) $this->courses->sum('sks');
    }

    /** Nilai mutu = Σ (sks × bobot nilai). */
    public function totalQuality(): float
    {
        return round($this->courses->sum(fn (TranscriptCourse $c) => $c->qualityPoints()), 2);
    }

    /** IPK = nilai mutu ÷ total kredit, dua desimal. */
    public function gpa(): float
    {
        $credits = $this->totalCredits();

        return $credits > 0 ? round($this->totalQuality() / $credits, 2) : 0.0;
    }

    /** Predikat: override manual bila ada, selain itu dari ambang di settings. */
    public function predicate(): string
    {
        if (filled($this->predicate_override)) {
            return (string) $this->predicate_override;
        }

        $gpa = $this->gpa();
        $thresholds = rescue(fn () => app(TranscriptSettings::class)->predicates, [], report: false);

        $best = '';
        $bestMin = -1.0;
        foreach ($thresholds as $row) {
            $min = (float) ($row['min'] ?? 0);
            if ($gpa >= $min && $min >= $bestMin) {
                $bestMin = $min;
                $best = (string) ($row['label'] ?? '');
            }
        }

        return $best;
    }
}
