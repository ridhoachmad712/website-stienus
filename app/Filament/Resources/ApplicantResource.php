<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicantResource\Pages;
use App\Models\Applicant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicantResource extends Resource
{
    protected static ?string $model = Applicant::class;

    protected static ?string $recordTitleAttribute = 'name';

    /** @return array<int, string> */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone', 'program'];
    }

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Penerimaan Mahasiswa';

    protected static ?string $navigationLabel = 'Pendaftar';

    protected static ?string $modelLabel = 'Pendaftar';

    protected static ?string $pluralModelLabel = 'Pendaftar';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) (static::getModel()::where('status', 'pending')->count() ?: '');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pendaftar')
                    ->schema([
                        Forms\Components\TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(255),
                        Forms\Components\TextInput::make('email')->label('Email')->email()->required()->maxLength(255),
                        Forms\Components\TextInput::make('phone')->label('No. Telepon/WA')->required()->maxLength(255),
                        Forms\Components\Select::make('program')->label('Program Studi Pilihan')
                            ->options(['Akuntansi' => 'S1 Akuntansi', 'Manajemen' => 'S1 Manajemen'])
                            ->required()->native(false),
                        Forms\Components\TextInput::make('origin_school')->label('Asal Sekolah')->maxLength(255),
                        Forms\Components\Select::make('status')->label('Status')
                            ->options([
                                'pending' => 'Menunggu',
                                'contacted' => 'Sudah Dihubungi',
                                'accepted' => 'Diterima',
                                'rejected' => 'Ditolak',
                            ])->required()->native(false),
                        Forms\Components\Textarea::make('address')->label('Alamat')->rows(2)->columnSpanFull(),
                        Forms\Components\Textarea::make('message')->label('Pesan/Catatan')->rows(3)->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('program')->label('Prodi')->badge()->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('Telepon')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'contacted' => 'info',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'contacted' => 'Dihubungi',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Tgl Daftar')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('Status')->options([
                    'pending' => 'Menunggu',
                    'contacted' => 'Sudah Dihubungi',
                    'accepted' => 'Diterima',
                    'rejected' => 'Ditolak',
                ]),
                Tables\Filters\SelectFilter::make('program')->label('Prodi')->options([
                    'Akuntansi' => 'S1 Akuntansi',
                    'Manajemen' => 'S1 Manajemen',
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(fn () => \App\Support\CsvExporter::download(
                        Applicant::query()->latest()->get(),
                        ['name' => 'Nama', 'email' => 'Email', 'phone' => 'Telepon', 'program' => 'Prodi', 'origin_school' => 'Asal Sekolah', 'status' => 'Status', 'created_at' => 'Tgl Daftar'],
                        'pendaftar-'.now()->format('Ymd-His').'.csv',
                    )),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplicants::route('/'),
            'create' => Pages\CreateApplicant::route('/create'),
            'edit' => Pages\EditApplicant::route('/{record}/edit'),
        ];
    }
}
