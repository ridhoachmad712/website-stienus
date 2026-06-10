<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Akuntansi', 'Manajemen', 'Ekonomi Pembangunan',
            'Perpajakan', 'Bisnis Digital', 'Keuangan dan Perbankan',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'degree' => fake()->randomElement(['D3', 'D4', 'S1', 'S2']),
            'accreditation' => fake()->randomElement(['Unggul', 'Baik Sekali', 'A', 'B']),
            'vision_mission' => "Visi:\n".fake()->sentence(12)."\n\nMisi:\n- ".implode("\n- ", fake()->sentences(3)),
            'profile_image' => null,
        ];
    }
}
