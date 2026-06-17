<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Program Studi';

    protected static ?string $modelLabel = 'Program Studi';

    protected static ?string $pluralModelLabel = 'Program Studi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Program Studi')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Program Studi')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, ?string $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Otomatis dibuat dari nama, dapat disesuaikan.'),
                        Forms\Components\Select::make('degree')
                            ->label('Jenjang / Gelar')
                            ->options([
                                'D3' => 'D3 (Diploma)',
                                'D4' => 'D4 (Sarjana Terapan)',
                                'S1' => 'S1 (Sarjana)',
                                'S2' => 'S2 (Magister)',
                                'S3' => 'S3 (Doktor)',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('accreditation')
                            ->label('Akreditasi')
                            ->options([
                                'Unggul' => 'Unggul',
                                'Baik Sekali' => 'Baik Sekali',
                                'Baik' => 'Baik',
                                'A' => 'A',
                                'B' => 'B',
                                'C' => 'C',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Profil')
                    ->schema([
                        Forms\Components\Textarea::make('vision_mission')
                            ->label('Visi & Misi')
                            ->rows(6)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('profile_image')
                            ->label('Gambar Profil')
                            ->image()
                            ->disk('public')
                            ->directory('programs')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Program Studi')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('degree')
                    ->label('Jenjang')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('accreditation')
                    ->label('Akreditasi')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('lecturers_count')
                    ->label('Dosen')
                    ->counts('lecturers')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('degree')
                    ->label('Jenjang')
                    ->options([
                        'D3' => 'D3',
                        'D4' => 'D4',
                        'S1' => 'S1',
                        'S2' => 'S2',
                        'S3' => 'S3',
                    ]),
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
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
