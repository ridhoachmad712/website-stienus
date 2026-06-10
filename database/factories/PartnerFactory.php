<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name' => $name,
            'logo' => 'https://placehold.co/200x80?text='.urlencode(\Illuminate\Support\Str::words($name, 1, '')),
            'url' => fake()->url(),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
