<div>
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 to-brand-950 py-16">
        <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full bg-brand-500/30 blur-3xl"></div>
        <div class="container-page relative max-w-3xl">
            <nav class="mb-6 flex items-center gap-2 text-sm text-brand-200">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a><span>/</span>
                <a href="{{ route('agenda.index') }}" class="hover:text-white">Agenda</a>
            </nav>
            <h1 class="text-3xl font-extrabold leading-tight text-white sm:text-4xl">{{ $agenda->title }}</h1>
            <div class="mt-5 flex flex-wrap items-center gap-5 text-sm text-brand-100">
                <span class="flex items-center gap-2"><x-heroicon-o-calendar class="h-4 w-4" />{{ $agenda->event_date->translatedFormat('l, d F Y') }}</span>
                <span class="flex items-center gap-2"><x-heroicon-o-map-pin class="h-4 w-4" />{{ $agenda->location }}</span>
            </div>
        </div>
    </section>

    <div class="container-page max-w-3xl py-12">
        @if ($agenda->image)
            <img src="{{ Storage::disk('public')->url($agenda->image) }}" alt="{{ $agenda->title }}" loading="lazy" class="mb-8 w-full rounded-3xl shadow-md" data-reveal>
        @endif
        <div class="prose prose-slate max-w-none" data-reveal>
            {!! nl2br(e($agenda->description)) !!}
        </div>
        <div class="mt-12 border-t border-slate-200 pt-6">
            <a href="{{ route('agenda.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                <x-heroicon-o-arrow-left class="h-4 w-4" /> Kembali ke Agenda
            </a>
        </div>
    </div>
</div>
