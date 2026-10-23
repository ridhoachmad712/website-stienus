<x-filament-panels::page>
    <div id="ijz" wire:ignore>
        <p class="ijz-intro">Geser blok teks pada pratinjau, atau atur angka di panel kiri. Garis putus-putus menandai tengah halaman (148,5&nbsp;mm); geser mendekatinya untuk menempel otomatis. Data yang tampil hanya contoh. Klik <strong>Simpan tata letak</strong> agar posisi dipakai saat mencetak. Latar putih &amp; garis bantu tidak ikut tercetak.</p>

        <div class="ijz-grid">
            <div class="ijz-controls">
                <label class="ijz-field"><span>Bagian teks</span>
                    <select id="ijz-target"></select>
                </label>
                <div class="ijz-two">
                    <label class="ijz-field"><span>Posisi X (mm)</span><input id="ijz-x" type="number" min="0" max="297" step="0.1"></label>
                    <label class="ijz-field"><span>Posisi Y (mm)</span><input id="ijz-y" type="number" min="0" max="210" step="0.1"></label>
                    <label class="ijz-field"><span>Ukuran (pt)</span><input id="ijz-size" type="number" min="6" max="60" step="0.5"></label>
                    <label class="ijz-field"><span>Lebar maks (mm)</span><input id="ijz-width" type="number" min="20" max="297" step="0.5"></label>
                </div>
                <label class="ijz-field"><span>Titik acuan</span>
                    <select id="ijz-align"><option value="center">Tengah</option><option value="left">Kiri</option><option value="right">Kanan</option></select>
                </label>
                <p class="ijz-hint" id="ijz-center-hint">Garis tengah berada pada X = 148,5 mm.</p>
                <div class="ijz-actions">
                    <button type="button" class="ijz-btn primary" id="ijz-center">Tengahkan teks</button>
                    <button type="button" class="ijz-btn" id="ijz-reset-one">Kembalikan posisi ini</button>
                    <button type="button" class="ijz-btn" id="ijz-reset-all">Kembalikan semua</button>
                </div>
                <button type="button" class="ijz-btn save" id="ijz-save">Simpan tata letak</button>
            </div>

            <div class="ijz-stage">
                <div class="ijz-wrap"><div class="ijz-paper" id="ijz-paper"></div></div>
            </div>
        </div>
    </div>

    <style>
        #ijz{color:#1f2937}
        .dark #ijz{color:#e5e7eb}
        .ijz-intro{font-size:13px;line-height:1.5;margin:0 0 14px;color:#64748b}
        .ijz-grid{display:grid;grid-template-columns:300px minmax(0,1fr);gap:18px;align-items:start}
        @media(max-width:900px){.ijz-grid{grid-template-columns:1fr}}
        .ijz-controls{display:flex;flex-direction:column;gap:12px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px}
        .dark .ijz-controls{background:#1f2937;border-color:#374151}
        .ijz-field{display:block}
        .ijz-field>span{display:block;font-size:12px;font-weight:700;margin-bottom:5px;color:#475569}
        .dark .ijz-field>span{color:#cbd5e1}
        .ijz-field input,.ijz-field select{width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:8px 10px;font-size:13px;background:#fff;color:#0f172a}
        .dark .ijz-field input,.dark .ijz-field select{background:#111827;border-color:#374151;color:#e5e7eb}
        .ijz-two{display:grid;grid-template-columns:1fr 1fr;gap:10px}
        .ijz-hint{font-size:12px;line-height:1.45;color:#92610f;background:#fff8e8;border:1px solid #efd8a8;border-radius:8px;padding:8px 10px;margin:0}
        .ijz-actions{display:flex;flex-wrap:wrap;gap:8px}
        .ijz-btn{border:1px solid #cbd5e1;background:#fff;color:#1e293b;border-radius:8px;padding:8px 12px;font-size:12px;font-weight:700;cursor:pointer}
        .dark .ijz-btn{background:#111827;border-color:#374151;color:#e5e7eb}
        .ijz-btn.primary{background:#986615;border-color:#986615;color:#fff}
        .ijz-btn.save{background:#15803d;border-color:#15803d;color:#fff;padding:10px 12px;font-size:13px}
        .ijz-stage{background:#e7ebf0;border:1px solid #d5dbe4;border-radius:12px;padding:16px;overflow:auto}
        .ijz-wrap{position:relative;margin:auto}
        .ijz-paper{position:relative;width:297mm;height:210mm;background:#fff;box-shadow:0 8px 24px #2f405433;overflow:hidden;font-family:Playfair,Georgia,serif;color:#0e0e0e;transform-origin:top left}
        .ijz-item{position:absolute;text-align:center;white-space:nowrap;line-height:1.12;transform:translateX(-50%);cursor:grab;touch-action:none;z-index:1}
        .ijz-item.left{transform:none;text-align:left}
        .ijz-item.right{transform:translateX(-100%);text-align:right}
        .ijz-item.selected{outline:2px dashed #986615;outline-offset:4px;background:#fff8e980}
        .ijz-item:active{cursor:grabbing}
        .ijz-item.gold{color:#ad7717;font-weight:700}
        .ijz-item.strong{font-weight:700}
        .ijz-item.underlined{text-decoration:underline;text-underline-offset:5px}
        .ijz-qr{position:absolute;display:flex;align-items:center;justify-content:center;border:1.5px dashed #64748b;background:#f1f5f9cc;color:#475569;font:700 11px Arial,sans-serif;text-align:center;cursor:grab;touch-action:none;z-index:1}
        .ijz-qr.selected{outline:2px dashed #986615;outline-offset:4px}
        .ijz-guide{position:absolute;left:50%;top:0;height:100%;border-left:2px dashed #b87916;z-index:0;pointer-events:none}
        .ijz-paper.centered .ijz-guide{border-color:#27834b}
        @font-face{font-family:Playfair;src:url("{{ asset('fonts/ijazah/PlayfairDisplay-Regular.ttf') }}") format("truetype");font-weight:400;font-display:swap}
        @font-face{font-family:Playfair;src:url("{{ asset('fonts/ijazah/PlayfairDisplay-Bold.ttf') }}") format("truetype");font-weight:700;font-display:swap}
    </style>

    @script
    <script>
        const layout = @js($positions);
        const blocks = @js($blocks);
        const labels = @js($labels);
        const extras = @js($extras);
        const defaults = @js($defaults);
        const showQr = @js($showQr);
        const MM_TO_PX = 96 / 25.4;
        const keys = Object.keys(layout);
        let selectedKey = keys.includes('nama') ? 'nama' : keys[0];

        const paper = document.getElementById('ijz-paper');
        const target = document.getElementById('ijz-target');
        const inX = document.getElementById('ijz-x'), inY = document.getElementById('ijz-y');
        const inSize = document.getElementById('ijz-size'), inWidth = document.getElementById('ijz-width');
        const inAlign = document.getElementById('ijz-align'), centerHint = document.getElementById('ijz-center-hint');

        function clamp(v, min, max) { return Math.min(max, Math.max(min, v)); }

        // Build guide + nodes
        paper.innerHTML = '<div class="ijz-guide"></div>';
        for (const key of keys) {
            const pos = layout[key];
            let node;
            if (key === 'qr') {
                if (!showQr) continue;
                node = document.createElement('div');
                node.className = 'ijz-qr';
                node.textContent = 'QR';
            } else {
                node = document.createElement('div');
                node.textContent = blocks[key] ?? labels[key] ?? key;
            }
            node.dataset.key = key;
            paper.append(node);
        }

        for (const key of keys) {
            if (key === 'qr' && !showQr) continue;
            const opt = document.createElement('option');
            opt.value = key; opt.textContent = labels[key] ?? key;
            target.append(opt);
        }

        function fit(node, pos) {
            const maxPx = pos.width * MM_TO_PX;
            let pt = pos.size;
            node.style.fontSize = pt + 'pt';
            while (pt > 6 && node.scrollWidth > maxPx + 0.5) { pt -= 0.5; node.style.fontSize = pt + 'pt'; }
        }

        function render() {
            for (const node of paper.querySelectorAll('[data-key]')) {
                const key = node.dataset.key, pos = layout[key];
                node.style.left = pos.x + 'mm';
                node.style.top = pos.y + 'mm';
                if (key === 'qr') {
                    node.style.width = pos.size + 'mm';
                    node.style.height = pos.size + 'mm';
                    node.className = 'ijz-qr' + (selectedKey === key ? ' selected' : '');
                } else {
                    node.style.maxWidth = pos.width + 'mm';
                    const align = pos.align === 'center' ? '' : pos.align;
                    node.className = 'ijz-item ' + (extras[key] ?? '') + ' ' + align + (selectedKey === key ? ' selected' : '');
                    fit(node, pos);
                }
            }
            const c = layout[selectedKey];
            const centered = Math.abs(c.x - 148.5) < 0.05 && c.align === 'center';
            paper.classList.toggle('centered', centered);
            centerHint.textContent = centered
                ? 'Blok terpilih tepat di tengah halaman (X = 148,5 mm).'
                : 'Garis tengah berada pada X = 148,5 mm. Geser mendekatinya atau klik Tengahkan.';
        }

        function syncInputs() {
            const p = layout[selectedKey];
            target.value = selectedKey;
            inX.value = p.x.toFixed(1); inY.value = p.y.toFixed(1);
            inSize.value = p.size.toFixed(1); inWidth.value = p.width.toFixed(1);
            inAlign.value = p.align;
        }

        function select(key) { if (!layout[key]) return; selectedKey = key; syncInputs(); render(); }

        target.addEventListener('change', () => select(target.value));
        for (const [el, part, min, max] of [[inX,'x',0,297],[inY,'y',0,210],[inSize,'size',6,60],[inWidth,'width',20,297]]) {
            el.addEventListener('input', e => {
                const n = parseFloat(e.target.value); if (!isFinite(n)) return;
                layout[selectedKey][part] = clamp(n, min, max); render();
            });
        }
        inAlign.addEventListener('change', e => { layout[selectedKey].align = e.target.value; render(); });
        document.getElementById('ijz-center').addEventListener('click', () => { layout[selectedKey].x = 148.5; layout[selectedKey].align = 'center'; syncInputs(); render(); });
        document.getElementById('ijz-reset-one').addEventListener('click', () => { if (defaults[selectedKey]) { layout[selectedKey] = Object.assign({}, defaults[selectedKey]); syncInputs(); render(); } });
        document.getElementById('ijz-reset-all').addEventListener('click', () => {
            if (!confirm('Kembalikan seluruh posisi ke pengaturan awal?')) return;
            for (const k of keys) if (defaults[k]) layout[k] = Object.assign({}, defaults[k]);
            syncInputs(); render();
        });

        // Drag
        let drag = null;
        paper.addEventListener('pointerdown', e => {
            const item = e.target.closest('[data-key]'); if (!item) return;
            select(item.dataset.key);
            const r = paper.getBoundingClientRect();
            drag = { id: e.pointerId, key: selectedKey, scaleX: r.width / 297, scaleY: r.height / 210,
                     startX: e.clientX, startY: e.clientY, x: layout[selectedKey].x, y: layout[selectedKey].y };
            item.setPointerCapture(e.pointerId); e.preventDefault();
        });
        paper.addEventListener('pointermove', e => {
            if (!drag || drag.id !== e.pointerId) return;
            const p = layout[drag.key];
            let nextX = clamp(drag.x + (e.clientX - drag.startX) / drag.scaleX, 0, 297);
            if (Math.abs(nextX - 148.5) <= 3) { nextX = 148.5; p.align = 'center'; }
            p.x = Math.round(nextX * 10) / 10;
            p.y = Math.round(clamp(drag.y + (e.clientY - drag.startY) / drag.scaleY, 0, 210) * 10) / 10;
            syncInputs(); render();
        });
        function endDrag(e) { if (drag && drag.id === e.pointerId) drag = null; }
        paper.addEventListener('pointerup', endDrag);
        paper.addEventListener('pointercancel', endDrag);

        document.getElementById('ijz-save').addEventListener('click', () => { $wire.saveLayout(layout); });

        function scale() {
            const wrap = paper.parentElement, stage = wrap.parentElement;
            const avail = stage.clientWidth - 32, full = paper.offsetWidth;
            const s = Math.min(1, avail / full);
            paper.style.transform = 'scale(' + s + ')';
            wrap.style.width = (full * s) + 'px';
            wrap.style.height = (paper.offsetHeight * s) + 'px';
        }
        window.addEventListener('resize', scale);

        (document.fonts && document.fonts.ready ? document.fonts.ready : Promise.resolve()).then(() => {
            syncInputs(); render(); scale();
        });
    </script>
    @endscript
</x-filament-panels::page>
