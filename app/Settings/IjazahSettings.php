<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class IjazahSettings extends Settings
{
    // Template umum (berlaku untuk kedua prodi)
    public string $akreditasi_institusi;
    public string $label_nomor;
    public string $awal_identitas;   // "Lahir di"
    public string $label_nim;        // "| NIM :"
    public string $tempat_terbit;    // "Makassar" -> "Diberikan di Makassar, <tgl terbit>"
    public string $jabatan_kiri;
    public string $jabatan_kanan;
    public string $nama_kiri;
    public string $nama_kanan;
    public string $nidn_kiri;
    public string $nidn_kanan;

    // Kalimat umum ijazah — sama untuk semua prodi, diatur sekali.
    public string $pernyataan;
    public string $studi;
    public string $pemberian;
    public string $hak;

    // Daftar program studi (bagian yang berbeda saja), dipakai ijazah & transkrip.
    // Tiap item: name, match, program, degree_suffix, gelar, akreditasi
    public array $prodi_profiles;

    // Posisi tiap blok teks + blok "qr": ['x','y','size','width','align']
    public array $layout;

    // Sertakan QR verifikasi pada ijazah yang dicetak.
    public bool $show_qr;

    public static function group(): string
    {
        return 'ijazah';
    }
}
