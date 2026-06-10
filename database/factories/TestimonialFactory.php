<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prodi = fake()->randomElement(['Akuntansi', 'Manajemen']);

        return [
            'name' => fake()->name(),
            'role' => 'Alumni '.$prodi.' '.fake()->numberBetween(2015, 2023),
            'photo' => 'https://i.pravatar.cc/150?img='.fake()->unique()->numberBetween(1, 70),
            'content' => fake()->paragraph(3),
            'order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
