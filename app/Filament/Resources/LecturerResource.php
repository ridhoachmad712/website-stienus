<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LecturerResource\Pages;
use App\Models\Lecturer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LecturerResource extends Resource
{
    protected static ?string $model = Lecturer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Direktori Dosen';

    protected static ?string $modelLabel = 'Dosen';

    protected static ?string $pluralModelLabel = 'Dosen';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Dosen')
                    ->schema([
                        Forms\Components\Select::make('program_id')
                            ->label('Program Studi')
                            ->relationship('program', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('nidn')
                            ->label('NIDN')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Nomor Induk Dosen Nasional (unik).'),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title')
                            ->label('Gelar')
                            ->maxLength(255)
                            ->placeholder('Contoh: S.Kom., M.Kom.'),
                        Forms\Components\Textarea::make('expertise')
                            ->label('Bidang Keahlian')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('photo')
                            ->label('Foto Dosen')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('lecturers')
                            ->imageEditor()
                            ->maxSize(2048),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Tautan Akademik')
                    ->schema([
                        Forms\Components\TextInput::make('google_scholar_link')
                            ->label('Google Scholar')
                            ->url()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('sinta_link')
                            ->label('SINTA')
                            ->url()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                    ])
                    ->columns(2)
                    ->collapsible(),
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
                Tables\Columns\TextColumn::make('nidn')
                    ->label('NIDN')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->description(fn (Lecturer $record): ?string => $record->title)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program.name')
                    ->label('Program Studi')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('expertise')
                    ->label('Keahlian')
                    ->limit(40)
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('program_id')
                    ->label('Program Studi')
                    ->relationship('program', 'name')
                    ->searchable()
                    ->preload(),
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
            ->defaultSort('name');
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
            'index' => Pages\ListLecturers::route('/'),
            'create' => Pages\CreateLecturer::route('/create'),
            'edit' => Pages\EditLecturer::route('/{record}/edit'),
        ];
    }
}
