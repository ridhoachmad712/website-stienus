@php
    use App\Support\Terbilang;
    $place = $transcript->issued_place ?: $settings->issued_place;
    $issued = $transcript->issued_date?->translatedFormat('j F Y');
    $identity = [
        'FAKULTAS' => $transcript->faculty,
        'JURUSAN' => $transcript->department,
        'PROGRAM STUDI' => $transcript->study_program,
        'PROGRAM PENDIDIKAN' => $transcript->education_program,
        'KONSENTRASI' => $transcript->concentration,
    ];
    $qtyInt = (int) round($totalQuality);
    $qtyLabel = abs($totalQuality - $qtyInt) < 0.01 ? (string) $qtyInt : rtrim(rtrim(number_format($totalQuality, 2, '.', ''), '0'), '.');
@endphp
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Transkrip — {{ $transcript->student_name }}</title>
<style>
  /* Caladea: font bebas-lisensi (OFL) yang metrik-kompatibel dengan Cambria,
     agar transkrip tampil seperti Cambria di perangkat tanpa Cambria. */
  @font-face{font-family:Caladea;src:url("{{ asset('fonts/transcript/Caladea-Regular.ttf') }}") format("truetype");font-weight:400;font-display:swap}
  @font-face{font-family:Caladea;src:url("{{ asset('fonts/transcript/Caladea-Bold.ttf') }}") format("truetype");font-weight:700;font-display:swap}
  *{box-sizing:border-box}
  body{margin:0;background:#e7ebf0;font-family:Arial,Helvetica,sans-serif;color:#15233a}
  .toolbar{max-width:210mm;margin:0 auto;padding:16px 10mm;display:flex;flex-wrap:wrap;gap:10px;align-items:center}
  .toolbar h1{font-size:15px;margin:0;flex:1 1 220px}
  .btn{border:1px solid #cbd4df;background:#fff;color:#28384e;border-radius:7px;padding:9px 14px;font:700 13px Arial;cursor:pointer;text-decoration:none}
  .btn.primary{background:#15803d;border-color:#15803d;color:#fff}
  .missing{max-width:680px;margin:30px auto;background:#fff;border:1px solid #efd8a8;border-radius:10px;padding:22px 24px}
  .missing ul{line-height:1.6}
  .page{width:210mm;min-height:297mm;margin:0 auto;background:#fff;padding:12mm 10mm;box-shadow:0 8px 30px #2f405433;
        font-family:Cambria,Caladea,"Times New Roman",Georgia,serif;color:#111}
  .head{display:flex;align-items:center;gap:12px;border-bottom:3px solid #1b2a4a;padding-bottom:6px}
  .head img{height:74px;width:auto}
  .head .ht{flex:1;text-align:center}
  .head .inst{font-size:20px;font-weight:bold;text-transform:uppercase;line-height:1.1;letter-spacing:.3px}
  .head .addr{font-size:10px;margin-top:4px}
  .serial{font-size:11px;margin:8px 0 2px}
  .title{text-align:center;font-size:19px;font-weight:bold;letter-spacing:1px;margin:4px 0 8px}
  .idblock{font-size:11.5px;line-height:1.35}
  .idrow{display:flex}
  .idrow .lbl{width:150px;flex:none}
  .idrow .sep{width:10px;flex:none}
  .accred{text-align:center;font-size:10.5px;margin:7px 0;line-height:1.3}
  .idname{margin-top:6px}
  .idname .lbl{width:150px;display:inline-block}
  .cols{display:flex;gap:6px;margin-top:8px;align-items:flex-start}
  .cols .col{flex:1;min-width:0}
  table.mk{width:100%;border-collapse:collapse;font-size:9px;table-layout:fixed}
  table.mk th,table.mk td{border:1px solid #333;padding:1.5px 3px;line-height:1.15;vertical-align:top}
  table.mk th{background:#f0f0f0;text-align:center;font-size:8.5px}
  table.mk .no{width:7%;text-align:center}
  table.mk .kode{width:16%}
  table.mk .nama{width:49%}
  table.mk .k{width:9%;text-align:center}
  table.mk .n{width:8%;text-align:center}
  table.mk .kxn{width:11%;text-align:center}
  table.mk tr.grp td{font-weight:bold;background:#fafafa}
  table.mk tr.sum td{font-weight:bold;text-align:center}
  table.mk tr.sum td.left{text-align:left}
  .foot{font-size:11px;margin-top:10px;line-height:1.45}
  .foot .lbl{display:inline-block;width:185px}
  .foot .pred{font-weight:bold}
  .sign{display:flex;margin-top:14px;font-size:11px;text-align:center}
  .sign .c{flex:1}
  .sign .c.mid{flex:0 0 30mm}
  .sign .photo{width:26mm;height:34mm;object-fit:cover;border:1px solid #999;margin:4px auto 0;display:block}
  .sign .photoph{width:26mm;height:34mm;border:1px dashed #bbb;margin:4px auto 0}
  .sign .gap{height:56px}
  .sign .nm{font-weight:bold;text-decoration:underline;text-underline-offset:2px}
  .sign .id{font-size:10px}
  @media print{
    @page{size:A4 portrait;margin:0}
    html,body{background:#fff;margin:0}
    .toolbar{display:none!important}
    .page{box-shadow:none!important;margin:0!important;width:210mm!important;min-height:0!important;
          print-color-adjust:exact;-webkit-print-color-adjust:exact}
  }
</style>
</head>
<body>
@if ($missing)
  <div class="toolbar"><h1>Belum dapat dicetak</h1><a class="btn" href="{{ \App\Filament\Resources\TranscriptResource::getUrl('edit', ['record' => $transcript]) }}">Lengkapi data</a></div>
  <div class="missing"><h2>Lengkapi dulu sebelum mencetak</h2><ul>@foreach ($missing as $m)<li>{{ $m }}</li>@endforeach</ul></div>
@else
  <div class="toolbar">
    <h1>Transkrip — {{ $transcript->student_name }}</h1>
    <button class="btn primary" onclick="window.print()">Cetak / Simpan PDF</button>
    <a class="btn" href="{{ \App\Filament\Resources\TranscriptResource::getUrl() }}">Kembali</a>
  </div>
  <div class="page">
    <div class="head">
      @if ($logo)<img src="{{ $logo }}" alt="Logo">@endif
      <div class="ht">
        <div class="inst">{{ $settings->institution_name }}</div>
        @if ($settings->institution_address)<div class="addr">{{ $settings->institution_address }}</div>@endif
      </div>
      @if ($logo)<div style="width:74px"></div>@endif
    </div>

    @if ($transcript->serial_number)<div class="serial">Nomor Seri Transkrip : {{ $transcript->serial_number }}</div>@endif
    <div class="title">TRANSKRIP AKADEMIK</div>

    <div class="idblock">
      @foreach ($identity as $lbl => $val)
        @continue(blank($val))
        <div class="idrow"><span class="lbl">{{ $lbl }}</span><span class="sep">:</span><span>{{ $val }}</span></div>
      @endforeach
      @if ($settings->accreditation_text)<div class="accred">{{ $settings->accreditation_text }}</div>@endif
      <div class="idname" style="margin-top:6px">
        <div><span class="lbl">NAMA</span>: <strong>{{ $transcript->student_name }}</strong></div>
        @if ($transcript->birth_place || $transcript->birth_date)
          <div><span class="lbl">TEMPAT/TANGGAL LAHIR</span>: {{ collect([$transcript->birth_place, $transcript->birth_date?->translatedFormat('j F Y')])->filter()->implode(', ') }}</div>
        @endif
        <div><span class="lbl">NIM (NPM)</span>: {{ $transcript->student_number }}</div>
      </div>
    </div>

    <div class="cols">
      @foreach ([$left, $right] as $i => $column)
        <div class="col">
          <table class="mk">
            <thead><tr><th class="no">No</th><th class="kode">Kode MK</th><th class="nama">Nama Matakuliah</th><th class="k">Kredit (K)</th><th class="n">Nilai (N)</th><th class="kxn">KxN</th></tr></thead>
            <tbody>
              @foreach ($column as $row)
                @if ($row['type'] === 'header')
                  <tr class="grp"><td colspan="6">{{ $row['label'] }}@if ($row['continued']) <span style="font-weight:normal">(lanjutan)</span>@endif</td></tr>
                @else
                  <tr><td class="no">{{ $row['no'] }}</td><td class="kode">{{ $row['kode'] }}</td><td class="nama">{{ $row['nama'] }}</td><td class="k">{{ $row['sks'] }}</td><td class="n">{{ $row['grade'] }}</td><td class="kxn">{{ $row['kxn'] }}</td></tr>
                @endif
              @endforeach
              @if ($i === 1)
                <tr class="sum"><td class="left" colspan="3">Jumlah</td><td class="k">{{ $totalCredits }}</td><td></td><td class="kxn">{{ $qtyLabel }}</td></tr>
              @endif
            </tbody>
          </table>
        </div>
      @endforeach
    </div>

    <div class="foot">
      @if ($transcript->graduation_date)<div><span class="lbl">Tanggal Kelulusan</span>: {{ $transcript->graduation_date->translatedFormat('j F Y') }}</div>@endif
      <div><span class="lbl">Jumlah Kredit Kumulatif</span>: {{ $totalCredits }} ({{ Terbilang::make($totalCredits) }})</div>
      <div><span class="lbl">Nilai Mutu</span>: {{ $qtyLabel }} ({{ Terbilang::make($qtyInt) }})</div>
      <div><span class="lbl">Indeks Prestasi Kumulatif</span>: {{ number_format($gpa, 2) }}</div>
      @if ($predicate)<div><span class="lbl">Predikat</span>: <span class="pred">{{ $predicate }}</span></div>@endif
      @if ($transcript->thesis_title)<div><span class="lbl">Judul Skripsi / Paper</span>: {{ $transcript->thesis_title }}</div>@endif
    </div>

    <div class="sign">
      <div class="c">
        <div>{{ $settings->sign_left_label }}</div>
        <div class="gap"></div>
        <div class="nm">{{ $settings->sign_left_name }}</div>
        <div class="id">{{ $settings->sign_left_id }}</div>
      </div>
      <div class="c mid">
        @if ($settings->show_photo)
          @if ($photo)<img class="photo" src="{{ $photo }}" alt="Pas foto">@else<div class="photoph"></div>@endif
        @endif
      </div>
      <div class="c">
        <div>{{ $place }}@if ($issued), {{ $issued }}@endif</div>
        <div>{{ $settings->sign_right_label }}</div>
        <div class="gap" style="height:34px"></div>
        <div class="nm">{{ $settings->sign_right_name }}</div>
        <div class="id">{{ $settings->sign_right_id }}</div>
      </div>
    </div>
  </div>
@endif
</body>
</html>
