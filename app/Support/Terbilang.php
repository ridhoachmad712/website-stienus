<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Bilangan bulat non-negatif ke kata (bahasa Indonesia), untuk transkrip
 * ("seratus empat puluh enam", dst.).
 */
class Terbilang
{
    private const ANGKA = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

    public static function make(int $number): string
    {
        $number = abs($number);

        return trim(self::convert($number)) ?: 'nol';
    }

    private static function convert(int $n): string
    {
        if ($n < 12) {
            return self::ANGKA[$n];
        }
        if ($n < 20) {
            return self::convert($n - 10).' belas';
        }
        if ($n < 100) {
            return self::convert(intdiv($n, 10)).' puluh '.self::convert($n % 10);
        }
        if ($n < 200) {
            return 'seratus '.self::convert($n - 100);
        }
        if ($n < 1000) {
            return self::convert(intdiv($n, 100)).' ratus '.self::convert($n % 100);
        }
        if ($n < 2000) {
            return 'seribu '.self::convert($n - 1000);
        }
        if ($n < 1_000_000) {
            return self::convert(intdiv($n, 1000)).' ribu '.self::convert($n % 1000);
        }
        if ($n < 1_000_000_000) {
            return self::convert(intdiv($n, 1_000_000)).' juta '.self::convert($n % 1_000_000);
        }

        return self::convert(intdiv($n, 1_000_000_000)).' miliar '.self::convert($n % 1_000_000_000);
    }
}
