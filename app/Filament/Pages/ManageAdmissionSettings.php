<?php

namespace App\Filament\Pages;

use App\Settings\AdmissionSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageAdmissionSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static string $settings = AdmissionSettings::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Konten PMB';

    protected static ?string $title = 'Penerimaan Mahasiswa Baru';

    protected static ?int $navigationSort = 5;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Header')
                    ->schema([
                        Forms\Components\TextInput::make('headline')->label('Judul')->required()->maxLength(255),
                        Forms\Components\TextInput::make('subheadline')->label('Subjudul')->required()->maxLength(255),
                        Forms\Components\RichEditor::make('intro')->label('Pengantar')->columnSpanFull(),
                        Forms\Components\Toggle::make('form_enabled')->label('Aktifkan formulir pendaftaran online'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Alur Pendaftaran')
                    ->schema([
                        Forms\Components\Repeater::make('steps')
                            ->label('Langkah')
                            ->schema([
                                Forms\Components\TextInput::make('title')->label('Judul Langkah')->required(),
                                Forms\Components\Textarea::make('description')->label('Keterangan')->required()->rows(2),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\RichEditor::make('schedule')->label('Jadwal Pendaftaran'),
                        Forms\Components\RichEditor::make('fee_info')->label('Informasi Biaya'),
                        Forms\Components\FileUpload::make('brochure')->label('Brosur (PDF/Gambar)')
                            ->disk('public')->directory('admission')->maxSize(8192)
                            ->acceptedFileTypes(['application/pdf', 'image/*']),
                    ]),
            ]);
    }
}
