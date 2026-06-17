<div x-data="{ open: false, src: '', caption: '' }">
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Dokumentasi</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Galeri</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">Momen dan kegiatan di lingkungan STIE Nusantara Makassar.</p>
        </div>
    </section>

    <div class="container-page py-12">
        {{-- Category filter --}}
        @if ($this->categories->isNotEmpty())
            <div class="mb-8 flex flex-wrap gap-2">
                <button wire:click="$set('category', null)" @class([
                    'rounded-full px-4 py-1.5 text-sm font-medium transition',
                    'bg-brand-600 text-white' => $category === null,
                    'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' => $category !== null,
                ])>Semua</button>
                @foreach ($this->categories as $cat)
                    <button wire:click="$set('category', '{{ $cat }}')" @class([
                        'rounded-full px-4 py-1.5 text-sm font-medium transition',
                        'bg-brand-600 text-white' => $category === $cat,
                        'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' => $category !== $cat,
                    ])>{{ $cat }}</button>
                @endforeach
            </div>
        @endif

        @if ($this->photos->isNotEmpty())
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($this->photos as $photo)
                    @php $url = $photo->image_url; @endphp
                    <button
                        @click="open = true; src = '{{ $url }}'; caption = @js($photo->title)"
                        data-reveal style="--reveal-delay: {{ ($loop->index % 4) * 70 }}ms"
                        class="group relative aspect-square overflow-hidden rounded-2xl bg-slate-100 shadow-sm ring-1 ring-slate-100 transition duration-300 ease-out hover:shadow-xl hover:shadow-brand-500/10"
                    >
                        <img src="{{ $url }}" alt="{{ $photo->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60 to-transparent p-3 opacity-0 transition group-hover:opacity-100">
                            <span class="text-left text-xs font-medium text-white">{{ $photo->title }}</span>
                        </div>
                    </button>
                @endforeach
            </div>
            <div class="mt-10">{{ $this->photos->links() }}</div>
        @else
            <div class="rounded-3xl bg-white py-20 text-center shadow-sm ring-1 ring-slate-100">
                <x-heroicon-o-photo class="mx-auto h-14 w-14 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Belum ada foto</h3>
            </div>
        @endif
    </div>

    {{-- Lightbox --}}
    <div x-show="open" x-cloak @keydown.escape.window="open = false" @click="open = false"
        class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 p-4" x-transition.opacity>
        <button @click="open = false" class="absolute right-5 top-5 text-white/80 hover:text-white"><x-heroicon-o-x-mark class="h-8 w-8" /></button>
        <figure @click.stop class="max-h-full max-w-4xl">
            <img :src="src" alt="" class="max-h-[80vh] w-auto rounded-2xl">
            <figcaption class="mt-3 text-center text-sm text-white/80" x-text="caption"></figcaption>
        </figure>
    </div>
</div>
