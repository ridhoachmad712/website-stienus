<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $akuntansi = $this->read('akuntansi');
        $manajemen = $this->read('manajemen');

        // Kalimat umum (identik antar prodi) -> cukup satu kali.
        $this->migrator->add('ijazah.pernyataan', $akuntansi['pernyataan'] ?? 'Dengan ini menyatakan bahwa :');
        $this->migrator->add('ijazah.studi', $akuntansi['studi'] ?? 'Telah menyelesaikan studi dengan baik dan memenuhi segala syarat Pendidikan Sarjana (S-1) pada :');
        $this->migrator->add('ijazah.pemberian', $akuntansi['pemberian'] ?? 'oleh sebab itu kepadanya diberikan Ijazah dan Gelar :');
        $this->migrator->add('ijazah.hak', $akuntansi['hak'] ?? 'beserta segala hak dan kewajiban yang melekat pada gelar tersebut.');

        // Daftar prodi dari bagian yang berbeda; pertahankan nilai yang sudah ada.
        $profiles = [];
        foreach ([['Akuntansi', 'akuntansi', $akuntansi], ['Manajemen', 'manajemen', $manajemen]] as [$name, $match, $src]) {
            if (! $src) {
                continue;
            }
            $profiles[] = [
                'name' => $name,
                'match' => $match,
                'program' => $src['program'] ?? '',
                'degree_suffix' => $src['akhiran_nama'] ?? '',
                'gelar' => $src['gelar'] ?? '',
                'akreditasi' => $src['akreditasi_program'] ?? '',
            ];
        }
        if (! $profiles) {
            $profiles = [
                ['name' => 'Akuntansi', 'match' => 'akuntansi', 'program' => 'Program Studi S-1 Akuntansi', 'degree_suffix' => 'S.Ak.', 'gelar' => 'SARJANA AKUNTANSI (S.Ak.)', 'akreditasi' => 'Akreditasi Program Studi LAMEMBA No. 2464/DE/A.5/AR.10/VII/2025'],
                ['name' => 'Manajemen', 'match' => 'manajemen', 'program' => 'Program Studi S-1 Manajemen', 'degree_suffix' => 'S.M.', 'gelar' => 'SARJANA MANAJEMEN (S.M.)', 'akreditasi' => ''],
            ];
        }
        $this->migrator->add('ijazah.prodi_profiles', $profiles);

        // Hapus blok per-prodi lama (langsung di DB agar tidak tergantung properti kelas).
        DB::table('settings')->where('group', 'ijazah')->whereIn('name', ['akuntansi', 'manajemen'])->delete();
    }

    private function read(string $name): array
    {
        $payload = DB::table('settings')->where('group', 'ijazah')->where('name', $name)->value('payload');
        $decoded = $payload !== null ? json_decode($payload, true) : null;

        return is_array($decoded) ? $decoded : [];
    }
};
