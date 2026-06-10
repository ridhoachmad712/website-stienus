<div>
    {{-- Hero header --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 to-brand-950 py-16">
        <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full bg-brand-500/30 blur-3xl"></div>
        <div class="container-page relative max-w-3xl">
            <nav class="mb-6 flex items-center gap-2 text-sm text-brand-200">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span>/</span>
                <a href="{{ route('posts.index') }}" class="hover:text-white">Berita</a>
            </nav>
            <span class="inline-block rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white ring-1 ring-inset ring-white/20">{{ $post->category?->name }}</span>
            <h1 class="mt-4 text-3xl font-extrabold leading-tight text-white sm:text-4xl">{{ $post->title }}</h1>
            <div class="mt-5 flex flex-wrap items-center gap-5 text-sm text-brand-100">
                <span class="flex items-center gap-2"><x-heroicon-o-calendar class="h-4 w-4" />{{ $post->created_at->translatedFormat('d F Y') }}</span>
                <span class="flex items-center gap-2"><x-heroicon-o-eye class="h-4 w-4" />{{ number_format($post->views_count) }}x dilihat</span>
            </div>
        </div>
    </section>

    <div class="container-page max-w-3xl py-12">
        @if ($post->featured_image)
            <img src="{{ Storage::disk('public')->url($post->featured_image) }}" alt="{{ $post->title }}" class="mb-8 w-full rounded-3xl shadow-md">
        @endif

        <article class="prose prose-slate max-w-none prose-headings:font-bold prose-a:text-brand-600 prose-img:rounded-2xl">
            {!! $post->content !!}
        </article>

        {{-- Share --}}
        @php
            $shareUrl = urlencode(request()->fullUrl());
            $shareTitle = urlencode($post->title);
        @endphp
        <div class="mt-10 flex flex-wrap items-center gap-3 border-y border-slate-200 py-5"
            x-data="{ copied: false }">
            <span class="text-sm font-semibold text-slate-600">Bagikan:</span>
            <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition hover:bg-emerald-600 hover:text-white" title="WhatsApp"><x-heroicon-o-chat-bubble-left-right class="h-5 w-5" /></a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-600 hover:text-white" title="Facebook">f</a>
            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-700 transition hover:bg-slate-800 hover:text-white" title="X">𝕏</a>
            <button type="button" @click="navigator.clipboard.writeText('{{ request()->fullUrl() }}'); copied = true; setTimeout(() => copied = false, 2000)" class="flex h-9 items-center gap-1.5 rounded-lg bg-slate-100 px-3 text-sm text-slate-600 transition hover:bg-slate-200">
                <x-heroicon-o-link class="h-4 w-4" /> <span x-text="copied ? 'Tersalin!' : 'Salin tautan'"></span>
            </button>
        </div>

        {{-- Related --}}
        @if ($this->related->isNotEmpty())
            <div class="mt-12">
                <h2 class="mb-5 text-xl font-bold text-slate-900">Berita Terkait</h2>
                <div class="grid gap-5 sm:grid-cols-3">
                    @foreach ($this->related as $item)
                        <a href="{{ route('posts.show', $item->slug) }}" class="group rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100 transition hover:shadow-md">
                            <span class="text-xs font-semibold text-brand-600">{{ $item->category?->name }}</span>
                            <h3 class="mt-1 line-clamp-2 font-semibold text-slate-800 transition group-hover:text-brand-700">{{ $item->title }}</h3>
                            <p class="mt-2 text-xs text-slate-400">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-12 border-t border-slate-200 pt-6">
            <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                <x-heroicon-o-arrow-left class="h-4 w-4" /> Kembali ke Berita
            </a>
        </div>
    </div>
</div>
