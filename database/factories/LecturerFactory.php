<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Lecturer;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lecturer>
 */
class LecturerFactory extends Factory
{
    protected $model = Lecturer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $frontTitle = fake()->randomElement(['', '', 'Dr. ', 'Prof. Dr. ']);
        $backTitle = fake()->randomElement([
            'S.Kom., M.Kom.', 'S.T., M.T.', 'S.E., M.M.',
            'S.H., M.H.', 'S.Sos., M.I.Kom.', 'S.Si., M.Si.',
        ]);

        return [
            'program_id' => Program::factory(),
            'nidn' => (string) fake()->unique()->numerify('00##########'),
            'name' => $frontTitle.fake()->name(),
            'title' => $backTitle,
            'photo' => null,
            'expertise' => fake()->randomElement([
                'Kecerdasan Buatan', 'Jaringan Komputer', 'Rekayasa Perangkat Lunak',
                'Basis Data', 'Keamanan Siber', 'Manajemen Keuangan',
                'Pemasaran Digital', 'Hukum Bisnis', 'Komunikasi Massa',
            ]),
            'google_scholar_link' => fake()->boolean(70) ? 'https://scholar.google.com/citations?user='.fake()->bothify('??????????') : null,
            'sinta_link' => fake()->boolean(70) ? 'https://sinta.kemdikbud.go.id/authors/profile/'.fake()->numberBetween(100000, 999999) : null,
        ];
    }
}
