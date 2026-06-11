<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchievementResource\Pages;
use App\Models\Achievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Berita & Informasi';

    protected static ?string $navigationLabel = 'Prestasi';

    protected static ?string $modelLabel = 'Prestasi';

    protected static ?string $pluralModelLabel = 'Prestasi';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Judul Prestasi')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\TextInput::make('category')->label('Kategori')->maxLength(255)
                    ->datalist(['Akademik', 'Mahasiswa', 'Dosen', 'Institusi', 'Olahraga', 'Seni']),
                Forms\Components\DatePicker::make('date')->label('Tanggal')->native(false)->displayFormat('d M Y'),
                Forms\Components\Textarea::make('description')->label('Deskripsi')->rows(4)->columnSpanFull(),
                Forms\Components\FileUpload::make('image')->label('Gambar')->image()
                    ->disk('public')->directory('achievements')->imageEditor()->maxSize(4096)->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')->label('Tampilkan')->default(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Gambar')->disk('public')->square(),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable()->wrap(),
                Tables\Columns\TextColumn::make('category')->label('Kategori')->badge()->sortable(),
                Tables\Columns\TextColumn::make('date')->label('Tanggal')->date('d M Y')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->label('Kategori')
                    ->options(fn (): array => Achievement::query()->whereNotNull('category')->distinct()->pluck('category', 'category')->all()),
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
            ->defaultSort('date', 'desc')
            ->reorderable('order');
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
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }
}
