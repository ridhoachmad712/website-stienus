<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Settings\TranscriptSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageTranscriptSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $settings = TranscriptSettings::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Template Transkrip';

    protected static ?string $title = 'Template & Kop Transkrip';

    protected static ?string $slug = 'pengaturan-transkrip';

    protected static ?int $navigationSort = 9;

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->can('update_transcript'));
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Kop / Kepala Surat')
                ->description('Tercetak di bagian atas transkrip (logo diambil dari Pengaturan Umum).')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('institution_name')->label('Nama institusi')->required()->maxLength(255)->columnSpanFull(),
                    Forms\Components\TextInput::make('institution_address')->label('Alamat / kontak')->maxLength(255)->columnSpanFull(),
                    Forms\Components\Textarea::make('accreditation_text')->label('Teks akreditasi / status')->rows(2)->maxLength(600)->columnSpanFull()
                        ->helperText('Isi sesuai SK resmi. Kosongkan jika belum ada — jangan mengarang.'),
                ]),

            Forms\Components\Section::make('Default Identitas')
                ->description('Mengisi otomatis saat membuat transkrip baru; tetap bisa diubah per mahasiswa.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('faculty')->label('Fakultas')->maxLength(120),
                    Forms\Components\TextInput::make('department')->label('Jurusan')->maxLength(120),
                    Forms\Components\TextInput::make('study_program')->label('Program studi')->maxLength(120),
                    Forms\Components\TextInput::make('education_program')->label('Program pendidikan')->maxLength(120),
                    Forms\Components\TextInput::make('concentration')->label('Konsentrasi')->maxLength(120),
                ]),

            Forms\Components\Section::make('Penandatangan')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('issued_place')->label('Tempat penerbitan')->maxLength(120)->columnSpanFull(),
                    Forms\Components\TextInput::make('sign_left_label')->label('Jabatan kiri')->maxLength(120),
                    Forms\Components\TextInput::make('sign_right_label')->label('Jabatan kanan')->maxLength(120),
                    Forms\Components\TextInput::make('sign_left_name')->label('Nama kiri')->maxLength(120),
                    Forms\Components\TextInput::make('sign_right_name')->label('Nama kanan')->maxLength(120),
                    Forms\Components\TextInput::make('sign_left_id')->label('NIP/NIDN kiri')->maxLength(60),
                    Forms\Components\TextInput::make('sign_right_id')->label('NIP/NIDN kanan')->maxLength(60),
                ]),

            Forms\Components\Section::make('Perhitungan Nilai')
                ->schema([
                    Forms\Components\Fieldset::make('Bobot nilai (untuk KxN & IPK)')->columns(5)->schema([
                        Forms\Components\TextInput::make('grade_points.A')->label('A')->numeric()->required(),
                        Forms\Components\TextInput::make('grade_points.B')->label('B')->numeric()->required(),
                        Forms\Components\TextInput::make('grade_points.C')->label('C')->numeric()->required(),
                        Forms\Components\TextInput::make('grade_points.D')->label('D')->numeric()->required(),
                        Forms\Components\TextInput::make('grade_points.E')->label('E')->numeric()->required(),
                    ]),
                    Forms\Components\Repeater::make('predicates')->label('Ambang predikat (IPK ≥ minimum → predikat)')
                        ->schema([
                            Forms\Components\TextInput::make('min')->label('IPK minimum')->numeric()->step('0.01')->required(),
                            Forms\Components\TextInput::make('label')->label('Predikat')->required()->maxLength(80),
                        ])->columns(2)->defaultItems(0)->addActionLabel('Tambah ambang')->reorderable()
                        ->helperText('Sesuaikan dengan peraturan akademik. Predikat juga dapat ditimpa manual per transkrip.'),
                    Forms\Components\TagsInput::make('category_defaults')->label('Pilihan kategori mata kuliah')
                        ->helperText('Pilihan kelompok mata kuliah saat menambah baris. Tekan Enter untuk menambah.'),
                    Forms\Components\Toggle::make('show_photo')->label('Sediakan area foto pada transkrip'),
                ]),
        ]);
    }
}
