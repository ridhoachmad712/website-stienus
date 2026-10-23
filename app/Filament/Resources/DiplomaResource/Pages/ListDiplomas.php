<?php

namespace App\Filament\Resources\DiplomaResource\Pages;

use App\Filament\Resources\DiplomaResource;
use App\Filament\Widgets\DiplomaStatsOverview;
use App\Models\Diploma;
use App\Services\DiplomaCsvImporter;
use App\Services\DiplomaXlsxImporter;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class ListDiplomas extends ListRecords
{
    protected static string $resource = DiplomaResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            DiplomaStatsOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('templateExcel')->label('Template Excel (.xlsx)')->icon('heroicon-o-table-cells')
                    ->visible(fn () => auth()->user()->can('create', Diploma::class))
                    ->action(function () {
                        Gate::authorize('create', Diploma::class);

                        return response()->streamDownload(function () {
                            $writer = new Writer();
                            $writer->openToFile('php://output');
                            $writer->addRow(Row::fromValues(DiplomaXlsxImporter::COLUMNS));
                            $writer->close();
                        }, 'template-ijazah.xlsx', [
                            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ]);
                    }),
                Actions\Action::make('templateCsv')->label('Template CSV')->icon('heroicon-o-document-text')
                    ->visible(fn () => auth()->user()->can('create', Diploma::class))
                    ->action(function () {
                        Gate::authorize('create', Diploma::class);

                        return response()->streamDownload(function () {
                            $file = fopen('php://output', 'wb');
                            fwrite($file, "\xEF\xBB\xBF");
                            fputcsv($file, DiplomaCsvImporter::COLUMNS, ',', '"', '');
                            fclose($file);
                        }, 'template-ijazah.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
                    }),
            ])->label('Unduh template')->icon('heroicon-o-arrow-down-tray')->button()->color('gray'),

            Actions\Action::make('importExcel')->label('Impor Excel')->icon('heroicon-o-arrow-up-tray')
                ->visible(fn () => auth()->user()->can('create', Diploma::class))
                ->modalDescription('Unggah file .xlsx sesuai Template Excel. Maksimal 500 baris. Tempat/tanggal lahir boleh kosong tetapi wajib diisi sebelum ijazah dicetak. Agar nol di awal (nomor/NIM) tidak hilang, format kolom tersebut sebagai Teks. Semua baris disimpan sebagai draf; jika ada kesalahan, seluruh impor dibatalkan.')
                ->form([Forms\Components\FileUpload::make('file')->label('Berkas Excel (.xlsx)')->required()->storeFiles(false)->maxSize(2048)
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])])
                ->action(function (array $data): void {
                    $count = app(DiplomaXlsxImporter::class)->import($data['file']->getRealPath(), auth()->user());
                    Notification::make()->title("{$count} draf ijazah diimpor dari Excel. Periksa sebelum menerbitkan.")->success()->send();
                }),

            Actions\Action::make('importCsv')->label('Impor CSV')->icon('heroicon-o-arrow-up-tray')->color('gray')
                ->visible(fn () => auth()->user()->can('create', Diploma::class))
                ->modalDescription('Maksimal 500 baris. Tanggal: YYYY-MM-DD. Nomor dan NIM berupa teks agar nol di awal tetap tersimpan. Semua baris disimpan sebagai draf; jika ada kesalahan, seluruh impor dibatalkan.')
                ->form([Forms\Components\FileUpload::make('file')->label('CSV sesuai template')->required()->storeFiles(false)->maxSize(1024)
                    ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'])])
                ->action(function (array $data): void {
                    $count = app(DiplomaCsvImporter::class)->import($data['file']->getRealPath(), auth()->user());
                    Notification::make()->title("{$count} draf ijazah diimpor. Periksa sebelum menerbitkan.")->success()->send();
                }),

            Actions\CreateAction::make()->label('Tambah ijazah'),
        ];
    }
}
