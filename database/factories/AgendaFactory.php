<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Agenda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agenda>
 */
class AgendaFactory extends Factory
{
    protected $model = Agenda::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement([
                'Seminar Nasional', 'Workshop', 'Kuliah Umum', 'Wisuda',
                'Pelatihan', 'Pameran', 'Lomba', 'Pengabdian Masyarakat',
            ]).' '.fake()->words(2, true),
            'description' => fake()->paragraph(4),
            'event_date' => fake()->dateTimeBetween('-1 month', '+3 months')->format('Y-m-d'),
            'location' => fake()->randomElement([
                'Auditorium Utama', 'Gedung Rektorat Lt. 3', 'Aula Fakultas Teknik',
                'Ruang Seminar A', 'Lapangan Kampus', 'Online via Zoom',
            ]),
            'image' => null,
        ];
    }
}
