<?php

namespace App\Filament\Pages;

use App\Settings\HomeSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHomeSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static string $settings = HomeSettings::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Konten Beranda';

    protected static ?string $title = 'Pengaturan Beranda';

    protected static ?int $navigationSort = 3;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Tampilan Hero')
                    ->description('Pilih tampilan bagian paling atas beranda: Hero Teks atau Slider gambar.')
                    ->schema([
                        Forms\Components\Radio::make('hero_type')
                            ->label('Tipe Tampilan')
                            ->options([
                                'text' => 'Hero Teks',
                                'slider' => 'Slider Gambar',
                            ])
                            ->descriptions([
                                'text' => 'Menampilkan judul, badge, subjudul, dan tombol (diatur di bawah).',
                                'slider' => 'Menampilkan slider gambar (kelola di menu Slider). Jika belum ada slide aktif, otomatis memakai Hero Teks.',
                            ])
                            ->required()
                            ->default('slider')
                            ->inline()
                            ->inlineLabel(false),
                    ]),

                Forms\Components\Section::make('Hero (Bagian Atas)')
                    ->description('Digunakan saat Tipe Tampilan = Hero Teks.')
                    ->schema([
                        Forms\Components\TextInput::make('hero_badge')
                            ->label('Teks Badge')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('hero_title')
                            ->label('Judul Utama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('hero_highlight')
                            ->label('Kata yang Disorot')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Bagian dari judul ini akan diberi warna aksen. Harus persis sama dengan potongan kata di Judul Utama.'),
                        Forms\Components\Textarea::make('hero_subtitle')
                            ->label('Subjudul / Deskripsi')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('hero_cta1_text')
                            ->label('Tombol 1 — Teks')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('hero_cta1_url')
                            ->label('Tombol 1 — Tautan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('hero_cta2_text')
                            ->label('Tombol 2 — Teks')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('hero_cta2_url')
                            ->label('Tombol 2 — Tautan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('hero_side_image')
                            ->label('Foto Hero (Sisi Kanan)')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->imageEditor()
                            ->maxSize(4096)
                            ->columnSpanFull()
                            ->helperText('Opsional. Jika diisi, foto ini menggantikan kartu statistik di sisi kanan hero. Kosongkan untuk menampilkan kartu statistik.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Bagian Berita')
                    ->schema([
                        Forms\Components\TextInput::make('news_eyebrow')->label('Label Kecil')->required()->maxLength(255),
                        Forms\Components\TextInput::make('news_title')->label('Judul Bagian')->required()->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Bagian Program Studi')
                    ->schema([
                        Forms\Components\TextInput::make('programs_eyebrow')->label('Label Kecil')->required()->maxLength(255),
                        Forms\Components\TextInput::make('programs_title')->label('Judul Bagian')->required()->maxLength(255),
                        Forms\Components\Textarea::make('programs_subtitle')->label('Subjudul')->required()->rows(2)->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Bagian Video Profil')
                    ->description('Kosongkan URL video untuk menyembunyikan bagian ini.')
                    ->schema([
                        Forms\Components\TextInput::make('video_url')->label('URL Video YouTube')->maxLength(255)
                            ->placeholder('https://www.youtube.com/watch?v=...'),
                        Forms\Components\TextInput::make('video_title')->label('Judul')->required()->maxLength(255),
                        Forms\Components\Textarea::make('video_subtitle')->label('Subjudul')->required()->rows(2)->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Bagian Agenda')
                    ->schema([
                        Forms\Components\TextInput::make('agenda_eyebrow')->label('Label Kecil')->required()->maxLength(255),
                        Forms\Components\TextInput::make('agenda_title')->label('Judul Bagian')->required()->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ajakan (Call to Action)')
                    ->schema([
                        Forms\Components\TextInput::make('cta_title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                        Forms\Components\Textarea::make('cta_subtitle')->label('Deskripsi')->required()->rows(2)->columnSpanFull(),
                        Forms\Components\TextInput::make('cta_button_text')->label('Teks Tombol')->required()->maxLength(255),
                        Forms\Components\TextInput::make('cta_button_url')->label('Tautan Tombol')->required()->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }
}
