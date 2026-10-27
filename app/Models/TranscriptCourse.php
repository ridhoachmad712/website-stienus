<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\GradeScale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranscriptCourse extends Model
{
    protected $fillable = [
        'transcript_id', 'category', 'category_order',
        'kode', 'nama', 'sks', 'grade', 'order',
    ];

    protected $casts = [
        'sks' => 'integer',
        'category_order' => 'integer',
        'order' => 'integer',
    ];

    public function transcript(): BelongsTo
    {
        return $this->belongsTo(Transcript::class);
    }

    /** KxN = sks × bobot nilai. */
    public function qualityPoints(): float
    {
        return $this->sks * GradeScale::points($this->grade);
    }
}
