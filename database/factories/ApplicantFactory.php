<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Applicant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Applicant>
 */
class ApplicantFactory extends Factory
{
    protected $model = Applicant::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '08'.fake()->numerify('##########'),
            'program' => fake()->randomElement(['Akuntansi', 'Manajemen']),
            'origin_school' => 'SMA/SMK '.fake()->city(),
            'address' => fake()->address(),
            'message' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['pending', 'pending', 'contacted', 'accepted']),
        ];
    }
}
