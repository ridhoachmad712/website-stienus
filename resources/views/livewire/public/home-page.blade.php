<div>
    @php
        $hero = $this->home;
        $heroTitle = $hero->hero_highlight
            ? str_replace($hero->hero_highlight, '<span class="bg-gradient-to-r from-amber-300 to-amber-100 bg-clip-text text-transparent">' . e($hero->hero_highlight) . '</span>', e($hero->hero_title))
            : e($hero->hero_title);
        $heroStats = $this->heroStats;
        // Slider hanya tampil bila dipilih DAN ada slide aktif; selain itu Hero Teks.
        $useSlider = $hero->hero_type === 'slider' && $this->slides->isNotEmpty();
    @endphp

    {{-- ===== SLIDER ===== --}}
    @if ($useSlider)
        <section x-data="{ active: 0, total: {{ $this->slides->count() }} }" x-init="setInterval(() => active = (active + 1) % total, 6000)" class="relative">
            <div class="relative h-[60vh] min-h-[380px] overflow-hidden">
                @foreach ($this->slides as $i => $slide)
                    <div x-show="active === {{ $i }}" x-transition.opacity.duration.700ms class="absolute inset-0">
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="h-full w-full object-cover transition-transform duration-[7000ms] ease-out" :class="active === {{ $i }} ? 'scale-105' : 'scale-100'">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
                        <div class="container-page relative flex h-full flex-col justify-center">
                            <div class="max-w-2xl text-white" style="text-shadow: 0 2px 14px rgba(0,0,0,0.55), 0 1px 3px rgba(0,0,0,0.5)">
                                <h2 class="text-3xl font-extrabold sm:text-5xl">{{ $slide->title }}</h2>
                                @if ($slide->subtitle)<p class="mt-4 text-lg text-white/95">{{ $slide->subtitle }}</p>@endif
                                @if ($slide->button_text && $slide->button_url)
                                    <a href="{{ $slide->button_url }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-brand-700 shadow-lg transition hover:bg-brand-50">{{ $slide->button_text }} <x-heroicon-o-arrow-right class="h-4 w-4" /></a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- dots --}}
            <div class="absolute bottom-5 left-1/2 flex -translate-x-1/2 gap-2">
                @foreach ($this->slides as $i => $slide)
                    <button @click="active = {{ $i }}" class="h-2.5 rounded-full bg-white transition-all" :class="active === {{ $i }} ? 'w-8 opacity-100' : 'w-2.5 opacity-50'"></button>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===== HERO TEKS (tampil bila tidak memakai slider) ===== --}}
    @unless ($useSlider)
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand-800 to-brand-950">
        @if ($this->heroImage)
            <div class="absolute inset-0">
                <img src="{{ Storage::disk('public')->url($this->heroImage) }}" alt="" class="h-full w-full object-cover opacity-20">
            </div>
        @endif
        {{-- decorative blobs --}}
        <div class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-brand-500/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-indigo-400/20 blur-3xl"></div>

        <div class="container-page relative grid items-end gap-10 pt-20 lg:grid-cols-2 lg:pt-28">
            <div class="text-white pb-20 lg:pb-28">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium text-brand-100 ring-1 ring-inset ring-white/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    {{ $hero->hero_badge }}
                </span>
                <h1 class="mt-6 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">{!! $heroTitle !!}</h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-brand-100">{{ $hero->hero_subtitle }}</p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ $hero->hero_cta1_url }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-brand-700 shadow-lg shadow-brand-950/30 transition hover:-translate-y-0.5 hover:bg-brand-50">
                        {{ $hero->hero_cta1_text }} <x-heroicon-o-arrow-right class="h-4 w-4" />
                    </a>
                    <a href="{{ $hero->hero_cta2_url }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-6 py-3 text-sm font-semibold text-white ring-1 ring-inset ring-white/30 transition hover:bg-white/20">
                        {{ $hero->hero_cta2_text }}
                    </a>
                </div>
            </div>

            {{-- floating photo / stats card --}}
            @if ($this->heroSideImage)
                <div class="relative hidden self-end lg:block">
                    <div class="ml-auto max-w-[500px] overflow-hidden rounded-t-3xl">
                        <img src="{{ $this->heroSideImage }}" alt="" class="block aspect-[4/5] w-full object-cover">
                    </div>
                </div>
            @else
                <div class="relative hidden self-center lg:block">
                <div class="ml-auto max-w-md rounded-3xl bg-white/10 p-6 ring-1 ring-inset ring-white/20 backdrop-blur">
                    <div class="grid grid-cols-2 gap-4">
                        @foreach ($heroStats as $stat)
                            <div class="rounded-2xl bg-white p-5 text-center shadow-sm">
                                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                    @svg('heroicon-o-' . $stat['icon'], 'h-6 w-6')
                                </div>
                                <div class="mt-3 text-3xl font-extrabold text-slate-900">
                                    @if ($stat['numeric'])
                                        <span x-data="{ n: 0 }" x-intersect.once="let t={{ (int) $stat['value'] }}, s=Math.max(1, Math.ceil(t/40)); let i=setInterval(()=>{ n+=s; if(n>=t){n=t; clearInterval(i)} }, 30)" x-text="n">{{ $stat['value'] }}</span>+
                                    @else
                                        {{ $stat['value'] }}
                                    @endif
                                </div>
                                <div class="text-xs font-medium text-slate-500">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                </div>
            @endif
        </div>
    </section>
    @endunless

    {{-- ===== STATS (mobile) ===== --}}
    <section class="container-page -mt-2 lg:hidden">
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach ($heroStats as $stat)
                <div class="rounded-2xl bg-white p-4 text-center shadow-sm ring-1 ring-slate-100">
                    <div class="text-2xl font-extrabold text-brand-700">
                        @if ($stat['numeric'])
                            <span x-data="{ n: 0 }" x-intersect.once="let t={{ (int) $stat['value'] }}, s=Math.max(1, Math.ceil(t/40)); let i=setInterval(()=>{ n+=s; if(n>=t){n=t; clearInterval(i)} }, 30)" x-text="n">{{ $stat['value'] }}</span>+
                        @else
                            {{ $stat['value'] }}
                        @endif
                    </div>
                    <div class="text-xs text-slate-500">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== LATEST NEWS ===== --}}
    @if ($this->latestPosts->isNotEmpty())
        @php $featured = $this->latestPosts->first(); $rest = $this->latestPosts->slice(1, 4); @endphp
        <section class="container-page py-20" data-reveal>
            <div class="mb-10 flex items-end justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">{{ $this->home->news_eyebrow }}</p>
                    <h2 class="mt-1 text-4xl font-extrabold tracking-tight text-slate-900">{{ $this->home->news_title }}</h2>
                </div>
                <a href="{{ route('posts.index') }}" class="group hidden items-center gap-1.5 text-sm font-semibold text-brand-600 transition hover:text-brand-700 sm:inline-flex">
                    Semua berita <x-heroicon-o-arrow-right class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                {{-- featured --}}
                <a href="{{ route('posts.show', $featured->slug) }}" class="group relative flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition duration-300 ease-out hover:-translate-y-1 hover:shadow-xl hover:shadow-brand-500/10">
                    <div class="relative h-64 overflow-hidden bg-gradient-to-br from-brand-500 to-brand-800">
                        @if ($featured->featured_image)
                            <img src="{{ Storage::disk('public')->url($featured->featured_image) }}" alt="" class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-110">
                        @else
                            <div class="flex h-full items-center justify-center"><x-heroicon-o-newspaper class="h-20 w-20 text-white/40" /></div>
                        @endif
                        <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-brand-700 backdrop-blur">{{ $featured->category?->name }}</span>
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <h3 class="text-xl font-bold leading-snug text-slate-900 transition group-hover:text-brand-700">{{ $featured->title }}</h3>
                        <p class="mt-3 line-clamp-2 text-sm text-slate-500">{{ $featured->summary }}</p>
                        <div class="mt-4 flex items-center gap-4 text-xs text-slate-400">
                            <span class="flex items-center gap-1.5"><x-heroicon-o-calendar class="h-4 w-4" />{{ $featured->created_at->translatedFormat('d M Y') }}</span>
                            <span class="flex items-center gap-1.5"><x-heroicon-o-eye class="h-4 w-4" />{{ number_format($featured->views_count) }}x</span>
                        </div>
                    </div>
                </a>

                {{-- list --}}
                <div class="flex flex-col divide-y divide-slate-100 overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100">
                    @foreach ($rest as $post)
                        <a href="{{ route('posts.show', $post->slug) }}" class="group flex gap-4 p-4 transition hover:bg-slate-50">
                            <div class="flex h-20 w-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-br from-brand-400 to-brand-700">
                                @if ($post->featured_image)
                                    <img src="{{ Storage::disk('public')->url($post->featured_image) }}" alt="" class="h-full w-full object-cover">
                                @else
                                    <x-heroicon-o-newspaper class="h-8 w-8 text-white/50" />
                                @endif
                            </div>
                            <div class="min-w-0">
                                <span class="text-xs font-semibold text-brand-600">{{ $post->category?->name }}</span>
                                <h4 class="mt-0.5 line-clamp-2 font-semibold text-slate-800 transition group-hover:text-brand-700">{{ $post->title }}</h4>
                                <p class="mt-1 text-xs text-slate-400">{{ $post->created_at->translatedFormat('d M Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== PROGRAMS ===== --}}
    @if ($this->featuredPrograms->isNotEmpty())
        <section class="bg-white py-24">
            <div class="container-page">
                <div class="mx-auto mb-12 max-w-2xl text-center" data-reveal>
                    <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">{{ $this->home->programs_eyebrow }}</p>
                    <h2 class="mt-1 text-4xl font-extrabold tracking-tight text-slate-900">{{ $this->home->programs_title }}</h2>
                    <p class="mt-3 text-slate-500">{{ $this->home->programs_subtitle }}</p>
                </div>

                <div class="flex flex-wrap justify-center gap-6">
                    @foreach ($this->featuredPrograms as $program)
                        <a href="{{ route('programs.show', $program->slug) }}" data-reveal style="--reveal-delay: {{ $loop->index * 90 }}ms" class="group w-full rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition duration-300 ease-out hover:-translate-y-1.5 hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 sm:w-[360px]">
                            <div class="flex items-center justify-between">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white">
                                    <x-heroicon-o-academic-cap class="h-6 w-6" />
                                </span>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $program->accreditation }}</span>
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-900 transition group-hover:text-brand-700">{{ $program->degree }} {{ $program->name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">Program Studi Strata Satu</p>
                            <div class="mt-4 flex items-center gap-4 border-t border-slate-100 pt-4 text-xs font-medium text-slate-500">
                                <span class="flex items-center gap-1.5"><x-heroicon-o-bookmark class="h-4 w-4 text-brand-500" />{{ $program->degree }}</span>
                                <span class="flex items-center gap-1.5"><x-heroicon-o-users class="h-4 w-4 text-brand-500" />{{ $program->lecturers_count }} Dosen</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== PIMPINAN ===== --}}
    @if ($this->leaders->isNotEmpty())
        <section class="bg-slate-50 py-24">
            <div class="container-page">
                <div class="mx-auto mb-12 max-w-2xl text-center" data-reveal>
                    <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">{{ $this->home->leaders_eyebrow }}</p>
                    <h2 class="mt-1 text-4xl font-extrabold tracking-tight text-slate-900">{{ $this->home->leaders_title }}</h2>
                    <p class="mt-3 text-slate-500">{{ $this->home->leaders_subtitle }}</p>
                </div>

                <div x-data="{ slide(dir) { const t = $refs.track; t.scrollBy({ left: dir * t.clientWidth, behavior: 'smooth' }); } }" class="relative" data-reveal>
                    {{-- tombol navigasi (desktop); mobile cukup swipe --}}
                    <button type="button" @click="slide(-1)" aria-label="Sebelumnya"
                        class="absolute -left-3 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-slate-700 shadow-md ring-1 ring-slate-100 transition hover:bg-brand-600 hover:text-white sm:flex">
                        <x-heroicon-o-chevron-left class="h-5 w-5" />
                    </button>
                    <button type="button" @click="slide(1)" aria-label="Berikutnya"
                        class="absolute -right-3 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-slate-700 shadow-md ring-1 ring-slate-100 transition hover:bg-brand-600 hover:text-white sm:flex">
                        <x-heroicon-o-chevron-right class="h-5 w-5" />
                    </button>

                    <div x-ref="track" class="-mx-3 flex snap-x snap-mandatory items-stretch overflow-x-auto scroll-smooth pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                        @foreach ($this->leaders as $leader)
                            <div class="shrink-0 basis-full snap-start px-3 sm:basis-1/2 lg:basis-1/4">
                                <div class="group flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl hover:shadow-brand-500/10">
                                    <div class="relative aspect-square overflow-hidden bg-gradient-to-br from-brand-400 to-brand-700">
                                        @if ($leader->photo_url)
                                            <img src="{{ $leader->photo_url }}" alt="{{ $leader->name }}" loading="lazy" class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-105">
                                        @else
                                            <div class="flex h-full items-center justify-center text-5xl font-bold text-white/70">{{ Str::upper(Str::substr($leader->name, 0, 1)) }}</div>
                                        @endif
                                    </div>
                                    <div class="flex flex-1 flex-col p-5 text-center">
                                        <h3 class="font-bold leading-snug text-slate-900">{{ $leader->name }}</h3>
                                        <p class="mt-1 text-sm font-medium text-brand-600">{{ $leader->position }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ===== VIDEO PROFIL ===== --}}
    @if ($this->videoEmbedUrl)
        <section class="container-page py-20" data-reveal>
            <div class="mx-auto mb-8 max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Video</p>
                <h2 class="mt-1 text-4xl font-extrabold tracking-tight text-slate-900">{{ $this->home->video_title }}</h2>
                <p class="mt-3 text-slate-500">{{ $this->home->video_subtitle }}</p>
            </div>
            <div class="mx-auto max-w-4xl overflow-hidden rounded-3xl shadow-xl ring-1 ring-slate-100">
                <div class="aspect-video">
                    <iframe src="{{ $this->videoEmbedUrl }}" title="Video Profil" class="h-full w-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </section>
    @endif

    {{-- ===== AGENDA ===== --}}
    @if ($this->upcomingAgendas->isNotEmpty())
        <section class="container-page py-20" data-reveal>
            <div class="mb-10 flex items-end justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">{{ $this->home->agenda_eyebrow }}</p>
                    <h2 class="mt-1 text-4xl font-extrabold tracking-tight text-slate-900">{{ $this->home->agenda_title }}</h2>
                </div>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($this->upcomingAgendas as $agenda)
                    <div data-reveal style="--reveal-delay: {{ $loop->index * 80 }}ms" class="flex items-center gap-5 rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition duration-300 ease-out hover:-translate-y-0.5 hover:shadow-lg hover:shadow-brand-500/10">
                        <div class="flex h-20 w-20 shrink-0 flex-col items-center justify-center rounded-2xl bg-gradient-to-br from-brand-600 to-brand-800 text-white">
                            <span class="text-2xl font-extrabold leading-none">{{ $agenda->event_date->format('d') }}</span>
                            <span class="text-xs font-medium uppercase">{{ $agenda->event_date->translatedFormat('M Y') }}</span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="truncate font-bold text-slate-900">{{ $agenda->title }}</h3>
                            <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-500"><x-heroicon-o-map-pin class="h-4 w-4 shrink-0 text-brand-500" />{{ $agenda->location }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===== TESTIMONIALS ===== --}}
    @if ($this->testimonials->isNotEmpty())
        <section class="bg-white py-24">
            <div class="container-page">
                <div class="mx-auto mb-12 max-w-2xl text-center" data-reveal>
                    <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Testimoni</p>
                    <h2 class="mt-1 text-4xl font-extrabold tracking-tight text-slate-900">Kata Mereka</h2>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->testimonials as $t)
                        <figure data-reveal style="--reveal-delay: {{ $loop->index * 90 }}ms" class="flex flex-col rounded-3xl bg-slate-50 p-6 ring-1 ring-slate-100 transition duration-300 ease-out hover:-translate-y-1 hover:bg-white hover:shadow-xl hover:shadow-brand-500/10">
                            <x-heroicon-s-chat-bubble-left-right class="h-8 w-8 text-brand-200" />
                            <blockquote class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">"{{ $t->content }}"</blockquote>
                            <figcaption class="mt-5 flex items-center gap-3">
                                @if ($t->photo_url)
                                    <img src="{{ $t->photo_url }}" alt="{{ $t->name }}" class="h-11 w-11 rounded-full object-cover">
                                @else
                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-600 font-bold text-white">{{ Str::upper(Str::substr($t->name, 0, 1)) }}</div>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $t->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $t->role }}</p>
                                </div>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== PARTNERS ===== --}}
    @if ($this->partners->isNotEmpty())
        <section class="container-page py-16" data-reveal>
            <p class="mb-8 text-center text-sm font-semibold uppercase tracking-wider text-slate-400">Mitra &amp; Akreditasi</p>
            <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-6">
                @foreach ($this->partners as $partner)
                    <a href="{{ $partner->url ?? '#' }}" @if($partner->url) target="_blank" rel="noopener" @endif class="grayscale transition hover:grayscale-0">
                        <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="h-12 w-auto object-contain" title="{{ $partner->name }}">
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===== CTA ===== --}}
    <section class="container-page pb-24" data-reveal>
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-brand-700 to-brand-900 px-8 py-16 text-center shadow-xl">
            <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
            <h2 class="relative text-3xl font-bold text-white sm:text-4xl">{{ $this->home->cta_title }}</h2>
            <p class="relative mx-auto mt-4 max-w-xl text-brand-100">{{ $this->home->cta_subtitle }}</p>
            <a href="{{ $this->home->cta_button_url }}" class="relative mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 text-sm font-semibold text-brand-700 shadow-lg transition hover:-translate-y-0.5 hover:bg-brand-50">
                {{ $this->home->cta_button_text }} <x-heroicon-o-arrow-right class="h-4 w-4" />
            </a>
        </div>
    </section>
</div>
