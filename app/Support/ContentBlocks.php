<?php

declare(strict_types=1);

namespace App\Support;

use Filament\Forms;

class ContentBlocks
{
    /**
     * Definisi blok konten yang dipakai bersama oleh Halaman & Berita.
     *
     * @return array<int, Forms\Components\Builder\Block>
     */
    public static function make(): array
    {
        return [
            Forms\Components\Builder\Block::make('rich_text')
                ->label('Teks')
                ->icon('heroicon-o-bars-3-bottom-left')
                ->schema([
                    Forms\Components\RichEditor::make('content')->label('Isi Teks')
                        ->fileAttachmentsDisk('public')->fileAttachmentsDirectory('pages'),
                ]),

            Forms\Components\Builder\Block::make('heading')
                ->label('Judul Bagian')
                ->icon('heroicon-o-h1')
                ->schema([
                    Forms\Components\TextInput::make('text')->label('Teks Judul')->required(),
                    Forms\Components\Select::make('level')->label('Ukuran')
                        ->options(['h2' => 'Besar', 'h3' => 'Sedang', 'h4' => 'Kecil'])->default('h2'),
                    Forms\Components\Select::make('align')->label('Perataan')
                        ->options(['left' => 'Kiri', 'center' => 'Tengah'])->default('left'),
                ])
                ->columns(3),

            Forms\Components\Builder\Block::make('image')
                ->label('Gambar')
                ->icon('heroicon-o-photo')
                ->schema([
                    Forms\Components\FileUpload::make('image')->label('Gambar')->image()
                        ->disk('public')->directory('pages')->imageEditor()->maxSize(4096)->required(),
                    Forms\Components\TextInput::make('caption')->label('Keterangan (opsional)'),
                ]),

            Forms\Components\Builder\Block::make('image_text')
                ->label('Gambar + Teks')
                ->icon('heroicon-o-view-columns')
                ->schema([
                    Forms\Components\FileUpload::make('image')->label('Gambar')->image()
                        ->disk('public')->directory('pages')->imageEditor()->maxSize(4096)->required(),
                    Forms\Components\Select::make('position')->label('Posisi Gambar')
                        ->options(['left' => 'Kiri', 'right' => 'Kanan'])->default('left'),
                    Forms\Components\RichEditor::make('content')->label('Teks')->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Builder\Block::make('video')
                ->label('Video YouTube')
                ->icon('heroicon-o-play-circle')
                ->schema([
                    Forms\Components\TextInput::make('url')->label('URL YouTube')->required()
                        ->placeholder('https://www.youtube.com/watch?v=...'),
                ]),

            Forms\Components\Builder\Block::make('quote')
                ->label('Kutipan')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->schema([
                    Forms\Components\Textarea::make('text')->label('Kutipan')->required()->rows(3),
                    Forms\Components\TextInput::make('author')->label('Sumber/Penulis'),
                ]),

            Forms\Components\Builder\Block::make('cta')
                ->label('Ajakan (Tombol)')
                ->icon('heroicon-o-cursor-arrow-rays')
                ->schema([
                    Forms\Components\TextInput::make('title')->label('Judul'),
                    Forms\Components\Textarea::make('text')->label('Deskripsi')->rows(2),
                    Forms\Components\TextInput::make('button_label')->label('Teks Tombol'),
                    Forms\Components\TextInput::make('button_url')->label('Tautan Tombol'),
                ])
                ->columns(2),

            Forms\Components\Builder\Block::make('table')
                ->label('Tabel')
                ->icon('heroicon-o-table-cells')
                ->schema([
                    Forms\Components\TextInput::make('caption')->label('Judul Tabel (opsional)'),
                    Forms\Components\Textarea::make('headers')
                        ->label('Baris Kepala (pisahkan dengan |)')
                        ->placeholder('Nama | Jabatan | Email')
                        ->rows(2)
                        ->required(),
                    Forms\Components\Textarea::make('rows')
                        ->label('Baris Data (satu baris per baris, kolom dipisah dengan |)')
                        ->placeholder("Ahmad | Dosen | ahmad@stie.ac.id\nBudi | Mahasiswa | budi@stie.ac.id")
                        ->rows(6)
                        ->required(),
                ]),
        ];
    }
}
