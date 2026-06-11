@if (! empty($heading))
    <h2 class="mb-8 text-center text-3xl font-bold {{ $headTone }}">{{ $heading }}</h2>
@endif

@switch($source)
    @case('posts')
        @php $items = \App\Models\Post::with('category')->where('status', 'published')->latest()->limit(max(1, $limit))->get(); @endphp
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $post)
                <a href="{{ route('posts.show', $post->slug) }}" class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:shadow-lg">
                    <div class="relative h-40 bg-gradient-to-br from-brand-500 to-brand-800">
                        @if ($post->featured_image)<img src="{{ Storage::disk('public')->url($post->featured_image) }}" alt="" loading="lazy" class="h-full w-full object-cover">@else<div class="flex h-full items-center justify-center"><x-heroicon-o-newspaper class="h-12 w-12 text-white/40" /></div>@endif
                    </div>
                    <div class="p-5"><span class="text-xs font-semibold text-brand-600">{{ $post->category?->name }}</span><h3 class="mt-1 font-bold text-slate-900 group-hover:text-brand-700">{{ $post->title }}</h3></div>
                </a>
            @endforeach
        </div>
        @break

    @case('programs')
        @php $items = \App\Models\Program::withCount('lecturers')->orderBy('name')->get(); @endphp
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $program)
                <a href="{{ route('programs.show', $program->slug) }}" class="group rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600"><x-heroicon-o-academic-cap class="h-6 w-6" /></span>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $program->accreditation }}</span>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900 group-hover:text-brand-700">{{ $program->degree }} {{ $program->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $program->lecturers_count }} Dosen</p>
                </a>
            @endforeach
        </div>
        @break

    @case('testimonials')
        @php $items = \App\Models\Testimonial::where('is_active', true)->orderBy('order')->get(); @endphp
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $t)
                <figure class="flex flex-col rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <blockquote class="flex-1 text-sm leading-relaxed text-slate-600">"{{ $t->content }}"</blockquote>
                    <figcaption class="mt-4 flex items-center gap-3">
                        @if ($t->photo_url)<img src="{{ $t->photo_url }}" alt="" class="h-10 w-10 rounded-full object-cover">@else<div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">{{ \Illuminate\Support\Str::substr($t->name, 0, 1) }}</div>@endif
                        <div><p class="text-sm font-semibold text-slate-900">{{ $t->name }}</p><p class="text-xs text-slate-500">{{ $t->role }}</p></div>
                    </figcaption>
                </figure>
            @endforeach
        </div>
        @break

    @case('partners')
        @php $items = \App\Models\Partner::orderBy('order')->get(); @endphp
        <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-6">
            @foreach ($items as $partner)
                <a href="{{ $partner->url ?? '#' }}" @if($partner->url) target="_blank" rel="noopener" @endif class="grayscale transition hover:grayscale-0"><img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" loading="lazy" class="h-12 w-auto object-contain"></a>
            @endforeach
        </div>
        @break
@endswitch
