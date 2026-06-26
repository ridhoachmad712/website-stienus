<div>
    {{-- Header --}}
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Program Akademik</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Kurikulum</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">Struktur mata kuliah dan distribusi SKS per semester untuk setiap program studi.</p>
        </div>
    </section>

    <div class="container-page py-12">
        @if ($this->programs->isEmpty())
            <div class="rounded-3xl bg-white py-20 text-center shadow-sm ring-1 ring-slate-100">
                <x-heroicon-o-book-open class="mx-auto h-14 w-14 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Belum ada data kurikulum</h3>
            </div>
        @else
            {{-- Program Studi Tabs --}}
            <div class="mb-8">
                <h2 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Program Studi</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($this->programs as $prog)
                        <button
                            wire:click="selectProgram({{ $prog->id }})"
                            @class([
                                'inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition',
                                'bg-brand-600 text-white shadow-sm' => $this->program === $prog->id,
                                'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' => $this->program !== $prog->id,
                            ])
                        >
                            {{ $prog->name }}
                            @if ($prog->degree)
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-xs',
                                    'bg-white/20 text-white' => $this->program === $prog->id,
                                    'bg-slate-100 text-slate-500' => $this->program !== $prog->id,
                                ])>{{ $prog->degree }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            @if ($this->selectedProgram)
                {{-- Semester Tabs --}}
                @if (count($this->semesters) > 0)
                    <div class="mb-6">
                        <h2 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Semester</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($this->semesters as $smt)
                                <button
                                    wire:click="selectSemester({{ $smt }})"
                                    @class([
                                        'h-10 w-10 rounded-xl text-sm font-bold transition',
                                        'bg-brand-600 text-white shadow-sm' => $this->semester === $smt,
                                        'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' => $this->semester !== $smt,
                                    ])
                                >
                                    {{ $smt }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Mata Kuliah Table --}}
                <div data-reveal class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <div>
                            <h3 class="font-semibold text-slate-900">
                                {{ $this->selectedProgram->name }} — Semester {{ $this->semester }}
                            </h3>
                            <p class="mt-0.5 text-sm text-slate-500">{{ $this->mataKuliah->count() }} mata kuliah</p>
                        </div>
                        @if ($this->mataKuliah->isNotEmpty())
                            <div class="text-right">
                                <p class="text-xs text-slate-400">Total SKS</p>
                                <p class="text-2xl font-extrabold text-brand-600">{{ $this->totalSks }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($this->mataKuliah->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        <th class="px-6 py-3">No</th>
                                        <th class="px-6 py-3">Kode</th>
                                        <th class="px-6 py-3">Nama Mata Kuliah</th>
                                        <th class="px-6 py-3 text-center">SKS</th>
                                        <th class="px-6 py-3 text-center">Jenis</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($this->mataKuliah as $mk)
                                        <tr class="transition hover:bg-slate-50">
                                            <td class="px-6 py-4 text-slate-400">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $mk->kode ?: '—' }}</td>
                                            <td class="px-6 py-4 font-medium text-slate-800">{{ $mk->nama }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-brand-50 text-xs font-bold text-brand-700">{{ $mk->sks }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span @class([
                                                    'rounded-full px-3 py-1 text-xs font-semibold',
                                                    'bg-brand-50 text-brand-700' => $mk->jenis === 'Wajib',
                                                    'bg-amber-50 text-amber-700' => $mk->jenis === 'Pilihan',
                                                ])>{{ $mk->jenis }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-slate-50">
                                        <td colspan="3" class="px-6 py-3 text-right text-xs font-semibold text-slate-500">Total SKS Semester {{ $this->semester }}</td>
                                        <td class="px-6 py-3 text-center font-bold text-brand-700">{{ $this->totalSks }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="py-16 text-center">
                            <x-heroicon-o-book-open class="mx-auto h-12 w-12 text-slate-300" />
                            <p class="mt-3 text-sm text-slate-500">Belum ada mata kuliah untuk semester ini.</p>
                        </div>
                    @endif
                </div>
            @endif
        @endif
    </div>
</div>
