<?php

namespace App\Services;

use App\Models\Diploma;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use OpenSpout\Reader\XLSX\Reader;

class DiplomaXlsxImporter
{
    /** Kolom sama dengan importer CSV agar satu format. */
    public const COLUMNS = DiplomaCsvImporter::COLUMNS;

    public function import(string $path, User $actor): int
    {
        Gate::forUser($actor)->authorize('create', Diploma::class);

        $reader = new Reader();
        try {
            $reader->open($path);
        } catch (\Throwable $e) {
            throw ValidationException::withMessages(['file' => 'Berkas Excel tidak dapat dibaca. Pastikan format .xlsx.']);
        }

        try {
            return DB::transaction(function () use ($reader, $actor) {
                $count = 0;
                $line = 0;
                $headerChecked = false;

                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        $line++;
                        $values = array_map([$this, 'cell'], $row->toArray());

                        if (! $headerChecked) {
                            $header = array_slice($values, 0, count(self::COLUMNS));
                            if ($header !== self::COLUMNS) {
                                throw ValidationException::withMessages(['file' => 'Baris pertama Excel harus berisi judul kolom yang sama persis dengan template.']);
                            }
                            $headerChecked = true;

                            continue;
                        }

                        // Lewati baris yang benar-benar kosong.
                        if (count(array_filter($values, fn ($v) => $v !== '')) === 0) {
                            continue;
                        }

                        if (count($values) > count(self::COLUMNS)
                            && count(array_filter(array_slice($values, count(self::COLUMNS)), fn ($v) => $v !== '')) > 0) {
                            throw ValidationException::withMessages(['file' => "Baris {$line}: jumlah kolom melebihi template. Tidak ada data yang diimpor."]);
                        }

                        if (++$count > 500) {
                            throw ValidationException::withMessages(['file' => 'Maksimum 500 data per impor. Tidak ada data yang diimpor.']);
                        }

                        $values = array_pad(array_slice($values, 0, count(self::COLUMNS)), count(self::COLUMNS), '');

                        try {
                            app(DiplomaService::class)->createDraft(array_combine(self::COLUMNS, $values), $actor);
                        } catch (ValidationException $error) {
                            throw ValidationException::withMessages(['file' => "Baris {$line}: ".implode(' ', $error->validator->errors()->all()).' Tidak ada data yang diimpor.']);
                        }
                    }
                    break; // hanya sheet pertama
                }

                if (! $headerChecked) {
                    throw ValidationException::withMessages(['file' => 'Berkas Excel kosong.']);
                }
                if (! $count) {
                    throw ValidationException::withMessages(['file' => 'Excel belum berisi data.']);
                }

                return $count;
            });
        } finally {
            $reader->close();
        }
    }

    /** Normalkan nilai sel menjadi string. */
    private function cell(mixed $value): string
    {
        if ($value === null) {
            return '';
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }
        if (is_float($value)) {
            return floor($value) === $value ? number_format($value, 0, '', '') : (string) $value;
        }

        return trim((string) $value);
    }
}
