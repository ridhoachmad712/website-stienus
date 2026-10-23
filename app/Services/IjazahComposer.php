<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Diploma;
use App\Settings\IjazahSettings;

/**
 * Menyusun teks tiap blok ijazah dari data record + IjazahSettings.
 * Dipakai oleh halaman cetak dan editor tata letak (untuk pratinjau).
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
     * @return array<string,string> key blok => teks
     */
    public function blocksForDiploma(Diploma $d): array
    {
        $prodi = $d->prodiKey() === 'manajemen' ? $this->settings->manajemen : $this->settings->akuntansi;

        return $this->compose([
            'nomor' => (string) $d->diploma_number,
            'nama' => (string) $d->student_name,
            'nim' => (string) $d->student_number,
            'tempatLahir' => (string) $d->birth_place,
            'tanggalLahir' => $d->birth_date?->translatedFormat('j F Y') ?? '',
            'institusi' => (string) $d->institution_name,
            'tanggalTerbit' => $d->issue_date?->translatedFormat('j F Y') ?? '',
        ], $prodi);
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
        ], $this->settings->akuntansi);
    }

    /**
     * @param  array<string,string>  $d
     * @param  array<string,string>  $prodi
     * @return array<string,string>
     */
    private function compose(array $d, array $prodi): array
    {
        $s = $this->settings;
        $akhiran = trim((string) ($prodi['akhiran_nama'] ?? ''));
        $nama = $d['nama'].($akhiran !== '' ? ', '.$akhiran : '');

        $tempat = trim($s->awal_identitas.' '.$d['tempatLahir']);
        $tempat = rtrim($tempat, " ,");
        $identitas = trim($tempat.', '.$d['tanggalLahir'].' '.$s->label_nim.' '.$d['nim']);

        return [
            'nomor' => trim($s->label_nomor.' '.$d['nomor']),
            'institusi' => $d['institusi'],
            'akreditasiInstitusi' => $s->akreditasi_institusi,
            'pernyataan' => (string) ($prodi['pernyataan'] ?? ''),
            'nama' => $nama,
            'identitas' => $identitas,
            'studi' => (string) ($prodi['studi'] ?? ''),
            'program' => (string) ($prodi['program'] ?? ''),
            'akreditasiProgram' => (string) ($prodi['akreditasi_program'] ?? ''),
            'pemberian' => (string) ($prodi['pemberian'] ?? ''),
            'gelar' => (string) ($prodi['gelar'] ?? ''),
            'hak' => (string) ($prodi['hak'] ?? ''),
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
