<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaderResource\Pages;
use App\Models\Leader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeaderResource extends Resource
{
    protected static ?string $model = Leader::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Tampilan Beranda';

    protected static ?string $navigationLabel = 'Pimpinan';

    protected static ?string $modelLabel = 'Pimpinan';

    protected static ?string $pluralModelLabel = 'Pimpinan';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pimpinan')
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('leaders')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('position')
                            ->label('Jabatan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Ketua STIE Nusantara Makassar'),
                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->helperText('Angka lebih kecil tampil lebih dulu.'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan untuk menyembunyikan dari beranda tanpa menghapus.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),
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
            ->defaultSort('order')
            ->reorderable('order')
            ->reorderRecordsTriggerAction(
                fn (Tables\Actions\Action $action, bool $isReordering) => $action
                    ->label($isReordering ? 'Selesai Mengatur' : 'Ubah Urutan')
                    ->icon($isReordering ? 'heroicon-o-check' : 'heroicon-o-arrows-up-down')
                    ->button()
                    ->color($isReordering ? 'success' : 'gray'),
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaders::route('/'),
            'create' => Pages\CreateLeader::route('/create'),
            'edit' => Pages\EditLeader::route('/{record}/edit'),
        ];
    }
}
