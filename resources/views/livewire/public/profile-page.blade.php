<div>
    @php $p = $this->profile; @endphp
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Profil</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">{{ $this->title }}</h1>
        </div>
    </section>

    <div class="container-page grid gap-8 py-12 lg:grid-cols-4">
        {{-- Sidebar --}}
        <aside class="lg:col-span-1" data-reveal>
            <nav class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                @foreach ($this->menu as $key => $item)
                    <a href="{{ route($item['route']) }}"
                        @class([
                            'flex items-center justify-between border-l-4 px-5 py-3.5 text-sm font-medium transition',
                            'border-brand-600 bg-brand-50 text-brand-700' => $section === $key,
                            'border-transparent text-slate-600 hover:bg-slate-50' => $section !== $key,
                        ])>
                        {{ $item['label'] }}
                        @if ($section === $key) <x-heroicon-o-chevron-right class="h-4 w-4" /> @endif
                    </a>
                @endforeach
            </nav>
        </aside>

        {{-- Content --}}
        <div class="lg:col-span-3">
            <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100" data-reveal style="--reveal-delay: 100ms">
                @switch($section)
                    @case('history')
                        <article class="prose prose-slate max-w-none">{!! $p->history ?: '<p class="text-slate-400">Konten sejarah belum diisi.</p>' !!}</article>
                        @break

                    @case('leader')
                        <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                            <div class="shrink-0 text-center">
                                @if ($p->leader_photo)
                                    <img src="{{ Storage::disk('public')->url($p->leader_photo) }}" alt="{{ $p->leader_name }}" class="mx-auto h-40 w-40 rounded-2xl object-cover shadow ring-4 ring-slate-50">
                                @else
                                    <div class="mx-auto flex h-40 w-40 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-400 to-brand-700 text-white"><x-heroicon-o-user class="h-16 w-16" /></div>
                                @endif
                                <p class="mt-3 font-bold text-slate-900">{{ $p->leader_name }}</p>
                                <p class="text-sm text-brand-600">{{ $p->leader_title }}</p>
                            </div>
                            <article class="prose prose-slate max-w-none flex-1">{!! $p->leader_speech ?: '<p class="text-slate-400">Sambutan belum diisi.</p>' !!}</article>
                        </div>
                        @break

                    @case('structure')
                        @if ($p->org_structure_image)
                            <img src="{{ Storage::disk('public')->url($p->org_structure_image) }}" alt="Struktur Organisasi" class="mb-6 w-full rounded-2xl ring-1 ring-slate-100">
                        @endif
                        <article class="prose prose-slate max-w-none">{!! $p->org_structure_text ?: '<p class="text-slate-400">Struktur organisasi belum diisi.</p>' !!}</article>
                        @break

                    @default
                        <article class="prose prose-slate max-w-none">{!! $p->about ?: '<p class="text-slate-400">Konten belum diisi.</p>' !!}</article>
                        <div class="mt-8 grid gap-6 sm:grid-cols-2">
                            <div class="rounded-2xl bg-brand-50 p-6">
                                <h3 class="flex items-center gap-2 font-bold text-brand-800"><x-heroicon-o-eye class="h-5 w-5" /> Visi</h3>
                                <p class="mt-2 text-sm text-slate-600">{{ $p->vision }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-6">
                                <h3 class="flex items-center gap-2 font-bold text-slate-800"><x-heroicon-o-flag class="h-5 w-5 text-brand-600" /> Misi</h3>
                                <div class="prose prose-sm mt-2 max-w-none text-slate-600">{!! $p->mission !!}</div>
                            </div>
                        </div>
                @endswitch
            </div>
        </div>
    </div>
</div>
