<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Staff>
 */
class StaffFactory extends Factory
{
    protected $model = Staff::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unit = fake()->randomElement([
            'Bagian Akademik', 'Bagian Keuangan', 'Perpustakaan',
            'Teknologi Informasi', 'Tata Usaha', 'Kemahasiswaan',
        ]);

        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement(['Kepala', 'Staf', 'Koordinator']).' '.$unit,
            'unit' => $unit,
            'nip' => (string) fake()->numerify('19##############'),
            'email' => fake()->unique()->safeEmail(),
            'photo' => null,
            'order' => 0,
        ];
    }
}
