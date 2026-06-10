<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Tampilan Beranda';

    protected static ?string $navigationLabel = 'Menu Navigasi';

    protected static ?string $modelLabel = 'Menu';

    protected static ?string $pluralModelLabel = 'Menu Navigasi';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Teks Menu')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('parent_id')
                    ->label('Induk (untuk submenu/dropdown)')
                    ->placeholder('— Menu Utama —')
                    ->options(fn (?MenuItem $record): array => MenuItem::query()
                        ->whereNull('parent_id')
                        ->where('is_button', false)
                        ->when($record, fn ($q) => $q->whereKeyNot($record->getKey()))
                        ->orderBy('order')
                        ->pluck('label', 'id')
                        ->all())
                    ->searchable()
                    ->helperText('Kosongkan untuk menu utama. Pilih induk agar menjadi item dropdown.'),
                Forms\Components\TextInput::make('url')
                    ->label('Tautan (URL)')
                    ->maxLength(255)
                    ->placeholder('/berita atau https://...')
                    ->helperText('Boleh dikosongkan untuk induk dropdown yang hanya membuka submenu.'),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
                Forms\Components\Toggle::make('is_button')
                    ->label('Tampilkan sebagai tombol (CTA)')
                    ->helperText('Mis. tombol "Daftar PMB" di kanan navbar. Hanya untuk menu utama.'),
                Forms\Components\Toggle::make('open_in_new_tab')->label('Buka di tab baru'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('parent'))
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Teks')
                    ->description(fn (MenuItem $r): ?string => $r->parent ? '↳ submenu dari '.$r->parent->label : null)
                    ->searchable()
                    ->weight(fn (MenuItem $r) => $r->parent_id ? null : 'bold'),
                Tables\Columns\TextColumn::make('url')->label('Tautan')->placeholder('—')->toggleable(),
                Tables\Columns\IconColumn::make('is_button')->label('Tombol')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('order')->label('Urutan')->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('parent_id')
                    ->label('Jenis')
                    ->placeholder('Semua')
                    ->trueLabel('Submenu saja')
                    ->falseLabel('Menu utama saja')
                    ->queries(
                        true: fn ($q) => $q->whereNotNull('parent_id'),
                        false: fn ($q) => $q->whereNull('parent_id'),
                        blank: fn ($q) => $q,
                    ),
            ])
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
