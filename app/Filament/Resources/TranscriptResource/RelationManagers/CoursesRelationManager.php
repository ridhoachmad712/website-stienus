<?php

namespace App\Filament\Resources\TranscriptResource\RelationManagers;

use App\Models\MataKuliah;
use App\Services\TranscriptXlsxImporter;
use App\Settings\TranscriptSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    protected static ?string $title = 'Mata Kuliah & Nilai';

    protected static ?string $icon = 'heroicon-o-list-bullet';

    protected function settings(): TranscriptSettings
    {
        return app(TranscriptSettings::class);
    }

    /** category_order mengikuti urutan kategori di Pengaturan agar grup rapi saat cetak. */
    public function applyCategoryOrder(array $data): array
    {
        $list = $this->settings()->category_defaults;
        $idx = array_search($data['category'] ?? null, $list, true);
        $data['category_order'] = $idx === false ? 900 : $idx;

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('catalog')->label('Ambil dari katalog mata kuliah (opsional)')
                ->searchable()->dehydrated(false)
                ->options(function () {
                    $pid = $this->getOwnerRecord()->program_id;

                    return MataKuliah::query()->where('is_active', true)
                        ->when($pid, fn ($q) => $q->where('program_id', $pid))
                        ->orderBy('semester')->orderBy('nama')->get()
                        ->mapWithKeys(fn ($m) => [$m->id => trim(($m->kode ? $m->kode.' — ' : '').$m->nama)])->all();
                })
                ->afterStateUpdated(function ($state, Forms\Set $set) {
                    $m = $state ? MataKuliah::find($state) : null;
                    if ($m) {
                        $set('kode', $m->kode);
                        $set('nama', $m->nama);
                        $set('sks', $m->sks);
                    }
                })
                ->helperText('Memilih akan mengisi kode, nama, dan SKS. Bisa juga diisi manual.')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('category')->label('Kelompok mata kuliah')
                ->datalist(fn () => $this->settings()->category_defaults)->maxLength(255)->columnSpanFull(),
            Forms\Components\TextInput::make('kode')->label('Kode MK')->maxLength(50),
            Forms\Components\TextInput::make('nama')->label('Nama mata kuliah')->required()->maxLength(255)->columnSpan(2),
            Forms\Components\TextInput::make('sks')->label('Kredit (SKS)')->numeric()->minValue(0)->maxValue(24)->default(2)->required(),
            Forms\Components\Select::make('grade')->label('Nilai')->options(fn () => array_combine(array_keys($this->settings()->grade_points), array_keys($this->settings()->grade_points)))->required(),
        ])->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->defaultSort('category_order')
            ->reorderable('order')
            ->columns([
                Tables\Columns\TextColumn::make('category')->label('Kelompok')->wrap()->toggleable(),
                Tables\Columns\TextColumn::make('kode')->label('Kode'),
                Tables\Columns\TextColumn::make('nama')->label('Mata kuliah')->wrap()->searchable(),
                Tables\Columns\TextColumn::make('sks')->label('K')->alignCenter(),
                Tables\Columns\TextColumn::make('grade')->label('N')->alignCenter(),
                Tables\Columns\TextColumn::make('kxn')->label('KxN')->alignCenter()
                    ->state(fn ($record) => rtrim(rtrim(number_format($record->qualityPoints(), 2, '.', ''), '0'), '.')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah mata kuliah')
                    ->mutateFormDataUsing(fn (array $data) => $this->applyCategoryOrder($data)),
                Tables\Actions\Action::make('templateExcel')->label('Template Excel')->icon('heroicon-o-arrow-down-tray')->color('gray')
                    ->action(function () {
                        return response()->streamDownload(function () {
                            $writer = new Writer();
                            $writer->openToFile('php://output');
                            $writer->addRow(Row::fromValues(TranscriptXlsxImporter::COLUMNS));
                            $writer->close();
                        }, 'template-transkrip.xlsx', [
                            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ]);
                    }),
                Tables\Actions\Action::make('importExcel')->label('Impor Excel')->icon('heroicon-o-arrow-up-tray')
                    ->modalDescription('Unggah .xlsx sesuai Template (kolom: category, kode, nama, sks, grade). Baris ditambahkan ke transkrip ini; maksimal 500. Jika ada baris salah, seluruh impor dibatalkan.')
                    ->form([Forms\Components\FileUpload::make('file')->label('Berkas Excel (.xlsx)')->required()->storeFiles(false)->maxSize(2048)
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])])
                    ->action(function (array $data): void {
                        $count = app(TranscriptXlsxImporter::class)->import($data['file']->getRealPath(), $this->getOwnerRecord());
                        Notification::make()->title("{$count} mata kuliah ditambahkan dari Excel.")->success()->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(fn (array $data) => $this->applyCategoryOrder($data)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('Belum ada mata kuliah')
            ->emptyStateDescription('Tambah manual atau impor dari Excel.');
    }
}
