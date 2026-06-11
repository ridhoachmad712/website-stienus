<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatResource\Pages;
use App\Models\Stat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StatResource extends Resource
{
    protected static ?string $model = Stat::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Tampilan Beranda';

    protected static ?string $navigationLabel = 'Statistik';

    protected static ?string $modelLabel = 'Statistik';

    protected static ?string $pluralModelLabel = 'Statistik';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('value')->label('Angka/Nilai')->required()->maxLength(255)
                    ->placeholder('mis. 1.200+ atau A'),
                Forms\Components\TextInput::make('label')->label('Keterangan')->required()->maxLength(255)
                    ->placeholder('mis. Mahasiswa Aktif'),
                Forms\Components\TextInput::make('icon')->label('Ikon (Heroicon)')->maxLength(255)
                    ->placeholder('mis. users, academic-cap, trophy')
                    ->helperText('Nama heroicon tanpa awalan. Lihat heroicons.com.'),
                Forms\Components\Toggle::make('is_active')->label('Tampilkan')->default(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('value')->label('Angka')->weight('bold'),
                Tables\Columns\TextColumn::make('label')->label('Keterangan')->searchable(),
                Tables\Columns\TextColumn::make('icon')->label('Ikon')->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListStats::route('/'),
            'create' => Pages\CreateStat::route('/create'),
            'edit' => Pages\EditStat::route('/{record}/edit'),
        ];
    }
}
