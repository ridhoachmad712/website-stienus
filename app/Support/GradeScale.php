<?php

declare(strict_types=1);

namespace App\Support;

use App\Settings\TranscriptSettings;

/**
 * Bobot nilai huruf untuk perhitungan KxN dan IPK. Diambil dari
 * TranscriptSettings (dapat diatur), dengan fallback skala umum.
 */
class GradeScale
{
    public const DEFAULT = ['A' => 4, 'B' => 3, 'C' => 2, 'D' => 1, 'E' => 0];

    private static ?array $map = null;

    /** @return array<string,float|int> */
    public static function map(): array
    {
        return self::$map ??= rescue(
            fn () => app(TranscriptSettings::class)->grade_points ?: self::DEFAULT,
            self::DEFAULT,
            report: false
        );
    }

    public static function points(?string $grade): float
    {
        if ($grade === null || trim($grade) === '') {
            return 0.0;
        }

        return (float) (self::map()[strtoupper(trim($grade))] ?? 0);
    }

    public static function reset(): void
    {
        self::$map = null;
    }
}
