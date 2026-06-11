<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Berita & Informasi';

    protected static ?string $navigationLabel = 'Halaman';

    protected static ?string $modelLabel = 'Halaman';

    protected static ?string $pluralModelLabel = 'Halaman';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Halaman')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Halaman')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, ?string $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->prefix(url('/halaman/'))
                            ->helperText('Alamat halaman, mis. "fasilitas" → /halaman/fasilitas.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Banner / Hero')
                    ->description('Tampilan bagian atas halaman. Kosongkan gambar untuk memakai hero gradient default.')
                    ->schema([
                        Forms\Components\TextInput::make('subtitle')
                            ->label('Subjudul')
                            ->maxLength(255)
                            ->helperText('Teks pendukung di bawah judul (opsional).'),
                        Forms\Components\FileUpload::make('banner_image')
                            ->label('Gambar Banner')
                            ->image()
                            ->disk('public')
                            ->directory('pages/banners')
                            ->imageEditor()
                            ->maxSize(4096)
                            ->helperText('Disarankan lanskap ~1920×600 px.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Susunan Konten')
                    ->description('Tambah, susun ulang (geser), dan hapus blok untuk membangun tampilan halaman.')
                    ->schema([
                        Forms\Components\Builder::make('blocks')
                            ->label('Blok Konten')
                            ->blocks(\App\Support\ContentBlocks::make())
                            ->blockNumbers(false)
                            ->collapsible()
                            ->cloneable()
                            ->addActionLabel('Tambah blok')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Terbitkan')
                            ->default(true)
                            ->helperText('Nonaktifkan untuk menyembunyikan halaman dari publik.'),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Deskripsi SEO (meta description)')
                            ->rows(2)
                            ->maxLength(255)
                            ->helperText('Ringkasan singkat untuk mesin pencari & pratinjau saat dibagikan.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('URL')
                    ->formatStateUsing(fn (string $state): string => '/halaman/'.$state)
                    ->url(fn (Page $record): string => $record->url, true)
                    ->color('primary'),
                Tables\Columns\IconColumn::make('is_published')->label('Terbit')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Diperbarui')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')->label('Status terbit'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('title');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
