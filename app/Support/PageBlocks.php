<?php

declare(strict_types=1);

namespace App\Support;

use Filament\Forms;

/**
 * Blok konten kaya untuk Halaman (superset dari ContentBlocks): mendukung
 * pengaturan tampilan (latar, lebar) per blok, blok layout, dan blok dinamis.
 */
class PageBlocks
{
    /**
     * Pengaturan tampilan yang ditambahkan ke setiap blok.
     *
     * @return array<int, \Filament\Forms\Components\Component>
     */
    protected static function appearance(string $defaultWidth = 'narrow'): array
    {
        return [
            Forms\Components\Fieldset::make('Tampilan')
                ->schema([
                    Forms\Components\Select::make('bg')->label('Latar')->options([
                        'none' => 'Tanpa latar',
                        'white' => 'Putih',
                        'gray' => 'Abu',
                        'brand' => 'Gradient Brand (gelap)',
                    ])->default('none'),
                    Forms\Components\Select::make('width')->label('Lebar')->options([
                        'narrow' => 'Sempit (fokus teks)',
                        'wide' => 'Lebar',
                    ])->default($defaultWidth),
                    Forms\Components\Select::make('padding')->label('Jarak atas-bawah')->options([
                        'sm' => 'Kecil',
                        'md' => 'Sedang',
                        'lg' => 'Besar',
                    ])->default('md'),
                ])
                ->columns(3),
        ];
    }

