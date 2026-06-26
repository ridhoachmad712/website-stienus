<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomFormResource\Pages;
use App\Filament\Resources\CustomFormResource\RelationManagers\SubmissionsRelationManager;
use App\Models\CustomForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CustomFormResource extends Resource
{
    protected static ?string $model = CustomForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Halaman & Formulir';

    protected static ?string $navigationLabel = 'Formulir';

    protected static ?string $modelLabel = 'Formulir';

    protected static ?string $pluralModelLabel = 'Formulir';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Formulir')
                    ->schema([
                        Forms\Components\TextInput::make('title')->label('Judul Formulir')->required()->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, ?string $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                        Forms\Components\TextInput::make('slug')->label('Slug (URL)')->required()->maxLength(255)
                            ->unique(ignoreRecord: true)->prefix(url('/formulir/')),
                        Forms\Components\Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                        Forms\Components\TextInput::make('success_message')->label('Pesan Sukses')
                            ->placeholder('Terima kasih, formulir Anda telah dikirim.')->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Susunan Field')
                    ->description('Tambah & susun field formulir.')
                    ->schema([
                        Forms\Components\Builder::make('fields')
                            ->label('Field')
                            ->blocks(static::fieldBlocks())
                            ->addActionLabel('Tambah field')
                            ->collapsible()
                            ->cloneable()
                            ->blockNumbers(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * @return array<int, Forms\Components\Builder\Block>
     */
    protected static function fieldBlocks(): array
    {
        $label = fn () => Forms\Components\TextInput::make('label')->label('Label')->required();
        $required = fn () => Forms\Components\Toggle::make('required')->label('Wajib diisi');
        $options = fn () => Forms\Components\TagsInput::make('options')->label('Pilihan')->placeholder('Tambah opsi')->required();

        return [
            Forms\Components\Builder\Block::make('text')->label('Teks Singkat')->icon('heroicon-o-bars-3-bottom-left')
                ->schema([$label(), $required()])->columns(2),
            Forms\Components\Builder\Block::make('textarea')->label('Teks Panjang')->icon('heroicon-o-bars-4')
                ->schema([$label(), $required()])->columns(2),
            Forms\Components\Builder\Block::make('email')->label('Email')->icon('heroicon-o-envelope')
                ->schema([$label(), $required()])->columns(2),
            Forms\Components\Builder\Block::make('number')->label('Angka')->icon('heroicon-o-hashtag')
                ->schema([$label(), $required()])->columns(2),
            Forms\Components\Builder\Block::make('phone')->label('Telepon')->icon('heroicon-o-phone')
                ->schema([$label(), $required()])->columns(2),
            Forms\Components\Builder\Block::make('date')->label('Tanggal')->icon('heroicon-o-calendar')
                ->schema([$label(), $required()])->columns(2),
            Forms\Components\Builder\Block::make('select')->label('Dropdown')->icon('heroicon-o-chevron-up-down')
                ->schema([$label(), $required(), $options()])->columns(2),
            Forms\Components\Builder\Block::make('radio')->label('Pilihan (radio)')->icon('heroicon-o-list-bullet')
                ->schema([$label(), $required(), $options()])->columns(2),
            Forms\Components\Builder\Block::make('checkbox')->label('Kotak Centang')->icon('heroicon-o-check')
                ->schema([$label(), $required(), $options()])->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('URL')
                    ->formatStateUsing(fn (string $state): string => '/formulir/'.$state)
                    ->url(fn (CustomForm $r): string => $r->url, true)->color('primary'),
                Tables\Columns\TextColumn::make('submissions_count')->label('Kiriman')->counts('submissions')->badge(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
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
            SubmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomForms::route('/'),
            'create' => Pages\CreateCustomForm::route('/create'),
            'edit' => Pages\EditCustomForm::route('/{record}/edit'),
        ];
    }
}
