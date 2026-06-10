<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gallery>
 */
class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = fake()->randomElement(['Kegiatan', 'Wisuda', 'Seminar', 'Fasilitas', 'Prestasi']);

        return [
            'title' => $category.' '.fake()->words(2, true),
            // Seeded with a placeholder image service; the model's image_url
            // accessor returns absolute URLs as-is.
            'image' => 'https://picsum.photos/seed/'.fake()->unique()->numberBetween(1, 100000).'/600/600',
            'category' => $category,
        ];
    }
}
