<?php

namespace App\Services;

use App\Models\Transcript;
use App\Settings\TranscriptSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use OpenSpout\Reader\XLSX\Reader;

class TranscriptXlsxImporter
{
    public const COLUMNS = ['category', 'kode', 'nama', 'sks', 'grade'];

    /** Tambahkan baris mata kuliah dari .xlsx ke sebuah transkrip (atomik). */
    public function import(string $path, Transcript $transcript): int
    {
        $settings = app(TranscriptSettings::class);
        $categories = $settings->category_defaults;
        $grades = array_map('strval', array_keys($settings->grade_points));

        $reader = new Reader();
        try {
            $reader->open($path);
        } catch (\Throwable $e) {
            throw ValidationException::withMessages(['file' => 'Berkas Excel tidak dapat dibaca. Pastikan format .xlsx.']);
        }

        try {
            return DB::transaction(function () use ($reader, $transcript, $categories, $grades) {
                $count = 0;
                $line = 0;
                $headerChecked = false;
                $order = (int) $transcript->courses()->max('order');

                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        $line++;
                        $values = array_map([$this, 'cell'], $row->toArray());

                        if (! $headerChecked) {
                            if (array_slice($values, 0, count(self::COLUMNS)) !== self::COLUMNS) {
                                throw ValidationException::withMessages(['file' => 'Baris pertama harus berisi judul kolom: '.implode(', ', self::COLUMNS).'.']);
                            }
                            $headerChecked = true;

                            continue;
                        }

                        if (count(array_filter($values, fn ($v) => $v !== '')) === 0) {
                            continue;
                        }
                        if (++$count > 500) {
                            throw ValidationException::withMessages(['file' => 'Maksimum 500 baris per impor. Tidak ada data yang ditambahkan.']);
                        }

                        $values = array_pad(array_slice($values, 0, count(self::COLUMNS)), count(self::COLUMNS), '');
                        $data = array_combine(self::COLUMNS, $values);
                        $data['grade'] = strtoupper($data['grade']);

                        $validator = Validator::make($data, [
                            'category' => 'nullable|string|max:255',
                            'kode' => 'nullable|string|max:50',
                            'nama' => 'required|string|max:255',
                            'sks' => 'required|integer|min:0|max:24',
                            'grade' => ['required', Rule::in($grades)],
                        ]);
                        if ($validator->fails()) {
                            throw ValidationException::withMessages(['file' => "Baris {$line}: ".implode(' ', $validator->errors()->all()).' Tidak ada data yang ditambahkan.']);
                        }

                        $idx = array_search($data['category'], $categories, true);
                        $transcript->courses()->create([
                            'category' => $data['category'] ?: null,
                            'category_order' => $idx === false ? 900 : $idx,
                            'kode' => $data['kode'] ?: null,
                            'nama' => $data['nama'],
                            'sks' => (int) $data['sks'],
                            'grade' => $data['grade'],
                            'order' => ++$order,
                        ]);
                    }
                    break;
                }

                if (! $headerChecked) {
                    throw ValidationException::withMessages(['file' => 'Berkas Excel kosong.']);
                }
                if (! $count) {
                    throw ValidationException::withMessages(['file' => 'Excel belum berisi data mata kuliah.']);
                }

                return $count;
            });
        } finally {
            $reader->close();
        }
    }

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
