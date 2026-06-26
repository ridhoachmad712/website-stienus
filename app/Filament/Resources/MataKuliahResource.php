<?php

namespace App\Filament\Resources;

use App\Filament\Imports\MataKuliahImporter;
use App\Filament\Resources\MataKuliahResource\Pages;
use App\Models\MataKuliah;
use App\Models\Program;
use Filament\Actions\ImportAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MataKuliahResource extends Resource
{
    protected static ?string $model = MataKuliah::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Kurikulum';

    protected static ?string $modelLabel = 'Mata Kuliah';

    protected static ?string $pluralModelLabel = 'Mata Kuliah';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('program_id')
                    ->label('Program Studi')
                    ->options(Program::query()->orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options(array_combine(range(1, 8), array_map(fn ($s) => "Semester $s", range(1, 8))))
                    ->required(),
                Forms\Components\TextInput::make('kode')
                    ->label('Kode MK')
                    ->maxLength(20),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Mata Kuliah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sks')
                    ->label('SKS')
                    ->numeric()
                    ->required()
                    ->default(2)
                    ->minValue(1)
                    ->maxValue(6),
                Forms\Components\Select::make('jenis')
                    ->label('Jenis')
                    ->options(['Wajib' => 'Wajib', 'Pilihan' => 'Pilihan'])
                    ->required()
                    ->default('Wajib'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Tampilkan')
                    ->default(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('program.name')
                    ->label('Program Studi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Smt')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('sks')
                    ->label('SKS')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state) => $state === 'Wajib' ? 'primary' : 'warning'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('program_id')
                    ->label('Program Studi')
                    ->options(Program::query()->orderBy('name')->pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('semester')
                    ->label('Semester')
                    ->options(array_combine(range(1, 8), array_map(fn ($s) => "Semester $s", range(1, 8)))),
                Tables\Filters\SelectFilter::make('jenis')
                    ->label('Jenis')
                    ->options(['Wajib' => 'Wajib', 'Pilihan' => 'Pilihan']),
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
            ->defaultSort('program_id')
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMataKuliah::route('/'),
            'create' => Pages\CreateMataKuliah::route('/create'),
            'edit' => Pages\EditMataKuliah::route('/{record}/edit'),
        ];
    }
}
