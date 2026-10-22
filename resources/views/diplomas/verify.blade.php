<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="referrer" content="no-referrer">
    <meta name="theme-color" content="#0f2a4a">
    <title>Verifikasi Ijazah — {{ $general->site_name }}</title>
    @php $logo = $general->logo ? \Illuminate\Support\Facades\Storage::disk('public')->url($general->logo) : null; @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --ink:#132033;--muted:#5b6b82;--line:#e4e9f0;--brand:#0f2a4a;--brand-2:#1d4e86;
            --ok:#0f8a4d;--ok-bg:#eafaf0;--ok-line:#b6e6c9;--warn:#9a6708;--warn-bg:#fff7e6;--warn-line:#f0dcae;
            --bad:#b23b34;--bad-bg:#fdf0ef;--bad-line:#f2c9c4;--card:#ffffff;
        }
        *{box-sizing:border-box}
        html{-webkit-text-size-adjust:100%}
        body{margin:0;color:var(--ink);background:#eef2f7;
            font-family:'Plus Jakarta Sans',system-ui,-apple-system,Segoe UI,sans-serif;font-size:16px;line-height:1.6;
            background-image:radial-gradient(1200px 400px at 50% -120px,rgba(29,78,134,.18),transparent 70%);}
        a{color:var(--brand-2)}
        .wrap{max-width:760px;margin:0 auto;padding:0 20px}
        .topbar{background:var(--brand);background-image:linear-gradient(120deg,#0f2a4a,#1d4e86);color:#fff}
        .topbar .wrap{display:flex;align-items:center;gap:14px;padding:18px 20px}
        .topbar a{display:block;line-height:0}
        .topbar img{height:34px;width:auto;display:block;background:#fff;padding:8px 12px;border-radius:10px}
        .brandmark{height:46px;width:46px;border-radius:12px;background:rgba(255,255,255,.14);
            display:grid;place-items:center;font-weight:800;font-size:19px;letter-spacing:.5px}
        .brandtext strong{display:block;font-weight:700;font-size:15px;line-height:1.25}
        .brandtext span{font-size:12.5px;color:#c6d7ee}
        main{padding:34px 0 48px}
        .eyebrow{display:inline-flex;align-items:center;gap:7px;color:var(--brand-2);background:#e7eefb;
            font-size:12px;font-weight:700;letter-spacing:.09em;text-transform:uppercase;padding:6px 12px;border-radius:999px}
        h1{font-size:clamp(24px,4.6vw,33px);line-height:1.2;margin:16px 0 8px;letter-spacing:-.01em}
        .lead{color:var(--muted);max-width:620px;margin:0}
        .card{background:var(--card);border:1px solid var(--line);border-radius:18px;margin:26px 0;overflow:hidden;
            box-shadow:0 18px 44px -28px rgba(19,32,51,.45)}
        .banner{display:flex;align-items:center;gap:16px;padding:24px 26px;border-bottom:1px solid var(--line)}
        .banner .seal{flex:none;width:54px;height:54px;border-radius:50%;display:grid;place-items:center}
        .banner h2{margin:0;font-size:20px;letter-spacing:-.01em}
        .banner p{margin:4px 0 0;font-size:14px;color:inherit;opacity:.92}
        .banner svg{width:30px;height:30px}
        .ok{background:var(--ok-bg);color:#0b5c35;border-bottom-color:var(--ok-line)}
        .ok .seal{background:var(--ok);color:#fff;box-shadow:0 0 0 6px rgba(15,138,77,.14)}
        .warn{background:var(--warn-bg);color:#7a5207;border-bottom-color:var(--warn-line)}
        .warn .seal{background:var(--warn);color:#fff;box-shadow:0 0 0 6px rgba(154,103,8,.14)}
        .bad{background:var(--bad-bg);color:#8f2f29;border-bottom-color:var(--bad-line)}
        .bad .seal{background:var(--bad);color:#fff;box-shadow:0 0 0 6px rgba(178,59,52,.14)}
        .issuer{display:flex;align-items:center;gap:12px;padding:16px 26px;background:#f8fafc;border-bottom:1px solid var(--line);
            font-size:13px;color:var(--muted)}
        .issuer img{height:30px;width:auto}
        .issuer .brandmark{height:32px;width:32px;border-radius:8px;background:var(--brand);font-size:13px}
        .body{padding:10px 26px 24px}
        dl{margin:0}
        .row{display:grid;grid-template-columns:220px 1fr;gap:16px;padding:15px 0;border-bottom:1px solid #eef1f5}
        .row:last-child{border:0}
        dt{color:var(--muted);font-size:13.5px}
        dd{margin:0;font-weight:600;overflow-wrap:anywhere}
        dd.big{font-size:18px;font-weight:700}
        .notice{display:flex;gap:12px;border:1px solid #d9e6f5;background:#f1f6fd;border-radius:12px;padding:15px 17px;font-size:14px;color:#2d4867;margin:22px 0 0}
        .notice svg{flex:none;width:20px;height:20px;color:var(--brand-2);margin-top:2px}
        .fineprint{font-size:12.5px;color:var(--muted);margin:14px 2px 0}
        .actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px}
        .button{display:inline-flex;align-items:center;gap:8px;background:var(--brand);color:#fff;border:0;border-radius:10px;
            padding:12px 20px;font:inherit;font-weight:600;text-decoration:none;cursor:pointer;transition:filter .15s}
        .button:hover{filter:brightness(1.08)}
        .button.secondary{background:#fff;color:var(--brand);border:1px solid #c7d4e3}
        label{display:block;font-weight:600;margin-bottom:8px}
        input{width:100%;border:1px solid #b7c5d6;border-radius:10px;padding:13px 14px;font:inherit;margin-bottom:12px;background:#fff}
        input:focus,.button:focus,a:focus{outline:3px solid #8fb8e6;outline-offset:2px}
        .error{color:var(--bad);font-size:14px;margin:2px 0 10px}
        .small{font-size:13px;color:var(--muted)}
        .link-row{margin-top:8px}
        footer{border-top:1px solid var(--line);background:#fff}
        footer .wrap{padding:22px 20px;font-size:13px;color:var(--muted)}
        @media(max-width:600px){
            main{padding:24px 0 36px}
            .banner{padding:20px}.body{padding:6px 20px 20px}.issuer{padding:14px 20px}
            .row{grid-template-columns:1fr;gap:3px}
            .actions .button{width:100%;justify-content:center}
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="wrap">
            @if ($logo)
                <a href="{{ route('home') }}"><img src="{{ $logo }}" alt="{{ $general->site_name }}"></a>
            @else
                <div class="brandmark">{{ strtoupper(mb_substr($general->site_name, 0, 2)) }}</div>
            @endif
            <div class="brandtext">
                <strong>{{ $general->site_full_name ?: $general->site_name }}</strong>
                <span>Layanan Verifikasi Dokumen Akademik</span>
            </div>
        </div>
    </header>

    <main>
        <div class="wrap">
            <span class="eyebrow">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 4 5v6c0 5 3.5 8 8 11 4.5-3 8-6 8-11V5z"/></svg>
                Verifikasi Ijazah
            </span>
            <h1>{{ $searched ? 'Hasil pemeriksaan ijazah' : 'Periksa keaslian penerbitan ijazah' }}</h1>
            <p class="lead">Pastikan alamat situs ini adalah domain resmi kampus, lalu cocokkan seluruh data di bawah dengan lembar ijazah yang Anda pegang.</p>

            @if ($details)
                <section class="card" aria-label="Hasil verifikasi">
                    @if ($details['status'] === 'issued')
                        <div class="banner ok" role="status">
                            <span class="seal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></span>
                            <div><h2>Terdaftar dan berlaku</h2><p>Catatan penerbitan ijazah ini telah diperiksa dan disetujui oleh kampus.</p></div>
                        </div>
                    @elseif ($details['status'] === 'superseded')
                        <div class="banner warn" role="status">
                            <span class="seal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg></span>
                            <div><h2>Ijazah telah diganti / diperbaiki</h2><p>Catatan ini adalah versi lama. Mintalah dokumen pengganti dan periksa QR pada dokumen tersebut.</p></div>
                        </div>
                    @else
                        <div class="banner bad" role="status">
                            <span class="seal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span>
                            <div><h2>Status penerbitan dicabut</h2><p>Catatan ini tidak lagi berlaku. Hubungi bagian akademik untuk konfirmasi.</p></div>
                        </div>
                    @endif

                    <div class="issuer">
                        @if ($logo)
                            <img src="{{ $logo }}" alt="">
                        @else
                            <div class="brandmark" style="color:#fff">{{ strtoupper(mb_substr($general->site_name, 0, 2)) }}</div>
                        @endif
                        <div>Diterbitkan dan diverifikasi oleh<br><strong style="color:var(--ink)">{{ $details['institution_name'] }}</strong></div>
                    </div>

                    <div class="body">
                        <dl>
                            @foreach (['student_name' => 'Nama pemilik ijazah', 'diploma_number' => 'Nomor ijazah', 'student_number' => 'NIM (disamarkan)', 'program_name' => 'Program studi', 'degree' => 'Jenjang / gelar', 'graduation_date' => 'Tanggal kelulusan', 'issue_date' => 'Tanggal penerbitan ijazah'] as $key => $label)
                                <div class="row"><dt>{{ $label }}</dt><dd class="{{ $key === 'student_name' ? 'big' : '' }}">{{ $details[$key] }}</dd></div>
                            @endforeach
                        </dl>
                        <div class="notice">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-5M12 8h.01"/></svg>
                            <div><strong>Cocokkan seluruh data.</strong> QR dapat disalin. Halaman ini mengonfirmasi catatan penerbitan di kampus; pemeriksaan lembar fisik dan identitas pemilik tetap diperlukan. Jika nama, nomor, atau data lain berbeda, hubungi bagian akademik.</div>
                        </div>
                        <p class="fineprint">Status ditampilkan saat halaman dibuka. Muat ulang untuk memeriksa status terbaru. Tangkapan layar bukan hasil verifikasi terkini.</p>
                    </div>
                </section>
            @elseif ($searched)
                <section class="card">
                    <div class="banner warn" role="status">
                        <span class="seal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg></span>
                        <div><h2>Catatan tidak ditemukan</h2><p>Kode ini tidak cocok dengan catatan yang telah diterbitkan dalam layanan kampus.</p></div>
                    </div>
                    <div class="body"><p>Hasil ini tidak otomatis menyatakan ijazah palsu. Coba pindai ulang QR atau hubungi bagian akademik, khususnya untuk ijazah yang belum didaftarkan pada layanan ini.</p></div>
                </section>
            @else
                <section class="card">
                    <div class="body" style="padding-top:24px">
                        <p>Pindai QR pada ijazah dengan kamera ponsel. Jika pemindaian tidak berhasil, masukkan kode verifikasi yang tercetak pada label.</p>
                        <form action="{{ route('diplomas.index') }}" method="get">
                            <label for="kode">Kode verifikasi</label>
                            <input id="kode" name="kode" value="{{ old('kode') }}" required maxlength="64" minlength="64" pattern="[a-f0-9]{64}" autocomplete="off" spellcheck="false" aria-describedby="code-help">
                            <div id="code-help" class="small">64 karakter angka dan huruf a–f. Gunakan kode pada label QR, bukan NIM.</div>
                            @error('kode')<p class="error">Kode harus berisi 64 karakter angka dan huruf a–f.</p>@enderror
                            <p><button type="submit" class="button">Periksa ijazah</button></p>
                        </form>
                    </div>
                </section>
            @endif

            <div class="actions">
                <a class="button secondary" href="{{ route('contact') }}">Hubungi bagian akademik</a>
                <a class="button secondary" href="https://pisn.kemdiktisaintek.go.id/" target="_blank" rel="noopener noreferrer">Periksa nomor ijazah di PISN ↗</a>
            </div>
            @if ($searched)<p class="link-row small"><a href="{{ route('diplomas.index') }}">← Periksa kode lain</a></p>@endif
            <p class="fineprint">Pemeriksaan PISN dilakukan secara terpisah pada layanan Kemdiktisaintek. Status pada halaman ini berasal dari catatan kampus.</p>
        </div>
    </main>

    <footer>
        <div class="wrap">Data ditampilkan terbatas untuk pencocokan dokumen. Hindari menyebarkan tautan verifikasi dan data pemilik tanpa keperluan yang sah.</div>
    </footer>
</body>
</html>
