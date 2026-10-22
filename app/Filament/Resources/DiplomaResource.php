<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiplomaResource\Pages;
use App\Filament\Resources\DiplomaResource\RelationManagers\EventsRelationManager;
use App\Models\Diploma;
use App\Models\Program;
use App\Services\DiplomaService;
use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DiplomaResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Diploma::class;

    protected static ?string $slug = 'ijazah';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Verifikasi Ijazah';

    protected static ?string $modelLabel = 'Ijazah';

    protected static ?string $pluralModelLabel = 'Ijazah';

    protected static ?int $navigationSort = 8;

    public static function getPermissionPrefixes(): array
    {
        return ['view', 'view_any', 'create', 'update', 'issue', 'revoke', 'download'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data yang tampil pada verifikasi')
                ->description('Isi sesuai arsip ijazah. Setelah diterbitkan, data ini terkunci. NIM ditampilkan sebagian pada halaman publik.')
                ->schema([
                    Forms\Components\TextInput::make('student_name')->label('Nama lengkap')->required()->maxLength(255),
                    Forms\Components\TextInput::make('student_number')->label('NIM')->required()->maxLength(50),
                    Forms\Components\TextInput::make('diploma_number')->label('Nomor ijazah / nomor ijazah nasional')->required()->maxLength(100)->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('institution_name')->label('Nama perguruan tinggi saat penerbitan')->required()->maxLength(255)
                        ->default(fn () => app(GeneralSettings::class)->site_full_name),
                    Forms\Components\TextInput::make('program_name')->label('Program studi saat kelulusan')->required()->maxLength(255)
                        ->datalist(fn () => Program::query()->orderBy('name')->pluck('name')->all()),
                    Forms\Components\TextInput::make('degree')->label('Jenjang / gelar')->placeholder('S1 — Sarjana Akuntansi (S.Ak.)')->required()->maxLength(100),
                    Forms\Components\DatePicker::make('graduation_date')->label('Tanggal kelulusan / yudisium')->required()->maxDate(now()),
                    Forms\Components\DatePicker::make('issue_date')->label('Tanggal penerbitan ijazah')->required()->afterOrEqual('graduation_date')->maxDate(now()),
                ])->columns(2),
            Forms\Components\Section::make('Dasar verifikasi — hanya untuk petugas')->schema([
                Forms\Components\TextInput::make('source_reference')->label('Referensi arsip / SK yudisium')->required()->maxLength(255)
                    ->helperText('Cantumkan nomor atau lokasi arsip yang dapat diperiksa. Tidak tampil di halaman publik.'),
                Forms\Components\Textarea::make('internal_notes')->label('Catatan internal')->maxLength(5000),
                Forms\Components\Select::make('replaces_id')->label('Menggantikan catatan ijazah (opsional)')->searchable()
                    ->getSearchResultsUsing(fn (string $search) => Diploma::query()->where('status', 'issued')
                        ->where(fn ($q) => $q->where('diploma_number', 'like', "%{$search}%")->orWhere('student_number', 'like', "%{$search}%"))
                        ->limit(20)->get()->mapWithKeys(fn ($r) => [$r->id => $r->diploma_number.' — '.$r->student_name])->all())
                    ->getOptionLabelUsing(fn ($value) => Diploma::find($value)?->diploma_number)
                    ->helperText('Cari nomor ijazah atau NIM. Saat pengganti diterbitkan, catatan lama berubah menjadi Diganti.')->live(),
                Forms\Components\Textarea::make('correction_reason')->label('Alasan penggantian / perbaikan')->minLength(10)->maxLength(2000)
                    ->required(fn (Forms\Get $get) => filled($get('replaces_id'))),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('student_name')->label('Nama')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('student_number')->label('NIM')->searchable(),
            Tables\Columns\TextColumn::make('diploma_number')->label('Nomor ijazah')->searchable(),
            Tables\Columns\TextColumn::make('program_name')->label('Program studi')->searchable(),
            Tables\Columns\TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn ($state) => Diploma::STATUSES[$state] ?? $state)
                ->color(fn ($state) => match ($state) {
                    'issued' => 'success', 'revoked' => 'danger', 'superseded' => 'warning', default => 'gray'
                }),
            Tables\Columns\TextColumn::make('issued_at')->label('QR diterbitkan')->dateTime('d M Y H:i')->placeholder('Belum diterbitkan')->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')->label('Status')->options(Diploma::STATUSES),
        ])->actions([
            Tables\Actions\ViewAction::make()->label('Detail'),
            Tables\Actions\EditAction::make()->visible(fn (Diploma $record) => $record->status === 'draft' && auth()->user()->can('update', $record)),
            Tables\Actions\Action::make('issue')->label('Terbitkan QR')->icon('heroicon-o-qr-code')->color('success')
                ->visible(fn (Diploma $record) => $record->status === 'draft' && auth()->user()->can('issue', $record))
                ->modalHeading('Periksa dan terbitkan QR ijazah')
                ->modalDescription(fn (Diploma $record) => $record->student_name.' — '.$record->diploma_number.'. Data akan dikunci setelah diterbitkan.')
                ->form([
                    Forms\Components\Placeholder::make('target')->label('Domain tujuan QR')->content(fn () => config('diplomas.verification_base_url')),
                    Forms\Components\Checkbox::make('confirmed')->label('Saya telah mencocokkan data dengan arsip akademik dan menyetujui data minimum ini untuk verifikasi publik.')->accepted()->required(),
                ])
                ->action(function (Diploma $record, array $data): void {
                    app(DiplomaService::class)->issue($record, auth()->user(), (bool) ($data['confirmed'] ?? false));
                    Notification::make()->title('QR diterbitkan. Data ijazah telah dikunci.')->success()->send();
                }),
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('public')->label('Lihat verifikasi publik')->icon('heroicon-o-arrow-top-right-on-square')
                    ->visible(fn (Diploma $record) => $record->status !== 'draft' && auth()->user()->can('view', $record))
                    ->url(fn (Diploma $record) => route('diplomas.verify', ['token' => $record->public_token]))->openUrlInNewTab(),
                Tables\Actions\Action::make('qr')->label('Unduh QR (SVG)')->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (Diploma $record) => $record->status === 'issued' && auth()->user()->can('download', $record))
                    ->url(fn (Diploma $record) => route('diplomas.qr', $record)),
                Tables\Actions\Action::make('label')->label('Cetak label QR')->icon('heroicon-o-printer')
                    ->visible(fn (Diploma $record) => $record->status === 'issued' && auth()->user()->can('download', $record))
                    ->url(fn (Diploma $record) => route('diplomas.label', $record))->openUrlInNewTab(),
                Tables\Actions\Action::make('revoke')->label('Cabut status penerbitan')->icon('heroicon-o-no-symbol')->color('danger')
                    ->visible(fn (Diploma $record) => $record->status === 'issued' && auth()->user()->can('revoke', $record))
                    ->modalHeading('Cabut status penerbitan ijazah')->modalDescription('QR lama akan menampilkan status Dicabut. Tindakan dan alasan akan dicatat pada riwayat.')
                    ->form([Forms\Components\Textarea::make('reason')->label('Dasar / alasan pencabutan (internal)')->required()->minLength(10)->maxLength(2000)])
                    ->action(function (Diploma $record, array $data): void {
                        app(DiplomaService::class)->revoke($record, auth()->user(), $data['reason']);
                        Notification::make()->title('Status penerbitan dicabut.')->success()->send();
                    }),
            ]),
        ])->defaultSort('id', 'desc')->emptyStateDescription('Tambahkan data ijazah atau impor CSV, periksa arsipnya, lalu terbitkan QR.');
    }

    public static function getRelations(): array
    {
        return [EventsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiplomas::route('/'),
            'create' => Pages\CreateDiploma::route('/create'),
            'view' => Pages\ViewDiploma::route('/{record}'),
            'edit' => Pages\EditDiploma::route('/{record}/edit'),
        ];
    }
}
