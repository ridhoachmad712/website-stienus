<div>
    @if ($page->banner_image)
        {{-- Hero dengan banner kustom --}}
        <section class="relative overflow-hidden">
            <img src="{{ Storage::disk('public')->url($page->banner_image) }}" alt="{{ $page->title }}" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-black/20"></div>
            <div class="container-page relative flex min-h-[300px] max-w-3xl flex-col justify-end py-12" style="text-shadow: 0 2px 14px rgba(0,0,0,0.5)">
                <nav class="mb-3 flex items-center gap-2 text-sm text-white/80">
                    <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                    <span>/</span>
                    <span>{{ $page->title }}</span>
                </nav>
                <h1 class="text-3xl font-extrabold leading-tight text-white sm:text-5xl">{{ $page->title }}</h1>
                @if ($page->subtitle)<p class="mt-3 max-w-2xl text-lg text-white/95">{{ $page->subtitle }}</p>@endif
            </div>
        </section>
    @else
        {{-- Hero gradient default --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 to-brand-950 py-16">
            <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full bg-brand-500/30 blur-3xl"></div>
            <div class="container-page relative max-w-3xl">
                <nav class="mb-6 flex items-center gap-2 text-sm text-brand-200">
                    <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                    <span>/</span>
                    <span class="text-white/80">{{ $page->title }}</span>
                </nav>
                <h1 class="text-3xl font-extrabold leading-tight text-white sm:text-4xl">{{ $page->title }}</h1>
                @if ($page->subtitle)<p class="mt-3 max-w-2xl text-lg text-brand-100">{{ $page->subtitle }}</p>@endif
            </div>
        </section>
    @endif

    @include('partials.page-blocks', ['blocks' => $page->blocks, 'fallback' => $page->content])
</div>
