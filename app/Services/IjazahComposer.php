<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Diploma;
use App\Settings\IjazahSettings;

/**
 * Menyusun teks tiap blok ijazah dari data record + IjazahSettings.
 * Kalimat umum diatur sekali; bagian per-prodi diambil dari daftar
 * prodi_profiles (dipakai bersama ijazah & transkrip).
 */
class IjazahComposer
{
    /** Kelas gaya tambahan per blok (selaras dengan editor statis). */
    public const EXTRAS = [
        'institusi' => 'gold',
        'namaKiri' => 'strong underlined',
        'namaKanan' => 'strong underlined',
    ];

    public function __construct(private IjazahSettings $settings) {}

    /**
     * Profil prodi yang cocok dengan nama program (mengandung kata kunci match).
     *
     * @return array<string,string>
     */
    public function profileFor(?string $programName): array
    {
        $name = mb_strtolower(trim((string) $programName));
        $profiles = $this->settings->prodi_profiles ?: [];
        foreach ($profiles as $profile) {
            $match = mb_strtolower(trim((string) ($profile['match'] ?? '')));
            if ($match !== '' && str_contains($name, $match)) {
                return $profile;
            }
        }

        return $profiles[0] ?? [];
    }

    /**
     * @return array<string,string> key blok => teks
     */
    public function blocksForDiploma(Diploma $d): array
    {
        return $this->compose([
            'nomor' => (string) $d->diploma_number,
            'nama' => (string) $d->student_name,
            'nim' => (string) $d->student_number,
            'tempatLahir' => (string) $d->birth_place,
            'tanggalLahir' => $d->birth_date?->translatedFormat('j F Y') ?? '',
            'institusi' => (string) $d->institution_name,
            'tanggalTerbit' => $d->issue_date?->translatedFormat('j F Y') ?? '',
        ], $this->profileFor($d->program_name));
    }

    /**
     * Data contoh untuk pratinjau editor tata letak.
     *
     * @return array<string,string>
     */
    public function blocksForSample(): array
    {
        return $this->compose([
            'nomor' => '093053612012025100007',
            'nama' => 'Nama Mahasiswa',
            'nim' => '22AK004',
            'tempatLahir' => 'Makassar',
            'tanggalLahir' => '2 Mei 2003',
            'institusi' => 'SEKOLAH TINGGI ILMU EKONOMI NUSANTARA MAKASSAR',
            'tanggalTerbit' => '1 September 2026',
        ], ($this->settings->prodi_profiles[0] ?? []));
    }

    /**
     * @param  array<string,string>  $d
     * @param  array<string,string>  $profile
     * @return array<string,string>
     */
    private function compose(array $d, array $profile): array
    {
        $s = $this->settings;
        $akhiran = trim((string) ($profile['degree_suffix'] ?? ''));
        $nama = $d['nama'].($akhiran !== '' ? ', '.$akhiran : '');

        $tempat = rtrim(trim($s->awal_identitas.' '.$d['tempatLahir']), ' ,');
        $identitas = trim($tempat.', '.$d['tanggalLahir'].' '.$s->label_nim.' '.$d['nim']);

        return [
            'nomor' => trim($s->label_nomor.' '.$d['nomor']),
            'institusi' => $d['institusi'],
            'akreditasiInstitusi' => $s->akreditasi_institusi,
            'pernyataan' => $s->pernyataan,
            'nama' => $nama,
            'identitas' => $identitas,
            'studi' => $s->studi,
            'program' => (string) ($profile['program'] ?? ''),
            'akreditasiProgram' => (string) ($profile['akreditasi'] ?? ''),
            'pemberian' => $s->pemberian,
            'gelar' => (string) ($profile['gelar'] ?? ''),
            'hak' => $s->hak,
            'tanggalTerbit' => trim('Diberikan di '.$s->tempat_terbit.', '.$d['tanggalTerbit']),
            'jabatanKiri' => $s->jabatan_kiri,
            'jabatanKanan' => $s->jabatan_kanan,
            'namaKiri' => $s->nama_kiri,
            'namaKanan' => $s->nama_kanan,
            'nidnKiri' => $s->nidn_kiri,
            'nidnKanan' => $s->nidn_kanan,
        ];
    }
}
