<?php

namespace App\Filament\Pages;

use App\Settings\ProfileSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageProfileSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static string $settings = ProfileSettings::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Profil Kampus';

    protected static ?string $title = 'Profil Kampus';

    protected static ?int $navigationSort = 4;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Tentang & Visi-Misi')
                            ->schema([
                                Forms\Components\RichEditor::make('about')->label('Tentang Kampus'),
                                Forms\Components\Textarea::make('vision')->label('Visi')->rows(3),
                                Forms\Components\RichEditor::make('mission')->label('Misi'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Sejarah')
                            ->schema([
                                Forms\Components\RichEditor::make('history')->label('Sejarah Kampus'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Sambutan Pimpinan')
                            ->schema([
                                Forms\Components\TextInput::make('leader_name')->label('Nama Pimpinan')->maxLength(255),
                                Forms\Components\TextInput::make('leader_title')->label('Jabatan')->maxLength(255),
                                Forms\Components\FileUpload::make('leader_photo')->label('Foto Pimpinan')
                                    ->image()->disk('public')->directory('profile')->imageEditor()->maxSize(2048),
                                Forms\Components\RichEditor::make('leader_speech')->label('Isi Sambutan')->columnSpanFull(),
                            ])
                            ->columns(2),
                        Forms\Components\Tabs\Tab::make('Struktur Organisasi')
                            ->schema([
                                Forms\Components\FileUpload::make('org_structure_image')->label('Bagan Struktur (Gambar)')
                                    ->image()->disk('public')->directory('profile')->maxSize(4096),
                                Forms\Components\RichEditor::make('org_structure_text')->label('Keterangan'),
                            ]),
                    ]),
            ]);
    }
}
