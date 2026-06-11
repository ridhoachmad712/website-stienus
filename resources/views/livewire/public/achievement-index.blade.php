<div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Kebanggaan Kami</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Prestasi</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">Capaian membanggakan mahasiswa, dosen, dan institusi STIE Nusantara Makassar.</p>
        </div>
    </section>

    <div class="container-page py-12">
        @if ($this->achievements->isNotEmpty())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->achievements as $item)
                    <article class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-44 overflow-hidden bg-gradient-to-br from-amber-400 to-amber-600">
                            @if ($item->image)
                                <img src="{{ Storage::disk('public')->url($item->image) }}" alt="{{ $item->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center"><x-heroicon-o-trophy class="h-16 w-16 text-white/50" /></div>
                            @endif
                            @if ($item->category)
                                <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-amber-700 backdrop-blur">{{ $item->category }}</span>
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h2 class="font-bold leading-snug text-slate-900">{{ $item->title }}</h2>
                            @if ($item->description)
                                <p class="mt-2 line-clamp-3 flex-1 text-sm text-slate-500">{{ $item->description }}</p>
                            @endif
                            @if ($item->date)
                                <p class="mt-3 flex items-center gap-1.5 text-xs text-slate-400"><x-heroicon-o-calendar class="h-4 w-4" />{{ $item->date->translatedFormat('d F Y') }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-3xl bg-white py-20 text-center shadow-sm ring-1 ring-slate-100">
                <x-heroicon-o-trophy class="mx-auto h-14 w-14 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Belum ada data prestasi</h3>
            </div>
        @endif
    </div>
</div>
