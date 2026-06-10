<div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Jadwal Kegiatan</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Agenda</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">Jadwal kegiatan, seminar, dan acara penting di lingkungan kampus.</p>
        </div>
    </section>

    <div class="container-page py-12">
        {{-- Upcoming --}}
        <h2 class="mb-6 flex items-center gap-2 text-2xl font-bold text-slate-900">
            <x-heroicon-o-calendar-days class="h-6 w-6 text-brand-600" /> Akan Datang
        </h2>
        @if ($this->upcoming->isNotEmpty())
            <div class="grid gap-5 lg:grid-cols-2">
                @foreach ($this->upcoming as $agenda)
                    <a href="{{ route('agenda.show', $agenda) }}" class="group flex items-center gap-5 rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex h-20 w-20 shrink-0 flex-col items-center justify-center rounded-2xl bg-gradient-to-br from-brand-600 to-brand-800 text-white">
                            <span class="text-2xl font-extrabold leading-none">{{ $agenda->event_date->format('d') }}</span>
                            <span class="text-xs font-medium uppercase">{{ $agenda->event_date->translatedFormat('M Y') }}</span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-slate-900 transition group-hover:text-brand-700">{{ $agenda->title }}</h3>
                            <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-500"><x-heroicon-o-map-pin class="h-4 w-4 shrink-0 text-brand-500" />{{ $agenda->location }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="rounded-3xl bg-white py-12 text-center text-slate-400 shadow-sm ring-1 ring-slate-100">Belum ada agenda mendatang.</div>
        @endif

        {{-- Past --}}
        @if ($this->past->isNotEmpty())
            <h2 class="mb-6 mt-14 flex items-center gap-2 text-2xl font-bold text-slate-900">
                <x-heroicon-o-clock class="h-6 w-6 text-slate-400" /> Telah Berlalu
            </h2>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->past as $agenda)
                    <a href="{{ route('agenda.show', $agenda) }}" class="group rounded-3xl bg-white p-5 opacity-90 shadow-sm ring-1 ring-slate-100 transition hover:opacity-100 hover:shadow-md">
                        <p class="text-sm font-semibold text-brand-600">{{ $agenda->event_date->translatedFormat('d F Y') }}</p>
                        <h3 class="mt-1 font-bold text-slate-800 transition group-hover:text-brand-700">{{ $agenda->title }}</h3>
                        <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-500"><x-heroicon-o-map-pin class="h-4 w-4 shrink-0" />{{ $agenda->location }}</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $this->past->links() }}</div>
        @endif
    </div>
</div>
