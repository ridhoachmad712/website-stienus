<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffResource\Pages;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $recordTitleAttribute = 'name';

    /** @return array<int, string> */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'position', 'unit'];
    }

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Tenaga Kependidikan';

    protected static ?string $modelLabel = 'Tenaga Kependidikan';

    protected static ?string $pluralModelLabel = 'Tenaga Kependidikan';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Tenaga Kependidikan')
                    ->schema([
                        Forms\Components\TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(255),
                        Forms\Components\TextInput::make('position')->label('Jabatan')->required()->maxLength(255)
                            ->placeholder('mis. Kepala Bagian Akademik'),
                        Forms\Components\TextInput::make('unit')->label('Unit Kerja')->maxLength(255)
                            ->datalist(['Bagian Akademik', 'Bagian Keuangan', 'Perpustakaan', 'Teknologi Informasi', 'Tata Usaha', 'Kemahasiswaan'])
                            ->helperText('Opsional, untuk pengelompokan/filter.'),
                        Forms\Components\TextInput::make('nip')->label('NIP/NIK')->maxLength(255),
                        Forms\Components\TextInput::make('email')->label('Email')->email()->maxLength(255),
                        Forms\Components\FileUpload::make('photo')->label('Foto')->image()->avatar()
                            ->disk('public')->directory('staff')->imageEditor()->maxSize(2048),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')->label('Foto')->disk('public')->circular(),
                Tables\Columns\TextColumn::make('name')->label('Nama')
                    ->description(fn (Staff $record): ?string => $record->position)
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('unit')->label('Unit')->badge()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nip')->label('NIP')->toggleable()->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unit')->label('Unit Kerja')
                    ->options(fn (): array => Staff::query()->whereNotNull('unit')->distinct()->pluck('unit', 'unit')->all()),
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
            ->paginated([25, 50, 100, 'all'])
            ->reorderRecordsTriggerAction(
                fn (Tables\Actions\Action $action, bool $isReordering) => $action
                    ->label($isReordering ? 'Selesai Mengatur' : 'Ubah Urutan')
                    ->icon($isReordering ? 'heroicon-o-check' : 'heroicon-o-arrows-up-down')
                    ->button()
                    ->color($isReordering ? 'success' : 'gray'),
            );
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
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
