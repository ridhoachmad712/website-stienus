<div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Informasi Resmi</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Pengumuman</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">Pengumuman resmi dari STIE Nusantara Makassar.</p>
        </div>
    </section>

    <div class="container-page max-w-3xl space-y-5 py-12">
        @forelse ($this->announcements as $item)
            <article x-data="{ open: false }" class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                <button @click="open = !open" class="flex w-full items-center gap-4 p-5 text-left">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $item->is_pinned ? 'bg-amber-50 text-amber-600' : 'bg-brand-50 text-brand-600' }}">
                        <x-heroicon-o-megaphone class="h-6 w-6" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            @if ($item->is_pinned)<span class="rounded bg-amber-100 px-2 py-0.5 text-[10px] font-semibold uppercase text-amber-700">Penting</span>@endif
                            <h2 class="truncate font-bold text-slate-900">{{ $item->title }}</h2>
                        </div>
                        @if ($item->published_at)<p class="mt-0.5 text-xs text-slate-400">{{ $item->published_at->translatedFormat('d F Y') }}</p>@endif
                    </div>
                    <x-heroicon-o-chevron-down class="h-5 w-5 shrink-0 text-slate-400 transition" ::class="open && 'rotate-180'" />
                </button>
                <div x-show="open" x-collapse x-cloak class="border-t border-slate-100 px-5 py-4">
                    <div class="prose prose-sm max-w-none prose-a:text-brand-600">{!! $item->content !!}</div>
                </div>
            </article>
        @empty
            <div class="rounded-3xl bg-white py-20 text-center shadow-sm ring-1 ring-slate-100">
                <x-heroicon-o-megaphone class="mx-auto h-14 w-14 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Belum ada pengumuman</h3>
            </div>
        @endforelse
    </div>
</div>
