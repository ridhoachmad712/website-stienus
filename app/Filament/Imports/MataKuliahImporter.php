<?php

namespace App\Filament\Imports;

use App\Models\MataKuliah;
use App\Models\Program;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Collection;

class MataKuliahImporter extends Importer
{
    protected static ?string $model = MataKuliah::class;

    /** @var Collection<int, Program>|null */
    private static ?Collection $programCache = null;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('program_id')
                ->label('Program Studi')
                ->requiredMapping()
                ->rules(['required'])
                ->resolveUsing(function (string $state): ?int {
                    static::$programCache ??= Program::query()->get(['id', 'name']);

                    $program = static::$programCache->first(
                        fn (Program $p) => strtolower(trim($p->name)) === strtolower(trim($state))
                    );

                    return $program?->id;
                })
                ->example('Akuntansi'),

            ImportColumn::make('semester')
                ->label('Semester')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'min:1', 'max:8'])
                ->example('1'),

            ImportColumn::make('kode')
                ->label('Kode MK')
                ->rules(['nullable', 'string', 'max:20'])
                ->example('AKT101'),

            ImportColumn::make('nama')
                ->label('Nama Mata Kuliah')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Pengantar Akuntansi'),

            ImportColumn::make('sks')
                ->label('SKS')
                ->numeric()
                ->rules(['required', 'integer', 'min:1', 'max:6'])
                ->example('3'),

            ImportColumn::make('jenis')
                ->label('Jenis')
                ->rules(['required', 'in:Wajib,Pilihan'])
                ->example('Wajib'),
        ];
    }

    public function resolveRecord(): ?MataKuliah
    {
        if (! $this->data['program_id'] || ! $this->data['semester'] || ! $this->data['nama']) {
            return null;
        }

        return MataKuliah::firstOrNew([
            'program_id' => $this->data['program_id'],
            'semester'   => $this->data['semester'],
            'nama'       => $this->data['nama'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import selesai. ' . number_format($import->successful_rows) . ' mata kuliah berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal.';
        }

        return $body;
    }
}
