<?php

namespace App\Http\Controllers;

use App\Models\Transcript;
use App\Settings\GeneralSettings;
use App\Settings\TranscriptSettings;
use App\Support\GradeScale;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TranscriptPrintController extends Controller
{
    public function show(Transcript $transcript, TranscriptSettings $settings, GeneralSettings $general)
    {
        Gate::authorize('view', $transcript);
        $transcript->load('courses');

        $missing = [];
        if (blank($transcript->student_name) || blank($transcript->student_number)) {
            $missing[] = 'Nama dan NIM mahasiswa';
        }
        if ($transcript->courses->isEmpty()) {
            $missing[] = 'Minimal satu mata kuliah (tambah manual atau impor Excel)';
        }

        [$left, $right] = $missing ? [[], []] : $this->buildColumns($transcript);

        $logo = rescue(fn () => $general->logo ? Storage::disk('public')->url($general->logo) : null, null, report: false);
        $photo = $transcript->photo_path ? Storage::disk('public')->url($transcript->photo_path) : null;

        return response()->view('transcripts.print', [
            'transcript' => $transcript,
            'settings' => $settings,
            'left' => $left,
            'right' => $right,
            'totalCredits' => $transcript->totalCredits(),
            'totalQuality' => $transcript->totalQuality(),
            'gpa' => $transcript->gpa(),
            'predicate' => $transcript->predicate(),
            'logo' => $logo,
            'photo' => $photo,
            'missing' => $missing,
        ])->withHeaders([
            'Cache-Control' => 'no-store, private',
            'X-Robots-Tag' => 'noindex, nofollow',
            'Referrer-Policy' => 'no-referrer',
        ]);
    }

    /**
     * Bangun urutan baris (header kategori + mata kuliah bernomor per kategori),
     * lalu bagi seimbang ke dua kolom; ulangi header kategori di kolom kanan bila
     * grup terpotong.
     *
     * @return array{0: array<int,array<string,mixed>>, 1: array<int,array<string,mixed>>}
     */
    private function buildColumns(Transcript $transcript): array
    {
        $rows = [];
        $currentCategory = null;
        $no = 0;
        foreach ($transcript->courses as $course) {
            $category = (string) ($course->category ?? '');
            if ($category !== $currentCategory) {
                $rows[] = ['type' => 'header', 'label' => $category, 'continued' => false];
                $currentCategory = $category;
                $no = 0;
            }
            $kxn = $course->qualityPoints();
            $rows[] = [
                'type' => 'course',
                'no' => ++$no,
                'kode' => $course->kode,
                'nama' => $course->nama,
                'sks' => $course->sks,
                'grade' => $course->grade,
                'kxn' => rtrim(rtrim(number_format($kxn, 2, '.', ''), '0'), '.'),
                'group' => $category,
            ];
        }

        $target = (int) ceil(count($rows) / 2);
        $left = array_slice($rows, 0, $target);
        $right = array_slice($rows, $target);

        // Jangan tinggalkan header sebagai baris terakhir kolom kiri.
        if ($left && end($left)['type'] === 'header') {
            $right = array_merge([array_pop($left)], $right);
        }
        // Jika kolom kanan diawali mata kuliah, ulangi header kategorinya.
        if ($right && $right[0]['type'] === 'course') {
            array_unshift($right, ['type' => 'header', 'label' => $right[0]['group'], 'continued' => true]);
        }

        return [$left, $right];
    }
}
