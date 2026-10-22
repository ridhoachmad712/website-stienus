<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="referrer" content="no-referrer">
    <title>Verifikasi Ijazah — {{ $general->site_name }}</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f5f7fa;color:#172b42;font:16px/1.6 system-ui,sans-serif}a{color:#174c87}header{background:white;border-bottom:1px solid #dce3ea}header>div,main,footer{max-width:850px;margin:auto;padding:24px}header a{font-weight:700;text-decoration:none;color:inherit}.eyebrow{color:#52677e;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}h1{font-size:clamp(25px,5vw,34px);line-height:1.25;margin:12px 0}p{margin:12px 0}.lead{color:#52677e;max-width:650px}.card{background:white;border:1px solid #dce3ea;border-radius:12px;margin:24px 0;overflow:hidden}.status{padding:22px 26px;background:#edf7f0;color:#155733;border-bottom:1px solid #bcdcc8}.status.warning{background:#fff7e7;color:#79510c;border-color:#ecdbb2}.status.danger{background:#fff0ef;color:#962d27;border-color:#ecc6c2}.status h2{font-size:21px;margin:0}.status p{font-size:14px;margin:6px 0 0}.body{padding:24px 26px}dl{margin:0}.row{display:grid;grid-template-columns:210px 1fr;gap:16px;padding:13px 0;border-bottom:1px solid #edf0f3}.row:last-child{border:0}dt{color:#52677e;font-size:14px}dd{margin:0;font-weight:600;overflow-wrap:anywhere}.notice{border-left:4px solid #2a5684;padding:14px 18px;background:#eef3f9;font-size:14px;margin-top:24px}.actions{display:flex;gap:16px;flex-wrap:wrap;margin-top:22px}button,.button{display:inline-block;background:#214d79;color:white;border:0;border-radius:6px;padding:11px 18px;font:inherit;text-decoration:none;cursor:pointer}.secondary{background:white;color:#214d79;border:1px solid #bac9d8}label{display:block;font-weight:600;margin-bottom:8px}input{width:100%;border:1px solid #9baec0;border-radius:6px;padding:12px;font:inherit;margin-bottom:14px}input:focus,button:focus,a:focus{outline:3px solid #75a9db;outline-offset:3px}.error{color:#a52626;font-size:14px}footer{font-size:13px;color:#52677e;padding-top:8px}.small{font-size:13px;color:#52677e}.code{font-family:monospace;overflow-wrap:anywhere}@media(max-width:600px){header>div,main,footer{padding:18px}.body,.status{padding:20px}.row{grid-template-columns:1fr;gap:3px}.actions>*{width:100%;text-align:center}}
    </style>
</head>
<body>
    <header><div><a href="{{ route('home') }}">{{ $general->site_name }}</a><div class="small">Layanan verifikasi dokumen akademik</div></div></header>
    <main>
        <div class="eyebrow">Verifikasi ijazah</div>
        <h1>{{ $searched ? 'Hasil pemeriksaan ijazah' : 'Periksa catatan penerbitan ijazah' }}</h1>
        <p class="lead">Pastikan alamat situs sesuai dengan domain resmi kampus. Cocokkan data pada halaman ini dengan ijazah yang sedang Anda periksa.</p>
        @if ($details)
            <section class="card" aria-label="Hasil verifikasi">
                @if ($details['status'] === 'issued')
                    <div class="status" role="status"><h2>Terdaftar dan berlaku</h2><p>Catatan penerbitan ijazah ini telah diperiksa dan disetujui oleh kampus.</p></div>
                @elseif ($details['status'] === 'superseded')
                    <div class="status warning" role="status"><h2>Ijazah telah diganti / diperbaiki</h2><p>Catatan ini adalah versi lama. Mintalah dokumen pengganti dan periksa QR pada dokumen tersebut.</p></div>
                @else
                    <div class="status danger" role="status"><h2>Status penerbitan dicabut</h2><p>Catatan ini tidak lagi berlaku. Hubungi bagian akademik untuk konfirmasi.</p></div>
                @endif
                <div class="body"><dl>
                    @foreach (['student_name' => 'Nama pemilik ijazah', 'diploma_number' => 'Nomor ijazah', 'student_number' => 'NIM (disamarkan)', 'institution_name' => 'Perguruan tinggi', 'program_name' => 'Program studi', 'degree' => 'Jenjang / gelar', 'graduation_date' => 'Tanggal kelulusan', 'issue_date' => 'Tanggal penerbitan ijazah'] as $key => $label)
                        <div class="row"><dt>{{ $label }}</dt><dd>{{ $details[$key] }}</dd></div>
                    @endforeach
                </dl>
                <div class="notice"><strong>Cocokkan seluruh data.</strong> QR dapat disalin. Halaman ini mengonfirmasi catatan penerbitan di kampus; pemeriksaan lembar fisik dan identitas pemilik tetap diperlukan. Jika nama, nomor, atau data lain berbeda, hubungi bagian akademik.</div>
                <p class="small">Status ditampilkan saat halaman dibuka. Muat ulang untuk memeriksa status terbaru. Tangkapan layar bukan hasil verifikasi terkini.</p>
                </div>
            </section>
        @elseif ($searched)
            <section class="card"><div class="status warning" role="status"><h2>Catatan tidak ditemukan</h2><p>Kode ini tidak cocok dengan catatan yang telah diterbitkan dalam layanan kampus.</p></div><div class="body"><p>Hasil ini tidak otomatis menyatakan ijazah palsu. Coba pindai ulang QR atau hubungi bagian akademik, khususnya untuk ijazah yang belum didaftarkan pada layanan ini.</p></div></section>
        @else
            <section class="card"><div class="body">
                <p>Pindai QR pada ijazah dengan kamera ponsel. Jika pemindaian tidak berhasil, masukkan kode verifikasi yang tercetak pada label.</p>
                <form action="{{ route('diplomas.index') }}" method="get">
                    <label for="kode">Kode verifikasi</label>
                    <input id="kode" name="kode" value="{{ old('kode') }}" required maxlength="64" minlength="64" pattern="[a-f0-9]{64}" autocomplete="off" spellcheck="false" aria-describedby="code-help">
                    <div id="code-help" class="small">64 karakter angka dan huruf a–f. Gunakan kode pada label QR, bukan NIM.</div>
                    @error('kode')<p class="error">Kode harus berisi 64 karakter angka dan huruf a–f.</p>@enderror
                    <p><button type="submit">Periksa ijazah</button></p>
                </form>
            </div></section>
        @endif
        <div class="actions">
            <a class="button secondary" href="{{ route('contact') }}">Hubungi bagian akademik</a>
            <a class="button secondary" href="https://pisn.kemdiktisaintek.go.id/" target="_blank" rel="noopener noreferrer">Periksa nomor ijazah di PISN ↗</a>
            @if ($searched)<a href="{{ route('diplomas.index') }}">Periksa kode lain</a>@endif
        </div>
        <p class="small">Pemeriksaan PISN dilakukan secara terpisah pada layanan Kemdiktisaintek. Status pada halaman ini berasal dari catatan kampus.</p>
    </main>
    <footer>Data ditampilkan terbatas untuk pencocokan dokumen. Hindari menyebarkan tautan verifikasi dan data pemilik tanpa keperluan yang sah.</footer>
</body>
</html>
