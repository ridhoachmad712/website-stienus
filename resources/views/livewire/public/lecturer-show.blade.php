<div>
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 to-brand-950 py-16">
        <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full bg-brand-500/30 blur-3xl"></div>
        <div class="container-page relative">
            <nav class="mb-6 flex items-center gap-2 text-sm text-brand-200">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a><span>/</span>
                <a href="{{ route('lecturers.index') }}" class="hover:text-white">Dosen</a>
            </nav>
            <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-end">
                <div class="h-36 w-36 shrink-0 overflow-hidden rounded-3xl bg-gradient-to-br from-brand-400 to-brand-700 ring-4 ring-white/20">
                    @if ($lecturer->photo)
                        <img src="{{ Storage::disk('public')->url($lecturer->photo) }}" alt="{{ $lecturer->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full items-center justify-center text-5xl font-bold text-white/70">{{ Str::upper(Str::substr($lecturer->name, 0, 1)) }}</div>
                    @endif
                </div>
                <div class="text-center text-white sm:text-left">
                    @if ($lecturer->program)
                        <span class="inline-block rounded-full bg-white/10 px-3 py-1 text-xs font-semibold ring-1 ring-inset ring-white/20">{{ $lecturer->program->name }}</span>
                    @endif
                    <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">{{ $lecturer->name }}{{ $lecturer->title ? ', ' . $lecturer->title : '' }}</h1>
                    @if ($lecturer->expertise)<p class="mt-2 text-brand-100">{{ $lecturer->expertise }}</p>@endif
                </div>
            </div>
        </div>
    </section>

    <div class="container-page grid gap-8 py-12 lg:grid-cols-3">
        {{-- Main --}}
        <div class="space-y-6 lg:col-span-2">
            @if ($lecturer->bio)
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100" data-reveal>
                    <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900"><x-heroicon-o-user class="h-6 w-6 text-brand-600" /> Biografi</h2>
                    <div class="mt-3 whitespace-pre-line leading-relaxed text-slate-600">{{ $lecturer->bio }}</div>
                </div>
            @endif

            @if ($lecturer->education)
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100" data-reveal>
                    <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900"><x-heroicon-o-academic-cap class="h-6 w-6 text-brand-600" /> Riwayat Pendidikan</h2>
                    <ul class="mt-3 space-y-2">
                        @foreach (preg_split('/\r\n|\r|\n/', trim($lecturer->education)) as $edu)
                            @if (trim($edu) !== '')
                                <li class="flex gap-2 text-slate-600"><x-heroicon-o-check-badge class="mt-0.5 h-5 w-5 shrink-0 text-brand-500" />{{ $edu }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($lecturer->courses)
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100" data-reveal>
                    <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900"><x-heroicon-o-book-open class="h-6 w-6 text-brand-600" /> Mata Kuliah Diampu</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach (preg_split('/\r\n|\r|\n|,/', trim($lecturer->courses)) as $course)
                            @if (trim($course) !== '')
                                <span class="rounded-full bg-brand-50 px-3 py-1 text-sm font-medium text-brand-700">{{ trim($course) }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @unless ($lecturer->bio || $lecturer->education || $lecturer->courses)
                <div class="rounded-3xl bg-white p-8 text-slate-400 shadow-sm ring-1 ring-slate-100">Profil lengkap belum tersedia.</div>
            @endunless
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-6" data-reveal style="--reveal-delay: 120ms">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Informasi</h3>
                <dl class="mt-4 space-y-4 text-sm">
                    <div class="flex items-center justify-between"><dt class="text-slate-500">NIDN</dt><dd class="font-semibold text-slate-800">{{ $lecturer->nidn }}</dd></div>
                    @if ($lecturer->program)
                        <div class="flex items-center justify-between"><dt class="text-slate-500">Program Studi</dt><dd class="text-right font-semibold text-slate-800">{{ $lecturer->program->name }}</dd></div>
                    @endif
                    @if ($lecturer->expertise)
                        <div class="flex items-center justify-between gap-4"><dt class="text-slate-500">Keahlian</dt><dd class="text-right font-semibold text-slate-800">{{ $lecturer->expertise }}</dd></div>
                    @endif
                </dl>
            </div>

            @if ($lecturer->google_scholar_link || $lecturer->sinta_link)
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Publikasi</h3>
                    <div class="mt-4 space-y-2">
                        @if ($lecturer->google_scholar_link)
                            <a href="{{ $lecturer->google_scholar_link }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-brand-50 hover:text-brand-700"><x-heroicon-o-academic-cap class="h-5 w-5" /> Google Scholar <x-heroicon-o-arrow-top-right-on-square class="ml-auto h-4 w-4" /></a>
                        @endif
                        @if ($lecturer->sinta_link)
                            <a href="{{ $lecturer->sinta_link }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-brand-50 hover:text-brand-700"><x-heroicon-o-link class="h-5 w-5" /> SINTA <x-heroicon-o-arrow-top-right-on-square class="ml-auto h-4 w-4" /></a>
                        @endif
                    </div>
                </div>
            @endif

            <a href="{{ route('lecturers.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700"><x-heroicon-o-arrow-left class="h-4 w-4" /> Semua Dosen</a>
        </aside>
    </div>
</div>
