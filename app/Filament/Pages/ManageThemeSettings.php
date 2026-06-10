<?php

namespace App\Filament\Pages;

use App\Settings\ThemeSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageThemeSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static string $settings = ThemeSettings::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Tampilan & Warna';

    protected static ?string $title = 'Pengaturan Tampilan';

    protected static ?int $navigationSort = 2;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Warna')
                    ->description('Warna utama akan diterapkan ke seluruh elemen front-end (tombol, header, aksen) tanpa perlu build ulang.')
                    ->schema([
                        Forms\Components\ColorPicker::make('primary_color')
                            ->label('Warna Utama (Brand)')
                            ->required()
                            ->helperText('Pilih warna identitas kampus. Gradasi terang/gelap dibuat otomatis.'),
                    ]),

                Forms\Components\Section::make('Gambar')
                    ->schema([
                        Forms\Components\FileUpload::make('hero_image')
                            ->label('Gambar Latar Hero (Beranda)')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->imageEditor()
                            ->maxSize(4096)
                            ->helperText('Opsional. Jika diisi, akan ditampilkan sebagai latar bagian hero beranda.'),
                    ]),
            ]);
    }
}
