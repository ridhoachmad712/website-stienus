<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExporter
{
    /**
     * Stream a CSV download from an Eloquent collection.
     *
     * @param  Collection<int, \Illuminate\Database\Eloquent\Model>  $rows
     * @param  array<string, string>  $columns  Map of attribute => column header.
     */
    public static function download(Collection $rows, array $columns, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($rows, $columns): void {
            $handle = fopen('php://output', 'wb');

            // UTF-8 BOM so Excel renders accents correctly.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, array_values($columns));

            foreach ($rows as $row) {
                $line = [];
                foreach (array_keys($columns) as $attribute) {
                    $value = data_get($row, $attribute);
                    $line[] = $value instanceof \DateTimeInterface
                        ? $value->format('Y-m-d H:i')
                        : (string) $value;
                }
                fputcsv($handle, $line);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
