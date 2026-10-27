<?php

declare(strict_types=1);

use App\Settings\GeneralSettings;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $general = rescue(fn () => app(GeneralSettings::class), null, report: false);
        $name = $general?->site_full_name ?: 'SEKOLAH TINGGI ILMU EKONOMI NUSANTARA MAKASSAR';
        $address = $general
            ? trim(collect([$general->address ?? '', $general->phone ? 'Telp. '.$general->phone : ''])->filter()->implode(' · '))
            : '';

        $this->migrator->add('transcript.institution_name', $name);
        $this->migrator->add('transcript.institution_address', $address);
        $this->migrator->add('transcript.accreditation_text', '');

        $this->migrator->add('transcript.faculty', 'Ekonomi');
        $this->migrator->add('transcript.department', 'Manajemen');
        $this->migrator->add('transcript.study_program', 'Manajemen');
        $this->migrator->add('transcript.education_program', 'Strata - 1 (S1)');
        $this->migrator->add('transcript.concentration', '');

        $this->migrator->add('transcript.issued_place', 'Makassar');
        $this->migrator->add('transcript.sign_left_label', 'Ketua,');
        $this->migrator->add('transcript.sign_left_name', 'Dr. Agus Arman, S.E., M.Si.');
        $this->migrator->add('transcript.sign_left_id', 'NIDN. 0016087001');
        $this->migrator->add('transcript.sign_right_label', 'Wakil Ketua Bidang Akademik,');
        $this->migrator->add('transcript.sign_right_name', 'Fitriani, S.E., M.M.');
        $this->migrator->add('transcript.sign_right_id', 'NIDN. 0925018503');

        $this->migrator->add('transcript.grade_points', ['A' => 4, 'B' => 3, 'C' => 2, 'D' => 1, 'E' => 0]);

        // Ambang predikat umum; dapat diedit sesuai peraturan akademik resmi,
        // dan dapat di-override per transkrip. Tidak mengarang skema resmi.
        $this->migrator->add('transcript.predicates', [
            ['min' => 3.51, 'label' => 'Dengan Pujian'],
            ['min' => 3.01, 'label' => 'Sangat Memuaskan'],
            ['min' => 2.76, 'label' => 'Memuaskan'],
        ]);

        $this->migrator->add('transcript.category_defaults', [
            'Matakuliah Pengembangan Kepribadian',
            'Matakuliah Keahlian dan Keterampilan',
            'Matakuliah Keahlian Berkarya',
            'Matakuliah Perilaku Berkarya',
            'Matakuliah Berkehidupan Bermasyarakat',
        ]);

        $this->migrator->add('transcript.show_photo', true);
    }
};
