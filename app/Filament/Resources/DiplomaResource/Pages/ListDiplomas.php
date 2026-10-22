<?php

namespace App\Filament\Resources\DiplomaResource\Pages;

use App\Filament\Resources\DiplomaResource;
use App\Models\Diploma;
use App\Services\DiplomaCsvImporter;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;

class ListDiplomas extends ListRecords
{
    protected static string $resource = DiplomaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('template')->label('Template CSV')->color('gray')
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
            Actions\Action::make('import')->label('Impor CSV')->icon('heroicon-o-arrow-up-tray')
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
