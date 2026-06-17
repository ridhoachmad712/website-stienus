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

        <div class="mb-4 flex items-center justify-between">
            <p class="text-sm text-slate-500">Menampilkan <span class="font-semibold text-slate-700">{{ $this->lecturers->count() }}</span> dosen</p>
        </div>

        {{-- Skeleton saat memfilter/mencari --}}
        <div wire:loading.delay wire:target="search,program">
            <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                <x-card-skeleton :count="8" />
            </div>
        </div>

        <div wire:loading.remove.delay wire:target="search,program">
        @if ($this->lecturers->isNotEmpty())
            <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($this->lecturers as $lecturer)
                    <a href="{{ route('lecturers.show', $lecturer) }}" data-reveal style="--reveal-delay: {{ ($loop->index % 4) * 70 }}ms" class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl hover:shadow-brand-500/10">
                        {{-- Foto (ukuran lebih ringkas) --}}
                        <div class="relative aspect-square overflow-hidden bg-gradient-to-br from-brand-400 to-brand-700">
                            @if ($lecturer->photo)
                                <img src="{{ Storage::disk('public')->url($lecturer->photo) }}" alt="{{ $lecturer->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center">
                                    <span class="text-5xl font-bold text-white/70">{{ Str::upper(Str::substr($lecturer->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-brand-700 backdrop-blur">{{ $lecturer->program?->name }}</span>
                            <span class="absolute bottom-3 right-3 flex h-8 w-8 items-center justify-center rounded-lg bg-white/90 text-brand-700 opacity-0 backdrop-blur transition group-hover:opacity-100"><x-heroicon-o-arrow-right class="h-4 w-4" /></span>
                        </div>

                        {{-- Info --}}
                        <div class="flex flex-1 flex-col p-4">
                            <h2 class="text-sm font-bold leading-snug text-slate-900 transition group-hover:text-brand-700">{{ $lecturer->name }}{{ $lecturer->title ? ', ' . $lecturer->title : '' }}</h2>
                            @if ($lecturer->expertise)
                                <p class="mt-1.5 flex items-start gap-1.5 text-xs text-slate-500"><x-heroicon-o-light-bulb class="mt-0.5 h-3.5 w-3.5 shrink-0 text-amber-500" />{{ $lecturer->expertise }}</p>
                            @endif
                            <span class="mt-auto pt-3 text-[11px] text-slate-400">NIDN {{ $lecturer->nidn }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="rounded-3xl bg-white py-20 text-center shadow-sm ring-1 ring-slate-100">
                <x-heroicon-o-user-group class="mx-auto h-14 w-14 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Tidak ada dosen ditemukan</h3>
                <p class="mt-1 text-sm text-slate-400">Coba ubah kata kunci atau filter program studi.</p>
            </div>
        @endif
        </div>
    </div>
</div>
