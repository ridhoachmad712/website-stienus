@forelse ($blocks ?? [] as $block)
    @php
        $d = $block['data'] ?? [];
        $bg = $d['bg'] ?? 'none';
        $isDark = $bg === 'brand';
        $secBg = match ($bg) {
            'white' => 'bg-white',
            'gray' => 'bg-slate-100',
            'brand' => 'bg-gradient-to-br from-brand-700 to-brand-900 text-white',
            default => '',
        };
        $py = match ($d['padding'] ?? 'md') { 'sm' => 'py-6', 'lg' => 'py-20', default => 'py-12' };
        $inner = ($d['width'] ?? 'narrow') === 'wide' ? 'max-w-6xl' : 'max-w-3xl';
        $proseTone = $isDark ? 'prose-invert' : 'prose-slate';
        $headTone = $isDark ? 'text-white' : 'text-slate-900';
    @endphp

    <section class="{{ $secBg }} {{ $py }}">
        <div class="container-page">
            <div class="mx-auto {{ $inner }}" data-reveal>
                @switch($block['type'])
                    @case('rich_text')
                        <article class="prose {{ $proseTone }} max-w-none prose-headings:font-bold prose-a:text-brand-600 prose-img:rounded-2xl">{!! $d['content'] ?? '' !!}</article>
                        @break

                    @case('heading')
                        @php $tag = in_array($d['level'] ?? 'h2', ['h2','h3','h4']) ? $d['level'] : 'h2'; $sizes = ['h2'=>'text-3xl','h3'=>'text-2xl','h4'=>'text-xl']; @endphp
                        <{{ $tag }} class="{{ $sizes[$tag] }} font-bold {{ $headTone }} {{ ($d['align'] ?? 'left') === 'center' ? 'text-center' : '' }}">{{ $d['text'] ?? '' }}</{{ $tag }}>
                        @break

                    @case('image')
                        <figure>
                            <img src="{{ Storage::disk('public')->url($d['image']) }}" alt="{{ $d['caption'] ?? '' }}" loading="lazy" class="w-full rounded-2xl shadow-sm">
                            @if (! empty($d['caption']))<figcaption class="mt-2 text-center text-sm {{ $isDark ? 'text-white/70' : 'text-slate-400' }}">{{ $d['caption'] }}</figcaption>@endif
                        </figure>
                        @break

                    @case('image_text')
                        <div class="grid items-center gap-8 sm:grid-cols-2">
                            <img src="{{ Storage::disk('public')->url($d['image']) }}" alt="" loading="lazy" class="w-full rounded-2xl shadow-sm {{ ($d['position'] ?? 'left') === 'right' ? 'sm:order-2' : '' }}">
                            <div class="prose {{ $proseTone }} max-w-none prose-a:text-brand-600">{!! $d['content'] ?? '' !!}</div>
                        </div>
                        @break

                    @case('video')
                        @php preg_match('%(?:youtube\.com/(?:watch\?v=|embed/|v/)|youtu\.be/)([\w-]{11})%', $d['url'] ?? '', $m); @endphp
                        @if (! empty($m[1]))
                            <div class="overflow-hidden rounded-2xl shadow-sm"><div class="aspect-video"><iframe src="https://www.youtube.com/embed/{{ $m[1] }}" class="h-full w-full" frameborder="0" allowfullscreen></iframe></div></div>
                        @endif
                        @break

                    @case('quote')
                        <blockquote class="rounded-2xl border-l-4 border-brand-500 {{ $isDark ? 'bg-white/10' : 'bg-brand-50' }} p-6">
                            <p class="text-lg italic {{ $isDark ? 'text-white' : 'text-slate-700' }}">"{{ $d['text'] ?? '' }}"</p>
                            @if (! empty($d['author']))<footer class="mt-2 text-sm font-semibold {{ $isDark ? 'text-brand-100' : 'text-brand-700' }}">— {{ $d['author'] }}</footer>@endif
                        </blockquote>
                        @break

                    @case('cta')
                        <div class="rounded-3xl {{ $isDark ? 'bg-white/10 ring-1 ring-white/20' : 'bg-gradient-to-r from-brand-700 to-brand-900' }} p-8 text-center text-white">
                            @if (! empty($d['title']))<h3 class="text-2xl font-bold">{{ $d['title'] }}</h3>@endif
                            @if (! empty($d['text']))<p class="mx-auto mt-2 max-w-xl text-brand-100">{{ $d['text'] }}</p>@endif
                            @if (! empty($d['button_label']) && ! empty($d['button_url']))<a href="{{ $d['button_url'] }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">{{ $d['button_label'] }} <x-heroicon-o-arrow-right class="h-4 w-4" /></a>@endif
                        </div>
                        @break

                    @case('cards')
                        @if (! empty($d['heading']))<h2 class="mb-8 text-center text-3xl font-bold {{ $headTone }}">{{ $d['heading'] }}</h2>@endif
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($d['items'] ?? [] as $card)
                                <div class="rounded-3xl {{ $isDark ? 'bg-white/10 ring-white/20' : 'bg-white ring-slate-100' }} p-6 shadow-sm ring-1">
                                    @if (! empty($card['icon']))<span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $isDark ? 'bg-white/15 text-white' : 'bg-brand-50 text-brand-600' }}">@svg('heroicon-o-'.$card['icon'], 'h-6 w-6')</span>@endif
                                    <h3 class="mt-4 text-lg font-bold {{ $headTone }}">{{ $card['title'] ?? '' }}</h3>
                                    @if (! empty($card['text']))<p class="mt-1 text-sm {{ $isDark ? 'text-brand-100' : 'text-slate-500' }}">{{ $card['text'] }}</p>@endif
                                </div>
                            @endforeach
                        </div>
                        @break

                    @case('stats')
                        <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                            @foreach ($d['items'] ?? [] as $st)
                                <div class="text-center">
                                    @if (! empty($st['icon']))<div class="mx-auto mb-2 flex h-11 w-11 items-center justify-center rounded-xl {{ $isDark ? 'bg-white/15 text-white' : 'bg-brand-50 text-brand-600' }}">@svg('heroicon-o-'.$st['icon'], 'h-6 w-6')</div>@endif
                                    <div class="text-3xl font-extrabold {{ $isDark ? 'text-white' : 'text-brand-700' }}">{{ $st['value'] ?? '' }}</div>
                                    <div class="text-sm {{ $isDark ? 'text-brand-100' : 'text-slate-500' }}">{{ $st['label'] ?? '' }}</div>
                                </div>
                            @endforeach
                        </div>
                        @break

                    @case('steps')
                        @if (! empty($d['heading']))<h2 class="mb-8 text-center text-3xl font-bold {{ $headTone }}">{{ $d['heading'] }}</h2>@endif
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach ($d['items'] ?? [] as $i => $step)
                                <div class="rounded-3xl {{ $isDark ? 'bg-white/10' : 'bg-white ring-1 ring-slate-100' }} p-6 shadow-sm">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-600 text-lg font-bold text-white">{{ $i + 1 }}</span>
                                    <h3 class="mt-4 font-bold {{ $headTone }}">{{ $step['title'] ?? '' }}</h3>
                                    @if (! empty($step['text']))<p class="mt-1 text-sm {{ $isDark ? 'text-brand-100' : 'text-slate-500' }}">{{ $step['text'] }}</p>@endif
                                </div>
                            @endforeach
                        </div>
                        @break

                    @case('accordion')
                        @if (! empty($d['heading']))<h2 class="mb-6 text-2xl font-bold {{ $headTone }}">{{ $d['heading'] }}</h2>@endif
                        <div class="space-y-3">
                            @foreach ($d['items'] ?? [] as $acc)
                                <div x-data="{ open: false }" class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                                    <button @click="open = !open" class="flex w-full items-center justify-between gap-4 p-5 text-left">
                                        <span class="font-semibold text-slate-800">{{ $acc['title'] ?? '' }}</span>
                                        <x-heroicon-o-plus class="h-5 w-5 shrink-0 text-brand-600 transition" ::class="open && 'rotate-45'" />
                                    </button>
                                    <div x-show="open" x-collapse x-cloak class="border-t border-slate-100 px-5 py-4 text-slate-600"><p class="whitespace-pre-line">{{ $acc['content'] ?? '' }}</p></div>
                                </div>
                            @endforeach
                        </div>
                        @break

                    @case('columns')
                        <div class="grid gap-8 {{ (int) ($d['count'] ?? 2) === 3 ? 'md:grid-cols-3' : 'md:grid-cols-2' }}">
                            @foreach ($d['items'] ?? [] as $col)
                                <div class="prose {{ $proseTone }} max-w-none prose-a:text-brand-600">{!! $col['content'] ?? '' !!}</div>
                            @endforeach
                        </div>
                        @break

                    @case('gallery')
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                            @foreach ($d['images'] ?? [] as $img)
                                <img src="{{ Storage::disk('public')->url($img) }}" alt="" loading="lazy" class="aspect-square w-full rounded-2xl object-cover shadow-sm">
                            @endforeach
                        </div>
                        @break

                    @case('buttons')
                        <div class="flex flex-wrap gap-3 {{ ($d['width'] ?? 'narrow') === 'wide' ? 'justify-center' : '' }}">
                            @foreach ($d['items'] ?? [] as $btn)
                                <a href="{{ $btn['url'] ?? '#' }}" class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold transition {{ ($btn['style'] ?? 'primary') === 'outline' ? 'ring-1 ring-inset ring-brand-300 text-brand-700 hover:bg-brand-50' : 'bg-brand-600 text-white hover:bg-brand-700' }}">{{ $btn['label'] ?? '' }}</a>
                            @endforeach
                        </div>
                        @break

                    @case('embed')
                        <div class="overflow-hidden rounded-2xl [&_iframe]:w-full">{!! $d['html'] ?? '' !!}</div>
                        @break

                    @case('dynamic')
                        @include('partials.page-dynamic', ['source' => $d['source'] ?? '', 'heading' => $d['heading'] ?? null, 'limit' => (int) ($d['limit'] ?? 3), 'isDark' => $isDark, 'headTone' => $headTone])
                        @break
                @endswitch
            </div>
        </div>
    </section>
@empty
    @if (filled($fallback ?? null))
        <div class="container-page max-w-3xl py-12"><article class="prose prose-slate max-w-none prose-a:text-brand-600">{!! $fallback !!}</article></div>
    @endif
@endforelse
