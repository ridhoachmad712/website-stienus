<?php

namespace App\Http\Controllers;

use App\Models\Diploma;
use App\Services\DiplomaQr;
use Illuminate\Support\Facades\Gate;

class DiplomaQrController extends Controller
{
    public function download(Diploma $diploma, DiplomaQr $qr)
    {
        Gate::authorize('download', $diploma);

        return response($qr->svg($diploma))->withHeaders([
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="qr-ijazah-'.$diploma->id.'.svg"',
            'Cache-Control' => 'no-store, private', 'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function label(Diploma $diploma, DiplomaQr $qr)
    {
        Gate::authorize('download', $diploma);

        return response()->view('diplomas.label', [
            'diploma' => $diploma,
            'qrData' => 'data:image/svg+xml;base64,'.base64_encode($qr->svg($diploma)),
        ])->withHeaders(['Cache-Control' => 'no-store, private', 'X-Robots-Tag' => 'noindex, nofollow', 'Referrer-Policy' => 'no-referrer']);
    }
}
