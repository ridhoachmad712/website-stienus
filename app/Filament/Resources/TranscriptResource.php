<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranscriptResource\Pages;
use App\Filament\Resources\TranscriptResource\RelationManagers\CoursesRelationManager;
use App\Models\Program;
use App\Models\Transcript;
use App\Settings\TranscriptSettings;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TranscriptResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Transcript::class;

    protected static ?string $slug = 'transkrip';

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Transkrip Nilai';

    protected static ?string $modelLabel = 'Transkrip';

    protected static ?string $pluralModelLabel = 'Transkrip';

    protected static ?int $navigationSort = 9;

    public static function getPermissionPrefixes(): array
    {
        return ['view', 'view_any', 'create', 'update', 'delete', 'print'];
    }

    protected static function s(): TranscriptSettings
    {
        return app(TranscriptSettings::class);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas mahasiswa')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('student_name')->label('Nama lengkap')->required()->maxLength(255),
                    Forms\Components\TextInput::make('student_number')->label('NIM / NPM')->required()->maxLength(50),
                    Forms\Components\TextInput::make('birth_place')->label('Tempat lahir')->maxLength(120),
                    Forms\Components\DatePicker::make('birth_date')->label('Tanggal lahir')->displayFormat('d F Y')->maxDate(now()),
                    Forms\Components\TextInput::make('serial_number')->label('Nomor seri transkrip')->maxLength(100)->unique(ignoreRecord: true),
                    Forms\Components\FileUpload::make('photo_path')->label('Pas foto')->image()->avatar()
                        ->directory('transcript-photos')->disk('public')->visibility('public')->imageEditor(),
                ]),

            Forms\Components\Section::make('Program')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('program_id')->label('Program studi (katalog MK)')->relationship('program', 'name')->searchable()->preload()
                        ->helperText('Opsional. Dipakai untuk memfilter katalog mata kuliah saat menambah baris.'),
                    Forms\Components\TextInput::make('faculty')->label('Fakultas')->maxLength(120)->default(fn () => static::s()->faculty),
                    Forms\Components\TextInput::make('department')->label('Jurusan')->maxLength(120)->default(fn () => static::s()->department),
                    Forms\Components\TextInput::make('study_program')->label('Program studi')->maxLength(120)->default(fn () => static::s()->study_program),
                    Forms\Components\TextInput::make('education_program')->label('Program pendidikan')->maxLength(120)->default(fn () => static::s()->education_program),
                    Forms\Components\TextInput::make('concentration')->label('Konsentrasi')->maxLength(120)->default(fn () => static::s()->concentration),
                ]),

            Forms\Components\Section::make('Kelulusan & penerbitan')
                ->columns(2)
                ->schema([
                    Forms\Components\DatePicker::make('graduation_date')->label('Tanggal kelulusan')->displayFormat('d F Y'),
                    Forms\Components\TextInput::make('issued_place')->label('Tempat penerbitan')->maxLength(120)->default(fn () => static::s()->issued_place),
                    Forms\Components\DatePicker::make('issued_date')->label('Tanggal penerbitan')->displayFormat('d F Y'),
                    Forms\Components\Select::make('status')->label('Status')->options(Transcript::STATUSES)->default('draft')->required(),
                    Forms\Components\Textarea::make('thesis_title')->label('Judul skripsi / paper')->rows(2)->maxLength(500)->columnSpanFull(),
                    Forms\Components\TextInput::make('predicate_override')->label('Predikat (opsional, timpa hitungan)')->maxLength(80)
                        ->helperText('Kosongkan agar predikat dihitung otomatis dari IPK & ambang di Pengaturan.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('courses'))
            ->columns([
                Tables\Columns\TextColumn::make('student_number')->label('NIM')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('student_name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('study_program')->label('Program studi')->searchable(),
                Tables\Columns\TextColumn::make('credits')->label('SKS')->state(fn (Transcript $r) => $r->totalCredits()),
                Tables\Columns\TextColumn::make('gpa')->label('IPK')->state(fn (Transcript $r) => number_format($r->gpa(), 2)),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($state) => Transcript::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => $state === 'final' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('Status')->options(Transcript::STATUSES),
            ])
            ->actions([
                Tables\Actions\Action::make('print')->label('Cetak')->icon('heroicon-o-printer')->color('primary')
                    ->visible(fn (Transcript $r) => auth()->user()->can('view', $r))
                    ->url(fn (Transcript $r) => route('transcripts.print', $r))->openUrlInNewTab(),
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateDescription('Tambahkan transkrip, isi mata kuliah pada halaman edit, lalu cetak.');
    }

    public static function getRelations(): array
    {
        return [CoursesRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranscripts::route('/'),
            'create' => Pages\CreateTranscript::route('/create'),
            'edit' => Pages\EditTranscript::route('/{record}/edit'),
        ];
    }
}
