<div>
    {{-- Page header --}}
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Akademik</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Program Studi</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">STIE Nusantara Makassar menyelenggarakan program studi jenjang Sarjana (S1) yang terakreditasi dan relevan dengan dunia kerja.</p>
        </div>
    </section>

    <div class="container-page py-12">
        @if ($this->programs->isNotEmpty())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->programs as $program)
                    <a href="{{ route('programs.show', $program->slug) }}" class="group rounded-3xl border border-slate-100 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:border-brand-200 hover:shadow-lg">
                        <div class="flex items-center justify-between">
                            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white">
                                <x-heroicon-o-academic-cap class="h-7 w-7" />
                            </span>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Akreditasi {{ $program->accreditation }}</span>
                        </div>
                        <h2 class="mt-5 text-xl font-bold text-slate-900 transition group-hover:text-brand-700">{{ $program->degree }} {{ $program->name }}</h2>
                        <p class="mt-2 text-sm text-slate-500">Program Studi jenjang Sarjana (Strata Satu) di STIE Nusantara Makassar.</p>
                        <div class="mt-5 flex items-center gap-4 border-t border-slate-100 pt-4 text-xs font-medium text-slate-500">
                            <span class="flex items-center gap-1.5"><x-heroicon-o-bookmark class="h-4 w-4 text-brand-500" />{{ $program->degree }}</span>
                            <span class="flex items-center gap-1.5"><x-heroicon-o-users class="h-4 w-4 text-brand-500" />{{ $program->lecturers_count }} Dosen</span>
                            <span class="ml-auto inline-flex items-center gap-1 text-brand-600 opacity-0 transition group-hover:opacity-100">Detail <x-heroicon-o-arrow-right class="h-3.5 w-3.5" /></span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="rounded-3xl bg-white py-20 text-center shadow-sm ring-1 ring-slate-100">
                <x-heroicon-o-academic-cap class="mx-auto h-14 w-14 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Belum ada program studi</h3>
            </div>
        @endif
    </div>
</div>
