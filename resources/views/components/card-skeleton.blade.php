@props(['count' => 6])

{{-- Placeholder kartu saat data sedang dimuat (filter/pencarian Livewire). --}}
@for ($i = 0; $i < (int) $count; $i++)
    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100">
        <div class="aspect-[4/3] w-full animate-pulse bg-slate-200"></div>
        <div class="space-y-3 p-5">
            <div class="h-4 w-3/4 animate-pulse rounded bg-slate-200"></div>
            <div class="h-3 w-full animate-pulse rounded bg-slate-100"></div>
            <div class="h-3 w-5/6 animate-pulse rounded bg-slate-100"></div>
        </div>
    </div>
@endfor
