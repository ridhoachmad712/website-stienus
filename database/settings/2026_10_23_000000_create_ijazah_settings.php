<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Template umum — nilai awal = teks pada sampel ijazah 2026.
        $this->migrator->add('ijazah.akreditasi_institusi', 'Akreditasi Institusi BAN-PT No.123/SK/BAN-PT/Ak/PT/II/2023');
        $this->migrator->add('ijazah.label_nomor', 'Nomor Ijazah Nasional :');
        $this->migrator->add('ijazah.awal_identitas', 'Lahir di');
        $this->migrator->add('ijazah.label_nim', '| NIM :');
        $this->migrator->add('ijazah.tempat_terbit', 'Makassar');
        $this->migrator->add('ijazah.jabatan_kiri', 'Ketua STIE Nusantara Makassar,');
        $this->migrator->add('ijazah.jabatan_kanan', 'Wakil Ketua Bidang Akademik,');
        $this->migrator->add('ijazah.nama_kiri', 'Dr. Agus Arman, S.E., M.Si.');
        $this->migrator->add('ijazah.nama_kanan', 'Fitriani, S.E., M.M.');
        $this->migrator->add('ijazah.nidn_kiri', 'NIDN. 0016087001');
        $this->migrator->add('ijazah.nidn_kanan', 'NIDN. 0925018503');

        $this->migrator->add('ijazah.akuntansi', [
            'pernyataan' => 'Dengan ini menyatakan bahwa :',
            'akhiran_nama' => 'S.Ak.',
            'studi' => 'Telah menyelesaikan studi dengan baik dan memenuhi segala syarat Pendidikan Sarjana (S-1) pada :',
            'program' => 'Program Studi S-1 Akuntansi',
            'akreditasi_program' => 'Akreditasi Program Studi LAMEMBA No. 2464/DE/A.5/AR.10/VII/2025',
            'pemberian' => 'oleh sebab itu kepadanya diberikan Ijazah dan Gelar :',
            'gelar' => 'SARJANA AKUNTANSI (S.Ak.)',
            'hak' => 'beserta segala hak dan kewajiban yang melekat pada gelar tersebut.',
        ]);

        // Akreditasi Manajemen sengaja dikosongkan (belum tersedia pada sampel).
        $this->migrator->add('ijazah.manajemen', [
            'pernyataan' => 'Dengan ini menyatakan bahwa :',
            'akhiran_nama' => 'S.M.',
            'studi' => 'Telah menyelesaikan studi dengan baik dan memenuhi segala syarat Pendidikan Sarjana (S-1) pada :',
            'program' => 'Program Studi S-1 Manajemen',
            'akreditasi_program' => '',
            'pemberian' => 'oleh sebab itu kepadanya diberikan Ijazah dan Gelar :',
            'gelar' => 'SARJANA MANAJEMEN (S.M.)',
            'hak' => 'beserta segala hak dan kewajiban yang melekat pada gelar tersebut.',
        ]);

        $this->migrator->add('ijazah.layout', $this->defaultLayout());
        $this->migrator->add('ijazah.show_qr', true);
    }

    /**
     * Posisi awal tiap blok dalam milimeter, dikonversi dari tata letak
     * aplikasi editor statis (kanvas 1122x794 px pada A4 lanskap) memakai
     * rumus yang sama persis, ditambah blok "qr".
     */
    private function defaultLayout(): array
    {
        $textLayout = [
            ['nomor', 95, 4.9, 18, 610, 'right'],
            ['institusi', 50, 20.3, 28, 970, ''],
            ['akreditasiInstitusi', 50, 25.1, 13, 780, ''],
            ['pernyataan', 50, 30, 18, 800, ''],
            ['nama', 50, 34.2, 46, 850, ''],
            ['identitas', 50, 41.1, 18, 900, ''],
            ['studi', 50, 46.7, 18, 1000, ''],
            ['program', 50, 50, 27, 870, ''],
            ['akreditasiProgram', 50, 53.7, 13, 820, ''],
            ['pemberian', 50, 57.9, 18, 850, ''],
            ['gelar', 50, 61.5, 27, 850, ''],
            ['hak', 50, 66.4, 18, 850, ''],
            ['tanggalTerbit', 50, 72.6, 18, 820, ''],
            ['jabatanKiri', 28, 76.8, 18, 450, ''],
            ['jabatanKanan', 72, 76.8, 18, 450, ''],
            ['namaKiri', 28, 89, 18, 450, ''],
            ['namaKanan', 72, 89, 18, 450, ''],
            ['nidnKiri', 28, 92.3, 13, 430, ''],
            ['nidnKanan', 72, 92.3, 13, 430, ''],
        ];

        $layout = [];
        foreach ($textLayout as [$key, $x, $y, $size, $width, $extra]) {
            $layout[$key] = [
                'x' => round($x * 2.97, 1),
                'y' => round($y * 2.1, 1),
                'size' => round($size * 0.75, 1),
                'width' => round($width * 297 / 1122, 1),
                'align' => $extra === 'right' ? 'right' : 'center',
            ];
        }

        // Blok QR verifikasi (ukuran = sisi kotak dalam mm). Dapat digeser di editor.
        $layout['qr'] = ['x' => 268.0, 'y' => 176.0, 'size' => 24.0, 'width' => 24.0, 'align' => 'center'];

        return $layout;
    }
};
