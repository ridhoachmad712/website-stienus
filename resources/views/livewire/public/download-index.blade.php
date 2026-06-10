<div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Dokumen</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Pusat Unduhan</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">Unduh formulir, panduan, dan dokumen resmi STIE Nusantara Makassar.</p>
        </div>
    </section>

    <div class="container-page space-y-12 py-12">
        @forelse ($this->groups as $category => $items)
            <section>
                <h2 class="mb-5 flex items-center gap-2 text-xl font-bold text-slate-900">
                    <x-heroicon-o-folder class="h-6 w-6 text-brand-600" /> {{ $category }}
                </h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ($items as $item)
                        <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                <x-heroicon-o-document-text class="h-6 w-6" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate font-semibold text-slate-900">{{ $item->title }}</h3>
                                @if ($item->description)<p class="truncate text-sm text-slate-500">{{ $item->description }}</p>@endif
                                <p class="mt-0.5 text-xs text-slate-400">{{ number_format($item->downloads_count) }}x diunduh</p>
                            </div>
                            <a href="{{ route('downloads.file', $item) }}" target="_blank" class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-700">
                                <x-heroicon-o-arrow-down-tray class="h-4 w-4" /> Unduh
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="rounded-3xl bg-white py-20 text-center shadow-sm ring-1 ring-slate-100">
                <x-heroicon-o-folder-open class="mx-auto h-14 w-14 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Belum ada berkas</h3>
            </div>
        @endforelse
    </div>
</div>
