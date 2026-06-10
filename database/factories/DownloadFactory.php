<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Download;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Download>
 */
class DownloadFactory extends Factory
{
    protected $model = Download::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = fake()->randomElement(['Formulir', 'Panduan', 'SK', 'Brosur', 'Kalender Akademik']);

        return [
            'title' => $category.' '.fake()->words(3, true),
            'description' => fake()->sentence(),
            // Sample public PDF for demo; file_url accessor passes absolute URLs through.
            'file' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            'category' => $category,
            'downloads_count' => fake()->numberBetween(0, 500),
        ];
    }
}
