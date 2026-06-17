<div>
    {{-- Page header --}}
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Informasi</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Berita & Pengumuman</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">Ikuti perkembangan terbaru seputar kegiatan akademik, prestasi, dan informasi penting kampus.</p>
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
                    placeholder="Cari berita..."
                    class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-11 pr-4 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500"
                >
            </div>
            <select wire:model.live="category" class="rounded-xl border-0 bg-slate-50 py-2.5 pl-4 pr-10 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500 sm:w-56">
                <option value="">Semua Kategori</option>
                @foreach ($this->categories as $category)
                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Skeleton saat memfilter/mencari --}}
        <div wire:loading.delay wire:target="search,category">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <x-card-skeleton :count="6" />
            </div>
        </div>

        {{-- Grid --}}
        <div wire:loading.remove.delay wire:target="search,category">
        @if ($this->posts->isNotEmpty())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->posts as $post)
                    <article data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 90 }}ms" class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl hover:shadow-brand-500/10">
                        <a href="{{ route('posts.show', $post->slug) }}" class="relative block h-48 overflow-hidden bg-gradient-to-br from-brand-500 to-brand-800">
                            @if ($post->featured_image)
                                <img src="{{ Storage::disk('public')->url($post->featured_image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-110">
                            @else
                                <div class="flex h-full items-center justify-center"><x-heroicon-o-newspaper class="h-14 w-14 text-white/40" /></div>
                            @endif
                            <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-brand-700 backdrop-blur">{{ $post->category?->name }}</span>
                        </a>
                        <div class="flex flex-1 flex-col p-5">
                            <h2 class="font-bold leading-snug text-slate-900 transition group-hover:text-brand-700">
                                <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                            <p class="mt-2 line-clamp-2 flex-1 text-sm text-slate-500">{{ $post->summary }}</p>
                            <div class="mt-4 flex items-center gap-4 border-t border-slate-100 pt-3 text-xs text-slate-400">
                                <span class="flex items-center gap-1.5"><x-heroicon-o-calendar class="h-4 w-4" />{{ $post->created_at->translatedFormat('d M Y') }}</span>
                                <span class="flex items-center gap-1.5"><x-heroicon-o-eye class="h-4 w-4" />{{ number_format($post->views_count) }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">{{ $this->posts->links() }}</div>
        @else
            <div class="rounded-3xl bg-white py-20 text-center shadow-sm ring-1 ring-slate-100">
                <x-heroicon-o-document-magnifying-glass class="mx-auto h-14 w-14 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Tidak ada berita ditemukan</h3>
                <p class="mt-1 text-sm text-slate-400">Coba ubah kata kunci atau filter kategori.</p>
            </div>
        @endif
        </div>
    </div>
</div>
