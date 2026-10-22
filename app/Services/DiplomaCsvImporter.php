<?php

namespace App\Services;

use App\Models\Diploma;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class DiplomaCsvImporter
{
    public const COLUMNS = [
        'student_name', 'student_number', 'diploma_number', 'institution_name',
        'program_name', 'degree', 'graduation_date', 'issue_date', 'source_reference',
    ];

    public function import(string $path, User $actor): int
    {
        Gate::forUser($actor)->authorize('create', Diploma::class);
        $handle = fopen($path, 'rb');
        if (! $handle) {
            throw ValidationException::withMessages(['file' => 'Berkas tidak dapat dibaca.']);
        }
        try {
            $header = fgetcsv($handle, 0, ',', '"', '');
            if ($header) {
                $header[0] = ltrim($header[0], "\xEF\xBB\xBF");
            }
            if ($header !== self::COLUMNS) {
                throw ValidationException::withMessages(['file' => 'Kolom CSV harus sama dengan template; gunakan pemisah koma dan UTF-8.']);
            }

            return DB::transaction(function () use ($handle, $actor) {
                $count = 0;
                $line = 1;
                while (($values = fgetcsv($handle, 0, ',', '"', '')) !== false) {
                    $line++;
                    if ($values === [null]) {
                        continue;
                    }
                    if (++$count > 500 || count($values) !== count(self::COLUMNS)) {
                        throw ValidationException::withMessages(['file' => "Baris {$line}: maksimum 500 data dan jumlah kolom harus sesuai template. Tidak ada data yang diimpor."]);
                    }
                    try {
                        app(DiplomaService::class)->createDraft(array_combine(self::COLUMNS, $values), $actor);
                    } catch (ValidationException $error) {
                        throw ValidationException::withMessages(['file' => "Baris {$line}: ".implode(' ', $error->validator->errors()->all()).' Tidak ada data yang diimpor.']);
                    }
                }
                if (! $count) {
                    throw ValidationException::withMessages(['file' => 'CSV belum berisi data.']);
                }

                return $count;
            });
        } finally {
            fclose($handle);
        }
    }
}
