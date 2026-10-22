<?php

namespace App\Services;

use App\Models\Diploma;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class DiplomaQr
{
    public function svg(Diploma $diploma): string
    {
        abort_unless($diploma->status === 'issued' && $diploma->verification_url, 409, 'QR hanya tersedia untuk ijazah yang berlaku.');
        $writer = new Writer(new ImageRenderer(new RendererStyle(400, 4), new SvgImageBackEnd));

        return $writer->writeString($diploma->verification_url, 'UTF-8', ErrorCorrectionLevel::M());
    }
}
