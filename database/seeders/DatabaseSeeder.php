<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Applicant;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Download;
use App\Models\Gallery;
use App\Models\Lecturer;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Program;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with realistic data for
     * STIE Nusantara Makassar (no faculties, two study programs).
     */
    public function run(): void
    {
        // Admin user untuk login ke dashboard Filament.
        $admin = User::updateOrCreate(
            ['email' => 'admin@stienusantara.ac.id'],
            ['name' => 'Administrator', 'password' => bcrypt('password')],
        );

        // Pastikan role super_admin ada & melekat ke admin (akses penuh via Shield).
        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => config('filament-shield.super_admin.name', 'super_admin'),
            'guard_name' => 'web',
        ]);
        $admin->assignRole($superAdminRole);

        $this->seedPrograms();
        $this->seedNews();
        $this->seedAgendas();

        Gallery::factory()->count(16)->create();
        Applicant::factory()->count(10)->create();
        ContactMessage::factory()->count(6)->create();

        Slider::factory()->count(3)->create();
        Testimonial::factory()->count(6)->create();
        Partner::factory()->count(8)->create();
        Download::factory()->count(6)->create();
    }

    /**
     * Program studi (S1 Akuntansi & S1 Manajemen) beserta dosennya.
     */
    private function seedPrograms(): void
    {
        /** @var list<array{name: string, accreditation: string, vision: string}> $programs */
        $programs = [
            [
                'name' => 'Akuntansi',
                'accreditation' => 'Baik Sekali',
                'vision' => 'Menjadi program studi Akuntansi yang unggul dan profesional dalam menghasilkan akuntan berintegritas di kawasan timur Indonesia pada tahun 2030.',
            ],
            [
                'name' => 'Manajemen',
                'accreditation' => 'Baik Sekali',
                'vision' => 'Menjadi program studi Manajemen yang unggul dan adaptif dalam mencetak wirausahawan dan manajer profesional yang berdaya saing global pada tahun 2030.',
            ],
        ];

        foreach ($programs as $data) {
            $program = Program::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'degree' => 'S1',
                'accreditation' => $data['accreditation'],
                'vision_mission' => "Visi:\n{$data['vision']}\n\nMisi:\n- Menyelenggarakan pendidikan berkualitas berbasis kompetensi.\n- Mengembangkan penelitian di bidang ekonomi yang berdampak.\n- Melaksanakan pengabdian kepada masyarakat secara berkelanjutan.",
            ]);

            Lecturer::factory()
                ->count(12)
                ->for($program)
                ->create();
        }
    }

    /**
     * Kategori dan berita.
     */
    private function seedNews(): void
    {
        $categories = collect([
            'Berita Kampus', 'Pengumuman', 'Prestasi', 'Akademik', 'Kegiatan Mahasiswa',
        ])->map(fn (string $name): Category => Category::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]));

        Post::factory()
            ->count(30)
            ->published()
            ->recycle($categories)
            ->create();

        Post::factory()
            ->count(5)
            ->draft()
            ->recycle($categories)
            ->create();
    }

    /**
     * Agenda kegiatan.
     */
    private function seedAgendas(): void
    {
        Agenda::factory()->count(10)->create();
    }
}
