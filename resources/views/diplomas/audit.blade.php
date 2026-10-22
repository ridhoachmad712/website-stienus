<div style="overflow-x:auto">
    <table style="width:100%;font-size:13px;border-collapse:collapse">
        <thead><tr><th style="padding:8px;text-align:left">Data</th><th style="padding:8px;text-align:left">Sebelum</th><th style="padding:8px;text-align:left">Sesudah</th></tr></thead>
        <tbody>
        @foreach (array_unique(array_merge(array_keys($event->before ?? []), array_keys($event->after ?? []))) as $key)
            @if (($event->before[$key] ?? null) !== ($event->after[$key] ?? null))
                <tr><td style="padding:8px;border-top:1px solid #ddd">{{ $key }}</td><td style="padding:8px;border-top:1px solid #ddd;overflow-wrap:anywhere">{{ $event->before[$key] ?? '—' }}</td><td style="padding:8px;border-top:1px solid #ddd;overflow-wrap:anywhere">{{ $event->after[$key] ?? '—' }}</td></tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
