<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Slider>
 */
class SliderFactory extends Factory
{
    protected $model = Slider::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'subtitle' => fake()->sentence(10),
            'image' => 'https://picsum.photos/seed/slide'.fake()->unique()->numberBetween(1, 99999).'/1600/700',
            'button_text' => 'Selengkapnya',
            'button_url' => '/pmb',
            'order' => fake()->numberBetween(0, 5),
            'is_active' => true,
        ];
    }
}
