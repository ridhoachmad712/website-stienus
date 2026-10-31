<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Settings\IjazahSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageIjazahSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = IjazahSettings::class;

    protected static ?string $navigationGroup = 'Ijazah';

    protected static ?string $navigationLabel = 'Template Ijazah';

    protected static ?string $title = 'Template Teks Ijazah';

    protected static ?string $slug = 'pengaturan-ijazah';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->can('update_diploma'));
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Template Umum')
                ->description('Teks tetap yang dipakai kedua prodi. Lima data per mahasiswa (nomor, nama, tempat/tanggal lahir, NIM) diisi pada menu Ijazah, bukan di sini.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('label_nomor')->label('Teks sebelum nomor ijazah')->required()->maxLength(120),
                    Forms\Components\TextInput::make('akreditasi_institusi')->label('Akreditasi institusi')->required()->maxLength(255)->columnSpanFull(),
                    Forms\Components\TextInput::make('awal_identitas')->label('Teks sebelum tempat lahir')->required()->maxLength(60),
                    Forms\Components\TextInput::make('label_nim')->label('Teks sebelum NIM')->required()->maxLength(60),
                    Forms\Components\TextInput::make('tempat_terbit')->label('Tempat penerbitan')->required()->maxLength(80)
                        ->helperText('Tanggal penerbitan diambil otomatis dari tanggal penerbitan ijazah: "Diberikan di <tempat>, <tanggal>".'),
                    Forms\Components\Toggle::make('show_qr')->label('Cetak kode QR verifikasi pada ijazah')
                        ->helperText('Posisi QR diatur di menu Tata Letak Ijazah.'),
                    Forms\Components\TextInput::make('jabatan_kiri')->label('Jabatan penandatangan kiri')->required()->maxLength(120),
                    Forms\Components\TextInput::make('jabatan_kanan')->label('Jabatan penandatangan kanan')->required()->maxLength(120),
                    Forms\Components\TextInput::make('nama_kiri')->label('Nama penandatangan kiri')->required()->maxLength(120),
                    Forms\Components\TextInput::make('nama_kanan')->label('Nama penandatangan kanan')->required()->maxLength(120),
                    Forms\Components\TextInput::make('nidn_kiri')->label('NIDN penandatangan kiri')->required()->maxLength(60),
                    Forms\Components\TextInput::make('nidn_kanan')->label('NIDN penandatangan kanan')->required()->maxLength(60),
                ]),

            Forms\Components\Section::make('Kalimat Umum Ijazah')
                ->description('Kalimat yang sama untuk semua prodi — cukup diatur sekali.')
                ->schema([
                    Forms\Components\TextInput::make('pernyataan')->label('Kalimat pembuka')->required()->maxLength(255),
                    Forms\Components\Textarea::make('studi')->label('Kalimat penyelesaian studi')->required()->maxLength(500)->rows(2),
                    Forms\Components\TextInput::make('pemberian')->label('Kalimat pemberian ijazah')->required()->maxLength(255),
                    Forms\Components\Textarea::make('hak')->label('Kalimat hak dan kewajiban')->required()->maxLength(500)->rows(2),
                ]),

            Forms\Components\Section::make('Daftar Program Studi')
                ->description('Bagian yang berbeda antar prodi. Dipakai bersama oleh ijazah & transkrip. Tambah satu baris per prodi.')
                ->schema([
                    Forms\Components\Repeater::make('prodi_profiles')->label(null)
                        ->schema([
                            Forms\Components\TextInput::make('name')->label('Nama prodi')->required()->maxLength(80)->helperText('Mis. Akuntansi'),
                            Forms\Components\TextInput::make('match')->label('Kata kunci pencocokan')->required()->maxLength(80)
                                ->helperText('Dicocokkan dengan program studi pada data ijazah/transkrip, mis. "akuntansi".'),
                            Forms\Components\TextInput::make('program')->label('Tulisan program studi')->required()->maxLength(120)
                                ->helperText('Mis. Program Studi S-1 Akuntansi')->columnSpanFull(),
                            Forms\Components\TextInput::make('degree_suffix')->label('Gelar setelah nama')->maxLength(40)->helperText('Mis. S.Ak.'),
                            Forms\Components\TextInput::make('gelar')->label('Gelar yang diberikan')->required()->maxLength(120)->helperText('Mis. SARJANA AKUNTANSI (S.Ak.)'),
                            Forms\Components\TextInput::make('akreditasi')->label('Akreditasi program studi')->maxLength(255)->columnSpanFull()
                                ->helperText('Isi sesuai SK resmi; kosongkan bila belum ada (jangan mengarang). Wajib terisi sebelum ijazah prodi ini dicetak.'),
                        ])->columns(2)
                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                        ->addActionLabel('Tambah program studi')->reorderable()->collapsible()->defaultItems(0),
                ]),
        ]);
    }
}
