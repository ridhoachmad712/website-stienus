<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Cetak ijazah — {{ $diploma->student_name }}</title>
<style>
  @font-face{font-family:Playfair;src:url("{{ asset('fonts/ijazah/PlayfairDisplay-Regular.ttf') }}") format("truetype");font-weight:400;font-display:swap}
  @font-face{font-family:Playfair;src:url("{{ asset('fonts/ijazah/PlayfairDisplay-Bold.ttf') }}") format("truetype");font-weight:700;font-display:swap}
  *{box-sizing:border-box}
  body{margin:0;background:#e7ebf0;font-family:Arial,Helvetica,sans-serif;color:#19243a}
  .toolbar{max-width:1000px;margin:0 auto;padding:18px 20px;display:flex;flex-wrap:wrap;gap:12px;align-items:center}
  .toolbar h1{font-size:16px;margin:0;flex:1 1 220px}
  .btn{border:1px solid #cbd4df;background:#fff;color:#28384e;border-radius:7px;padding:9px 14px;font:700 13px Arial;cursor:pointer;text-decoration:none}
  .btn.primary{background:#986615;border-color:#986615;color:#fff}
  .note{max-width:1000px;margin:0 auto 6px;padding:0 20px;font-size:12px;line-height:1.5;color:#647083}
  .warn{color:#a12626;font-weight:700}
  .missing{max-width:720px;margin:30px auto;background:#fff;border:1px solid #efd8a8;border-radius:10px;padding:22px 24px}
  .missing h2{margin:0 0 10px;font-size:17px}
  .missing ul{margin:0 0 14px;padding-left:20px;line-height:1.6}
  .sheet{padding:20px;display:flex;justify-content:center;overflow:auto}
  .paper-wrap{position:relative}
  .paper{position:relative;width:297mm;height:210mm;background:#fff;box-shadow:0 9px 30px #2f405433;overflow:hidden;font-family:Playfair,Georgia,serif;color:#0e0e0e;transform-origin:top left}
  .item{position:absolute;text-align:center;white-space:nowrap;line-height:1.12;transform:translateX(-50%)}
  .item.left{transform:none;text-align:left}
  .item.right{transform:translateX(-100%);text-align:right}
  .gold{color:#ad7717;font-weight:700}
  .strong{font-weight:700}
  .underlined{text-decoration:underline;text-underline-offset:5px}
  .qr{position:absolute}
  .qr img{display:block;width:100%;height:100%}
  @media print{
    @page{size:A4 landscape;margin:0}
    html,body{width:297mm;height:210mm;margin:0;background:#fff}
    .toolbar,.note{display:none!important}
    .sheet{padding:0!important;display:block!important;overflow:visible!important}
    .paper-wrap{height:auto!important}
    .paper{transform:none!important;box-shadow:none!important;width:297mm!important;height:210mm!important;print-color-adjust:exact;-webkit-print-color-adjust:exact}
  }
</style>
</head>
<body>
@if ($missing)
  <div class="toolbar"><h1>Belum dapat dicetak</h1><a class="btn" href="{{ \App\Filament\Resources\DiplomaResource::getUrl('edit', ['record' => $diploma]) }}">Lengkapi data</a></div>
  <div class="missing">
    <h2>Lengkapi dulu sebelum mencetak</h2>
    <p>Data berikut masih kosong untuk ijazah atas nama <strong>{{ $diploma->student_name }}</strong>:</p>
    <ul>@foreach ($missing as $item)<li>{{ $item }}</li>@endforeach</ul>
    <p>Isi tempat/tanggal lahir pada data ijazah, dan akreditasi program studi pada menu <strong>Template Ijazah</strong>, lalu buka kembali halaman cetak.</p>
  </div>
@else
  <div class="toolbar">
    <h1>Cetak ijazah — {{ $diploma->student_name }}</h1>
    <button class="btn primary" onclick="window.print()">Cetak / Simpan PDF</button>
    <a class="btn" href="{{ \App\Filament\Resources\DiplomaResource::getUrl() }}">Kembali</a>
  </div>
  <p class="note">
    Gunakan kertas A4 lanskap, skala 100%, margin tanpa batas. Latar putih polos — cetak di atas blanko fisik. Lakukan satu cetak uji pada kertas biasa untuk memeriksa posisi.
    @unless ($isIssued)<br><span class="warn">PRATINJAU — ijazah belum diterbitkan, jadi kode QR belum tampil. Terbitkan QR dahulu untuk cetak resmi.</span>@endunless
  </p>
  <div class="sheet">
    <div class="paper-wrap">
      <div class="paper" id="paper">
        @foreach ($blocks as $key => $text)
          @php $pos = $layout[$key] ?? null; @endphp
          @continue(! $pos || $text === '')
          @php
            $align = $pos['align'] ?? 'center';
            $cls = trim(($extras[$key] ?? '').' '.($align === 'center' ? '' : $align));
          @endphp
          <div class="item {{ $cls }}"
               data-maxw="{{ $pos['width'] }}"
               style="left:{{ $pos['x'] }}mm;top:{{ $pos['y'] }}mm;font-size:{{ $pos['size'] }}pt;max-width:{{ $pos['width'] }}mm">{{ $text }}</div>
        @endforeach
        @if ($qrData)
          @php $q = $layout['qr'] ?? ['x' => 268, 'y' => 176, 'size' => 24]; @endphp
          <div class="qr" style="left:{{ $q['x'] }}mm;top:{{ $q['y'] }}mm;width:{{ $q['size'] }}mm;height:{{ $q['size'] }}mm">
            <img src="{{ $qrData }}" alt="QR verifikasi ijazah">
          </div>
        @endif
      </div>
    </div>
  </div>
  <script>
    const paper=document.getElementById('paper');
    const MM_TO_PX=96/25.4;
    function fit(){
      for(const node of paper.querySelectorAll('.item')){
        const maxPx=parseFloat(node.dataset.maxw)*MM_TO_PX;
        let pt=parseFloat(node.style.fontSize);
        node.style.fontSize=pt+'pt';
        while(pt>6 && node.scrollWidth>maxPx+0.5){pt-=0.5;node.style.fontSize=pt+'pt'}
      }
    }
    function scale(){
      const wrap=paper.parentElement, avail=wrap.parentElement.clientWidth-40, full=paper.offsetWidth;
      const s=Math.min(1, avail/full);
      paper.style.transform='scale('+s+')';
      wrap.style.width=(full*s)+'px';
      wrap.style.height=(paper.offsetHeight*s)+'px';
    }
    (document.fonts&&document.fonts.ready?document.fonts.ready:Promise.resolve()).then(()=>{fit();scale()});
    window.addEventListener('resize',scale);
  </script>
@endif
</body>
</html>
