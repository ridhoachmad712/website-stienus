@forelse ($blocks ?? [] as $block)
    @php $d = $block['data'] ?? []; @endphp
    @switch($block['type'])
        @case('rich_text')
            <article class="prose prose-slate max-w-none prose-headings:font-bold prose-a:text-brand-600 prose-img:rounded-2xl">{!! $d['content'] ?? '' !!}</article>
            @break

        @case('heading')
            @php $tag = in_array($d['level'] ?? 'h2', ['h2','h3','h4']) ? $d['level'] : 'h2'; $sizes = ['h2'=>'text-3xl','h3'=>'text-2xl','h4'=>'text-xl']; @endphp
            <{{ $tag }} class="{{ $sizes[$tag] }} font-bold text-slate-900 {{ ($d['align'] ?? 'left') === 'center' ? 'text-center' : '' }}">{{ $d['text'] ?? '' }}</{{ $tag }}>
            @break

        @case('image')
            <figure>
                <img src="{{ Storage::disk('public')->url($d['image']) }}" alt="{{ $d['caption'] ?? '' }}" loading="lazy" class="w-full rounded-2xl shadow-sm">
                @if (! empty($d['caption']))<figcaption class="mt-2 text-center text-sm text-slate-400">{{ $d['caption'] }}</figcaption>@endif
            </figure>
            @break

        @case('image_text')
            <div class="grid items-center gap-6 sm:grid-cols-2">
                <img src="{{ Storage::disk('public')->url($d['image']) }}" alt="" loading="lazy" class="w-full rounded-2xl shadow-sm {{ ($d['position'] ?? 'left') === 'right' ? 'sm:order-2' : '' }}">
                <div class="prose prose-slate max-w-none prose-a:text-brand-600">{!! $d['content'] ?? '' !!}</div>
            </div>
            @break

        @case('video')
            @php preg_match('%(?:youtube\.com/(?:watch\?v=|embed/|v/)|youtu\.be/)([\w-]{11})%', $d['url'] ?? '', $m); @endphp
            @if (! empty($m[1]))
                <div class="overflow-hidden rounded-2xl shadow-sm">
                    <div class="aspect-video"><iframe src="https://www.youtube.com/embed/{{ $m[1] }}" class="h-full w-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                </div>
            @endif
            @break

        @case('quote')
            <blockquote class="rounded-2xl border-l-4 border-brand-500 bg-brand-50 p-6">
                <p class="text-lg italic text-slate-700">"{{ $d['text'] ?? '' }}"</p>
                @if (! empty($d['author']))<footer class="mt-2 text-sm font-semibold text-brand-700">— {{ $d['author'] }}</footer>@endif
            </blockquote>
            @break

        @case('cta')
            <div class="rounded-3xl bg-gradient-to-r from-brand-700 to-brand-900 p-8 text-center text-white">
                @if (! empty($d['title']))<h3 class="text-2xl font-bold">{{ $d['title'] }}</h3>@endif
                @if (! empty($d['text']))<p class="mx-auto mt-2 max-w-xl text-brand-100">{{ $d['text'] }}</p>@endif
                @if (! empty($d['button_label']) && ! empty($d['button_url']))
                    <a href="{{ $d['button_url'] }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">{{ $d['button_label'] }} <x-heroicon-o-arrow-right class="h-4 w-4" /></a>
                @endif
            </div>
            @break
    @endswitch
@empty
    @if (filled($fallback ?? null))
        <article class="prose prose-slate max-w-none prose-headings:font-bold prose-a:text-brand-600 prose-img:rounded-2xl">{!! $fallback !!}</article>
    @endif
@endforelse
