<div>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 to-brand-950 py-16">
        <div class="pointer-events-none absolute -right-20 -top-24 h-80 w-80 rounded-full bg-brand-500/30 blur-3xl"></div>
        <div class="container-page relative">
            <nav class="mb-6 flex items-center gap-2 text-sm text-brand-200">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span>/</span>
                <a href="{{ route('programs.index') }}" class="hover:text-white">Program Studi</a>
            </nav>
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white ring-1 ring-inset ring-white/20">{{ $program->degree }}</span>
                <span class="rounded-full bg-emerald-400/20 px-3 py-1 text-xs font-semibold text-emerald-100 ring-1 ring-inset ring-emerald-300/30">Akreditasi {{ $program->accreditation }}</span>
            </div>
            <h1 class="mt-4 text-4xl font-extrabold text-white sm:text-5xl">{{ $program->degree }} {{ $program->name }}</h1>
            <p class="mt-3 flex items-center gap-2 text-brand-100"><x-heroicon-o-building-library class="h-5 w-5" />STIE Nusantara Makassar</p>
        </div>
    </section>

    <div class="container-page grid gap-8 py-12 lg:grid-cols-3">
        {{-- Main --}}
        <div class="space-y-8 lg:col-span-2">
            @if ($program->vision_mission)
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
                    <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900"><x-heroicon-o-flag class="h-6 w-6 text-brand-600" /> Visi &amp; Misi</h2>
                    <div class="mt-4 whitespace-pre-line leading-relaxed text-slate-600">{{ $program->vision_mission }}</div>
                </div>
            @endif

            {{-- Lecturers --}}
            <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
                <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900"><x-heroicon-o-users class="h-6 w-6 text-brand-600" /> Dosen Pengampu <span class="text-base font-normal text-slate-400">({{ $program->lecturers->count() }})</span></h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @forelse ($program->lecturers as $lecturer)
                        <div class="flex items-center gap-4 rounded-2xl bg-slate-50 p-4">
                            @if ($lecturer->photo)
                                <img src="{{ Storage::disk('public')->url($lecturer->photo) }}" alt="{{ $lecturer->name }}" class="h-14 w-14 rounded-full object-cover ring-2 ring-white">
                            @else
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-700 text-lg font-bold text-white">{{ Str::upper(Str::substr($lecturer->name, 0, 1)) }}</div>
                            @endif
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-800">{{ $lecturer->name }}{{ $lecturer->title ? ', ' . $lecturer->title : '' }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $lecturer->expertise }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Belum ada data dosen.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-6">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Informasi</h3>
                <dl class="mt-4 space-y-4 text-sm">
                    <div class="flex items-center justify-between"><dt class="text-slate-500">Jenjang</dt><dd class="font-semibold text-slate-800">{{ $program->degree }}</dd></div>
                    <div class="flex items-center justify-between"><dt class="text-slate-500">Akreditasi</dt><dd><span class="rounded-full bg-emerald-50 px-2.5 py-0.5 font-semibold text-emerald-700">{{ $program->accreditation }}</span></dd></div>
                    <div class="flex items-center justify-between"><dt class="text-slate-500">Institusi</dt><dd class="text-right font-semibold text-slate-800">STIE Nusantara Makassar</dd></div>
                    <div class="flex items-center justify-between"><dt class="text-slate-500">Jumlah Dosen</dt><dd class="font-semibold text-slate-800">{{ $program->lecturers->count() }}</dd></div>
                </dl>
            </div>

            <div class="rounded-3xl bg-gradient-to-br from-brand-600 to-brand-800 p-6 text-white shadow-sm">
                <h3 class="font-bold">Tertarik Mendaftar?</h3>
                <p class="mt-2 text-sm text-brand-100">Hubungi tim penerimaan mahasiswa baru untuk informasi lengkap.</p>
                <a href="/admin" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">Daftar Sekarang <x-heroicon-o-arrow-right class="h-4 w-4" /></a>
            </div>

            <a href="{{ route('programs.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700">
                <x-heroicon-o-arrow-left class="h-4 w-4" /> Semua Program Studi
            </a>
        </aside>
    </div>
</div>
