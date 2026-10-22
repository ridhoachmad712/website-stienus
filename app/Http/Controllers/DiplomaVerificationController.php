<?php

namespace App\Http\Controllers;

use App\Models\Diploma;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DiplomaVerificationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('kode')) {
            $data = $request->validate(['kode' => ['required', 'string', 'regex:/\A[a-f0-9]{64}\z/']]);

            return redirect()->route('diplomas.verify', ['token' => $data['kode']]);
        }

        return $this->page(null, false);
    }

    public function show(string $token): Response
    {
        $diploma = preg_match('/\A[a-f0-9]{64}\z/', $token)
            ? Diploma::where('public_token', $token)->whereIn('status', ['issued', 'revoked', 'superseded'])->first()
            : null;

        return $this->page($diploma, true);
    }

    private function page(?Diploma $diploma, bool $searched): Response
    {
        // Pass an explicit public projection; internal references and notes never reach the view.
        $details = $diploma ? [
            'status' => $diploma->status,
            'student_name' => $diploma->student_name,
            'student_number' => str_repeat('•', max(0, mb_strlen($diploma->student_number) - 4)).mb_substr($diploma->student_number, -4),
            'diploma_number' => $diploma->diploma_number,
            'institution_name' => $diploma->institution_name,
            'program_name' => $diploma->program_name,
            'degree' => $diploma->degree,
            'graduation_date' => $diploma->graduation_date->translatedFormat('d F Y'),
            'issue_date' => $diploma->issue_date->translatedFormat('d F Y'),
        ] : null;

        return response()->view('diplomas.verify', [
            'details' => $details, 'searched' => $searched,
            'general' => app(GeneralSettings::class),
        ], $searched && ! $diploma ? 404 : 200)->withHeaders([
            'Cache-Control' => 'no-store, private, max-age=0',
            'X-Robots-Tag' => 'noindex, nofollow, noarchive',
            'Referrer-Policy' => 'no-referrer',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
