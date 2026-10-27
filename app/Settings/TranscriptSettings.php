<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TranscriptSettings extends Settings
{
    // Kop
    public string $institution_name;
    public string $institution_address;
    public string $accreditation_text;

    // Default identitas (prefill saat membuat transkrip)
    public string $faculty;
    public string $department;
    public string $study_program;
    public string $education_program;
    public string $concentration;

    // Penandatangan
    public string $issued_place;
    public string $sign_left_label;
    public string $sign_left_name;
    public string $sign_left_id;
    public string $sign_right_label;
    public string $sign_right_name;
    public string $sign_right_id;

    // Perhitungan
    public array $grade_points;   // ['A'=>4, ...]
    public array $predicates;     // [['min'=>3.51,'label'=>'...'], ...]
    public array $category_defaults; // ['Matakuliah ...', ...]

    public bool $show_photo;

    public static function group(): string
    {
        return 'transcript';
    }
}
