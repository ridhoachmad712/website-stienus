<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Settings\FooterSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageFooterSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';

    protected static string $settings = FooterSettings::class;

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Footer';

    protected static ?string $title = 'Pengaturan Footer';

    protected static ?string $slug = 'pengaturan-footer';

    protected static ?int $navigationSort = 6;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kolom Deskripsi')
                    ->description('Kolom pertama footer berisi logo dan deskripsi singkat institusi.')
                    ->schema([
                        Forms\Components\Toggle::make('show_description')
                            ->label('Tampilkan kolom deskripsi')
                            ->helperText('Teks deskripsi diatur di Pengaturan Umum → Footer.'),
                    ]),

                Forms\Components\Section::make('Kolom Tautan')
                    ->schema([
                        Forms\Components\Toggle::make('show_links_column')
                            ->label('Tampilkan kolom tautan')
                            ->live(),
                        Forms\Components\TextInput::make('links_column_title')
                            ->label('Judul Kolom')
                            ->required()
                            ->maxLength(60)
                            ->visible(fn (Forms\Get $get) => $get('show_links_column')),
                        Forms\Components\Toggle::make('use_custom_links')
                            ->label('Gunakan tautan kustom (bukan dari menu navigasi)')
                            ->live()
                            ->visible(fn (Forms\Get $get) => $get('show_links_column')),
                        Forms\Components\Repeater::make('custom_links')
                            ->label('Daftar Tautan Kustom')
                            ->schema([
                                Forms\Components\TextInput::make('label')->label('Teks')->required()->maxLength(100),
                                Forms\Components\TextInput::make('url')->label('URL')->required()->maxLength(255),
                            ])
                            ->columns(2)
                            ->addActionLabel('Tambah tautan')
                            ->reorderable()
                            ->collapsible()
                            ->visible(fn (Forms\Get $get) => $get('show_links_column') && $get('use_custom_links')),
                    ]),

                Forms\Components\Section::make('Kolom Kontak')
                    ->schema([
                        Forms\Components\Toggle::make('show_contact_column')
                            ->label('Tampilkan kolom kontak')
                            ->live(),
                        Forms\Components\TextInput::make('contact_column_title')
                            ->label('Judul Kolom')
                            ->required()
                            ->maxLength(60)
                            ->visible(fn (Forms\Get $get) => $get('show_contact_column')),
                    ]),

                Forms\Components\Section::make('Kolom Media Sosial')
                    ->schema([
                        Forms\Components\Toggle::make('show_social_column')
                            ->label('Tampilkan kolom media sosial')
                            ->live(),
                        Forms\Components\TextInput::make('social_column_title')
                            ->label('Judul Kolom')
                            ->required()
                            ->maxLength(60)
                            ->visible(fn (Forms\Get $get) => $get('show_social_column')),
                    ]),

                Forms\Components\Section::make('Kolom Tambahan')
                    ->description('Tambahkan kolom bebas dengan judul dan daftar tautan sendiri.')
                    ->schema([
                        Forms\Components\Repeater::make('extra_columns')
                            ->label(null)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Kolom')
                                    ->required()
                                    ->maxLength(60)
                                    ->columnSpanFull(),
                                Forms\Components\Repeater::make('links')
                                    ->label('Tautan')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')->label('Teks')->required()->maxLength(100),
                                        Forms\Components\TextInput::make('url')->label('URL')->required()->maxLength(255),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel('Tambah tautan')
                                    ->reorderable()
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Tambah kolom')
                            ->reorderable()
                            ->collapsible()
                            ->maxItems(3),
                    ]),
            ]);
    }
}
