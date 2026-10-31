<?php

namespace App\Http\Controllers;

use App\Models\Diploma;
use App\Services\DiplomaQr;
use App\Services\IjazahComposer;
use App\Settings\IjazahSettings;
use Illuminate\Support\Facades\Gate;

class DiplomaPrintController extends Controller
{
    public function show(Diploma $diploma, IjazahSettings $settings, IjazahComposer $composer, DiplomaQr $qr)
    {
        Gate::authorize('view', $diploma);

        $composer = new IjazahComposer($settings);

        // Kelengkapan yang wajib sebelum ijazah layak dicetak.
        $profile = $composer->profileFor($diploma->program_name);
        $missing = [];
        if (blank($diploma->birth_place)) {
            $missing[] = 'Tempat lahir';
        }
        if (blank($diploma->birth_date)) {
            $missing[] = 'Tanggal lahir';
        }
        if (blank($profile)) {
            $missing[] = 'Program studi "'.$diploma->program_name.'" belum terdaftar di Daftar Program Studi (Template Ijazah)';
        } elseif (blank($profile['akreditasi'] ?? '')) {
            $missing[] = 'Akreditasi program studi '.($profile['name'] ?? $diploma->program_name).' (atur di Template Ijazah)';
        }

        // QR hanya untuk ijazah yang sudah diterbitkan dan jika diaktifkan.
        $qrData = null;
        if ($settings->show_qr && $diploma->status === 'issued') {
            $qrData = 'data:image/svg+xml;base64,'.base64_encode($qr->svg($diploma));
        }

        return response()->view('diplomas.print', [
            'diploma' => $diploma,
            'settings' => $settings,
            'layout' => $settings->layout,
            'blocks' => $missing ? [] : $composer->blocksForDiploma($diploma),
            'extras' => IjazahComposer::EXTRAS,
            'qrData' => $qrData,
            'missing' => $missing,
            'isIssued' => $diploma->status === 'issued',
        ])->withHeaders([
            'Cache-Control' => 'no-store, private',
            'X-Robots-Tag' => 'noindex, nofollow',
            'Referrer-Policy' => 'no-referrer',
        ]);
    }
}