    /** @return array<int, Forms\Components\Builder\Block> */
    public static function make(): array
    {
        return [
            Forms\Components\Builder\Block::make('rich_text')->label('Teks')->icon('heroicon-o-bars-3-bottom-left')
                ->schema([
                    Forms\Components\RichEditor::make('content')->label('Isi Teks')
                        ->fileAttachmentsDisk('public')->fileAttachmentsDirectory('pages'),
                    ...self::appearance(),
                ]),

            Forms\Components\Builder\Block::make('heading')->label('Judul Bagian')->icon('heroicon-o-h1')
                ->schema([
                    Forms\Components\TextInput::make('text')->label('Teks Judul')->required(),
                    Forms\Components\Select::make('level')->label('Ukuran')->options(['h2' => 'Besar', 'h3' => 'Sedang', 'h4' => 'Kecil'])->default('h2'),
                    Forms\Components\Select::make('align')->label('Perataan')->options(['left' => 'Kiri', 'center' => 'Tengah'])->default('left'),
                    ...self::appearance(),
                ])->columns(3),

            Forms\Components\Builder\Block::make('image')->label('Gambar')->icon('heroicon-o-photo')
                ->schema([
                    Forms\Components\FileUpload::make('image')->label('Gambar')->image()->disk('public')->directory('pages')->imageEditor()->maxSize(4096)->required(),
                    Forms\Components\TextInput::make('caption')->label('Keterangan'),
                    ...self::appearance(),
                ]),

            Forms\Components\Builder\Block::make('image_text')->label('Gambar + Teks')->icon('heroicon-o-view-columns')
                ->schema([
                    Forms\Components\FileUpload::make('image')->label('Gambar')->image()->disk('public')->directory('pages')->imageEditor()->maxSize(4096)->required(),
                    Forms\Components\Select::make('position')->label('Posisi Gambar')->options(['left' => 'Kiri', 'right' => 'Kanan'])->default('left'),
                    Forms\Components\RichEditor::make('content')->label('Teks')->columnSpanFull(),
                    ...self::appearance('wide'),
                ])->columns(2),

            Forms\Components\Builder\Block::make('video')->label('Video YouTube')->icon('heroicon-o-play-circle')
                ->schema([
                    Forms\Components\TextInput::make('url')->label('URL YouTube')->required()->placeholder('https://www.youtube.com/watch?v=...'),
                    ...self::appearance(),
                ]),

            Forms\Components\Builder\Block::make('quote')->label('Kutipan')->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->schema([
                    Forms\Components\Textarea::make('text')->label('Kutipan')->required()->rows(3),
                    Forms\Components\TextInput::make('author')->label('Sumber/Penulis'),
                    ...self::appearance(),
                ]),

            Forms\Components\Builder\Block::make('cta')->label('Ajakan (Tombol)')->icon('heroicon-o-cursor-arrow-rays')
                ->schema([
                    Forms\Components\TextInput::make('title')->label('Judul'),
                    Forms\Components\Textarea::make('text')->label('Deskripsi')->rows(2),
                    Forms\Components\TextInput::make('button_label')->label('Teks Tombol'),
                    Forms\Components\TextInput::make('button_url')->label('Tautan Tombol'),
                    ...self::appearance('wide'),
                ])->columns(2),

            Forms\Components\Builder\Block::make('cards')->label('Kartu / Fitur')->icon('heroicon-o-squares-2x2')
                ->schema([
                    Forms\Components\TextInput::make('heading')->label('Judul Bagian (opsional)'),
                    Forms\Components\Repeater::make('items')->label('Kartu')->schema([
                        Forms\Components\TextInput::make('icon')->label('Ikon (heroicon)')->placeholder('mis. academic-cap'),
                        Forms\Components\TextInput::make('title')->label('Judul')->required(),
                        Forms\Components\Textarea::make('text')->label('Deskripsi')->rows(2),
                    ])->columns(3)->defaultItems(3)->grid(2)->columnSpanFull(),
                    ...self::appearance('wide'),
                ]),

            Forms\Components\Builder\Block::make('stats')->label('Statistik')->icon('heroicon-o-chart-bar-square')
                ->schema([
                    Forms\Components\Repeater::make('items')->label('Angka')->schema([
                        Forms\Components\TextInput::make('value')->label('Angka')->required(),
                        Forms\Components\TextInput::make('label')->label('Keterangan')->required(),
                        Forms\Components\TextInput::make('icon')->label('Ikon')->placeholder('users'),
                    ])->columns(3)->defaultItems(3)->columnSpanFull(),
                    ...self::appearance('wide'),
                ]),

            Forms\Components\Builder\Block::make('steps')->label('Langkah / Alur')->icon('heroicon-o-list-bullet')
                ->schema([
                    Forms\Components\TextInput::make('heading')->label('Judul Bagian (opsional)'),
                    Forms\Components\Repeater::make('items')->label('Langkah')->schema([
                        Forms\Components\TextInput::make('title')->label('Judul')->required(),
                        Forms\Components\Textarea::make('text')->label('Keterangan')->rows(2),
                    ])->columns(2)->defaultItems(3)->columnSpanFull(),
                    ...self::appearance('wide'),
                ]),

            Forms\Components\Builder\Block::make('accordion')->label('Akordeon')->icon('heroicon-o-queue-list')
                ->schema([
                    Forms\Components\TextInput::make('heading')->label('Judul Bagian (opsional)'),
                    Forms\Components\Repeater::make('items')->label('Item')->schema([
                        Forms\Components\TextInput::make('title')->label('Pertanyaan/Judul')->required(),
                        Forms\Components\Textarea::make('content')->label('Jawaban/Isi')->required()->rows(2),
                    ])->defaultItems(2)->columnSpanFull(),
                    ...self::appearance(),
                ]),

            Forms\Components\Builder\Block::make('columns')->label('Kolom')->icon('heroicon-o-rectangle-group')
                ->schema([
                    Forms\Components\Select::make('count')->label('Jumlah Kolom')->options([2 => '2 Kolom', 3 => '3 Kolom'])->default(2),
                    Forms\Components\Repeater::make('items')->label('Kolom')->schema([
                        Forms\Components\RichEditor::make('content')->label('Isi'),
                    ])->defaultItems(2)->columnSpanFull(),
                    ...self::appearance('wide'),
                ]),

            Forms\Components\Builder\Block::make('gallery')->label('Galeri')->icon('heroicon-o-photo')
                ->schema([
                    Forms\Components\FileUpload::make('images')->label('Foto')->image()->multiple()->reorderable()
                        ->disk('public')->directory('pages/gallery')->maxSize(4096)->columnSpanFull(),
                    ...self::appearance('wide'),
                ]),

            Forms\Components\Builder\Block::make('buttons')->label('Grup Tombol')->icon('heroicon-o-cursor-arrow-ripple')
                ->schema([
                    Forms\Components\Repeater::make('items')->label('Tombol')->schema([
                        Forms\Components\TextInput::make('label')->label('Teks')->required(),
                        Forms\Components\TextInput::make('url')->label('Tautan')->required(),
                        Forms\Components\Select::make('style')->label('Gaya')->options(['primary' => 'Utama', 'outline' => 'Garis'])->default('primary'),
                    ])->columns(3)->defaultItems(1)->columnSpanFull(),
                    ...self::appearance(),
                ]),

            Forms\Components\Builder\Block::make('embed')->label('Embed (HTML/iframe)')->icon('heroicon-o-code-bracket')
                ->schema([
                    Forms\Components\Textarea::make('html')->label('Kode HTML/iframe')->rows(4)->required()
                        ->helperText('Mis. iframe Google Maps atau video lain.'),
                    ...self::appearance('wide'),
                ]),

            Forms\Components\Builder\Block::make('dynamic')->label('Konten Dinamis')->icon('heroicon-o-rss')
                ->schema([
                    Forms\Components\Select::make('source')->label('Sumber')->options([
                        'posts' => 'Berita Terbaru',
                        'programs' => 'Program Studi',
                        'testimonials' => 'Testimoni',
                        'partners' => 'Mitra / Logo',
                    ])->required(),
                    Forms\Components\TextInput::make('heading')->label('Judul Bagian (opsional)'),
                    Forms\Components\TextInput::make('limit')->label('Jumlah (untuk Berita)')->numeric()->default(3),
                    ...self::appearance('wide'),
                ])->columns(3),
        ];
    }
}
