<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AdmissionSettings extends Settings
{
    public string $headline;

    public string $subheadline;

    public ?string $intro;

    public array $steps;

    public ?string $schedule;

    public ?string $fee_info;

    public ?string $brochure;

    public bool $form_enabled;

    public static function group(): string
    {
        return 'admission';
    }
}
