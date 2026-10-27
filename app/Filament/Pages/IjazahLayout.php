<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Services\IjazahComposer;
use App\Settings\IjazahSettings;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class IjazahLayout extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrows-pointing-out';

    protected static ?string $navigationGroup = 'Ijazah';

    protected static ?string $navigationLabel = 'Tata Letak Ijazah';

    protected static ?string $title = 'Tata Letak Ijazah';

    protected static ?string $slug = 'tata-letak-ijazah';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.ijazah-layout';

    /** @var array<string,array<string,mixed>> */
    public array $positions = [];

    /** @var array<string,string> */
    public array $blocks = [];

    public bool $showQr = true;

    /** @var array<string,string> */
    public array $labels = [
        'nomor' => 'Nomor Ijazah',
        'institusi' => 'Nama perguruan tinggi',
        'akreditasiInstitusi' => 'Akreditasi institusi',
        'pernyataan' => 'Kalimat pembuka',
        'nama' => 'Nama mahasiswa',
        'identitas' => 'Tempat/tanggal lahir dan NIM',
        'studi' => 'Kalimat penyelesaian studi',
        'program' => 'Program studi',
        'akreditasiProgram' => 'Akreditasi program studi',
        'pemberian' => 'Kalimat pemberian gelar',
        'gelar' => 'Gelar',
        'hak' => 'Hak dan kewajiban',
        'tanggalTerbit' => 'Tanggal penerbitan',
        'jabatanKiri' => 'Jabatan kiri',
        'jabatanKanan' => 'Jabatan kanan',
        'namaKiri' => 'Nama penandatangan kiri',
        'namaKanan' => 'Nama penandatangan kanan',
        'nidnKiri' => 'NIDN kiri',
        'nidnKanan' => 'NIDN kanan',
        'qr' => 'Kode QR verifikasi',
    ];

    /** @var array<string,string> */
    public array $extras = IjazahComposer::EXTRAS;

    /** @var array<string,array<string,mixed>> */
    public array $defaults = [];

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->can('update_diploma'));
    }

    public function mount(IjazahSettings $settings): void
    {
        $this->positions = $settings->layout;
        $this->showQr = $settings->show_qr;
        $this->blocks = (new IjazahComposer($settings))->blocksForSample();
        $this->defaults = self::defaultLayout();
    }

    /**
     * Posisi awal pabrik (sama dengan settings migration) untuk tombol
     * "Kembalikan". Blok yang sudah ada di settings tetapi tak ada di sini
     * (mis. tambahan masa depan) ditangani di sisi klien.
     *
     * @return array<string,array<string,mixed>>
     */
    public static function defaultLayout(): array
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
        $layout['qr'] = ['x' => 268.0, 'y' => 176.0, 'size' => 24.0, 'width' => 24.0, 'align' => 'center'];

        return $layout;
    }

    /**
     * Simpan posisi dari editor. Nilai diklem agar tetap di dalam halaman.
     *
     * @param  array<string,array<string,mixed>>  $layout
     */
    public function saveLayout(array $layout): void
    {
        abort_unless((bool) (auth()->user()?->can('update_diploma')), 403);

        $settings = app(IjazahSettings::class);
        $current = $settings->layout;

        foreach ($current as $key => $pos) {
            $incoming = $layout[$key] ?? null;
            if (! is_array($incoming)) {
                continue;
            }
            foreach ([['x', 0, 297], ['y', 0, 210], ['size', 6, 60], ['width', 20, 297]] as [$part, $min, $max]) {
                if (isset($incoming[$part]) && is_numeric($incoming[$part])) {
                    $current[$key][$part] = round(min($max, max($min, (float) $incoming[$part])), 1);
                }
            }
            if (in_array($incoming['align'] ?? null, ['center', 'left', 'right'], true)) {
                $current[$key]['align'] = $incoming['align'];
            }
        }

        $settings->layout = $current;
        $settings->save();
        $this->positions = $current;

        Notification::make()->title('Tata letak ijazah tersimpan.')->success()->send();
    }
}
