<div>
    {{-- Page header --}}
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Sumber Daya Manusia</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Direktori Dosen</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">Temui para dosen profesional dan ahli di bidangnya yang siap membimbing perjalanan akademikmu.</p>
        </div>
    </section>

    <div class="container-page py-12">
        {{-- Filter bar --}}
        <div class="mb-8 flex flex-col gap-4 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama atau NIDN..."
                    class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-11 pr-4 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500"
                >
            </div>
            <select wire:model.live="program" class="rounded-xl border-0 bg-slate-50 py-2.5 pl-4 pr-10 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500 sm:w-64">
                <option value="">Semua Program Studi</option>
                @foreach ($this->programs as $program)
                    <option value="{{ $program->slug }}">{{ $program->name }}</option>
                @endforeach
            </select>
        </div>

        <div wire:loading.delay class="mb-4 text-sm text-slate-400">Memuat...</div>

        @if ($this->lecturers->isNotEmpty())
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->lecturers as $lecturer)
                    <article class="group rounded-3xl bg-white p-6 text-center shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg">
                        @if ($lecturer->photo)
                            <img src="{{ Storage::disk('public')->url($lecturer->photo) }}" alt="{{ $lecturer->name }}" class="mx-auto h-24 w-24 rounded-2xl object-cover ring-4 ring-slate-50">
                        @else
                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-400 to-brand-700 text-3xl font-bold text-white ring-4 ring-slate-50">
                                {{ Str::upper(Str::substr($lecturer->name, 0, 1)) }}
                            </div>
                        @endif
                        <h2 class="mt-4 font-bold text-slate-900">{{ $lecturer->name }}{{ $lecturer->title ? ', ' . $lecturer->title : '' }}</h2>
                        <p class="mt-1 inline-block rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700">{{ $lecturer->program?->name }}</p>

                        @if ($lecturer->expertise)
                            <p class="mt-3 flex items-center justify-center gap-1.5 text-xs text-slate-500"><x-heroicon-o-light-bulb class="h-4 w-4 text-amber-500" />{{ $lecturer->expertise }}</p>
                        @endif

                        <div class="mt-4 flex items-center justify-center gap-4 border-t border-slate-100 pt-4 text-xs">
                            <span class="text-slate-400">NIDN: {{ $lecturer->nidn }}</span>
                            <div class="flex gap-2">
                                @if ($lecturer->google_scholar_link)
                                    <a href="{{ $lecturer->google_scholar_link }}" target="_blank" rel="noopener" class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition hover:bg-brand-600 hover:text-white" title="Google Scholar"><x-heroicon-o-academic-cap class="h-4 w-4" /></a>
                                @endif
                                @if ($lecturer->sinta_link)
                                    <a href="{{ $lecturer->sinta_link }}" target="_blank" rel="noopener" class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition hover:bg-brand-600 hover:text-white" title="SINTA"><x-heroicon-o-link class="h-4 w-4" /></a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">{{ $this->lecturers->links() }}</div>
        @else
            <div class="rounded-3xl bg-white py-20 text-center shadow-sm ring-1 ring-slate-100">
                <x-heroicon-o-user-group class="mx-auto h-14 w-14 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Tidak ada dosen ditemukan</h3>
                <p class="mt-1 text-sm text-slate-400">Coba ubah kata kunci atau filter program studi.</p>
            </div>
        @endif
    </div>
</div>
