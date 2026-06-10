<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static string $settings = GeneralSettings::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Umum & Kontak';

    protected static ?string $title = 'Pengaturan Umum';

    protected static ?int $navigationSort = 1;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Situs')
                    ->description('Nama, logo, dan favicon yang tampil di seluruh halaman.')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Nama Singkat')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Mis. "STIE Nusantara Makassar" — tampil di navbar & footer.'),
                        Forms\Components\TextInput::make('site_full_name')
                            ->label('Nama Lengkap Institusi')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->maxSize(2048)
                            ->helperText('Kosongkan untuk memakai ikon bawaan.'),
                        Forms\Components\FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->maxSize(1024)
                            ->helperText('Ikon kecil pada tab browser (disarankan .png/.ico persegi).'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->label('Alamat')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telepon')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('Nomor WhatsApp')
                            ->maxLength(255)
                            ->helperText('Format internasional tanpa tanda, mis. 6281234567890. Mengaktifkan tombol WA melayang.'),
                        Forms\Components\Textarea::make('map_embed')
                            ->label('Embed Peta (URL src Google Maps)')
                            ->rows(2)
                            ->columnSpanFull()
                            ->helperText('Salin nilai src dari Google Maps → Bagikan → Sematkan peta.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media Sosial')
                    ->description('Kosongkan jika tidak digunakan.')
                    ->schema([
                        Forms\Components\TextInput::make('social_facebook')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('social_instagram')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('social_youtube')
                            ->label('YouTube')
                            ->url()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('social_x')
                            ->label('X (Twitter)')
                            ->url()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Banner Pengumuman')
                    ->description('Bar tipis di paling atas setiap halaman.')
                    ->schema([
                        Forms\Components\Toggle::make('announcement_enabled')->label('Tampilkan banner pengumuman'),
                        Forms\Components\TextInput::make('announcement_text')->label('Teks Pengumuman')->maxLength(255),
                        Forms\Components\TextInput::make('announcement_url')->label('Tautan (opsional)')->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Footer')
                    ->schema([
                        Forms\Components\Textarea::make('footer_description')
                            ->label('Deskripsi Footer')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('copyright_text')
                            ->label('Teks Hak Cipta')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Tahun otomatis ditambahkan di depan teks ini.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
