<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadResource\Pages;
use App\Models\Download;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DownloadResource extends Resource
{
    protected static ?string $model = Download::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationGroup = 'Berita & Informasi';

    protected static ?string $navigationLabel = 'Unduhan';

    protected static ?string $modelLabel = 'Berkas Unduhan';

    protected static ?string $pluralModelLabel = 'Unduhan';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Judul')->required()->maxLength(255),
                Forms\Components\TextInput::make('category')->label('Kategori')->maxLength(255)
                    ->datalist(['Formulir', 'Panduan', 'SK', 'Brosur', 'Kalender Akademik']),
                Forms\Components\Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                Forms\Components\FileUpload::make('file')->label('Berkas')->required()
                    ->disk('public')->directory('downloads')->maxSize(20480)
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'image/*'])
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->label('Kategori')->badge()->sortable(),
                Tables\Columns\TextColumn::make('downloads_count')->label('Diunduh')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Ditambahkan')->dateTime('d M Y')->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->label('Kategori')
                    ->options(fn (): array => Download::query()->whereNotNull('category')->distinct()->pluck('category', 'category')->all()),
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListDownloads::route('/'),
            'create' => Pages\CreateDownload::route('/create'),
            'edit' => Pages\EditDownload::route('/{record}/edit'),
        ];
    }
}
