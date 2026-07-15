<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $pageTitle = isset($title) ? $title . ' — ' . $general->site_name : $general->site_name;
        $metaDesc = ($metaDescription ?? null) ?: ('Portal informasi resmi ' . $general->site_full_name . ' — berita, agenda, program studi, dan direktori dosen.');
        $ogImage = ($ogImageUrl ?? null) ?: ($general->logo ? Storage::disk('public')->url($general->logo) : null);
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:site_name" content="{{ $general->site_name }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($ogImage)<meta property="og:image" content="{{ $ogImage }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    @if ($ogImage)<meta name="twitter:image" content="{{ $ogImage }}">@endif

    {{-- Structured data (JSON-LD): identitas institusi untuk hasil pencarian Google. --}}
    @php
        $orgSchema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'CollegeOrUniversity',
            'name' => $general->site_full_name ?: $general->site_name,
            'alternateName' => $general->site_full_name ? $general->site_name : null,
            'url' => url('/'),
            'logo' => $ogImage,
            'description' => $metaDesc,
            'telephone' => $general->phone ?: null,
            'email' => $general->email ?: null,
            'address' => $general->address ? [
                '@type' => 'PostalAddress',
                'streetAddress' => $general->address,
            ] : null,
            'sameAs' => array_values(array_filter([
                $general->social_facebook,
                $general->social_instagram,
                $general->social_youtube,
                $general->social_x,
            ])),
        ]);
        $siteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $general->site_name,
            'url' => url('/'),
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($siteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @isset($jsonLd)
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endisset

    @if ($general->favicon)
        <link rel="icon" href="{{ Storage::disk('public')->url($general->favicon) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:ital,wdth,wght@0,75..125,100..700;1,75..125,100..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- Runtime brand color override: maps the admin-chosen primary color onto the
         Tailwind v4 --color-brand-* CSS variables, so all utilities update live. --}}
    @php $c = $theme->primary_color ?: '#4f46e5'; @endphp
    <style>
        :root {
            --color-brand-50: color-mix(in srgb, {{ $c }} 8%, white);
            --color-brand-100: color-mix(in srgb, {{ $c }} 16%, white);
            --color-brand-200: color-mix(in srgb, {{ $c }} 30%, white);
            --color-brand-300: color-mix(in srgb, {{ $c }} 45%, white);
            --color-brand-400: color-mix(in srgb, {{ $c }} 70%, white);
            --color-brand-500: color-mix(in srgb, {{ $c }} 88%, white);
            --color-brand-600: {{ $c }};
            --color-brand-700: color-mix(in srgb, {{ $c }} 85%, black);
            --color-brand-800: color-mix(in srgb, {{ $c }} 70%, black);
            --color-brand-900: color-mix(in srgb, {{ $c }} 55%, black);
            --color-brand-950: color-mix(in srgb, {{ $c }} 40%, black);
        }
    </style>

    {{-- Jaring pengaman: bila JavaScript mati, jangan sembunyikan konten reveal. --}}
    <noscript>
        <style>[data-reveal] { opacity: 1 !important; transform: none !important; }</style>
    </noscript>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-700 antialiased">
    @php
        // Navigasi dari database (dikelola admin via Menu Navigasi).
        $menuLinks = $menuLinks ?? collect();
        $menuButtons = $menuButtons ?? collect();

        $currentPath = '/'.trim(request()->path(), '/');
        $isActive = function (?string $url) use ($currentPath): bool {
            if (blank($url) || ! str_starts_with($url, '/')) {
                return false;
            }
            $url = '/'.trim($url, '/');
            return $url === '/'
                ? $currentPath === '/'
                : ($currentPath === $url || str_starts_with($currentPath, $url.'/'));
        };
        $isParentActive = fn ($item): bool => $item->children->contains(fn ($c) => $isActive($c->url)) || $isActive($item->url);

        // Tautan footer: ratakan item daun (anak dropdown, atau item ber-URL).
        $footerLinks = collect();
        foreach ($menuLinks as $item) {
            if ($item->children->isNotEmpty()) {
                foreach ($item->children as $c) {
                    $footerLinks->push($c);
                }
            } elseif (filled($item->url)) {
                $footerLinks->push($item);
            }
        }
        foreach ($menuButtons as $b) {
            if (filled($b->url)) {
                $footerLinks->push($b);
            }
        }

        $socials = array_filter([
            'Facebook' => $general->social_facebook,
            'Instagram' => $general->social_instagram,
            'YouTube' => $general->social_youtube,
            'X' => $general->social_x,
        ]);
    @endphp

    @if ($general->announcement_enabled && filled($general->announcement_text))
        <a href="{{ $general->announcement_url ?: '#' }}" class="block bg-brand-700 py-2 text-center text-sm font-medium text-white transition hover:bg-brand-800">
            <span class="container-page flex items-center justify-center gap-2">
                <x-heroicon-s-megaphone class="h-4 w-4 shrink-0" />
                {{ $general->announcement_text }}
            </span>
        </a>
    @endif

    <header
        x-data="{ open: false, scrolled: false }"
        @scroll.window="scrolled = window.pageYOffset > 10"
        class="sticky top-0 z-50 transition-all"
        :class="scrolled ? 'bg-white/90 shadow-sm backdrop-blur' : 'bg-white'"
    >
        <nav class="container-page flex h-16 items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                @if ($general->logo)
                    <img src="{{ Storage::disk('public')->url($general->logo) }}" alt="{{ $general->site_name }}" class="h-10 w-auto">
                @else
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 text-white shadow-sm">
                        <x-heroicon-s-academic-cap class="h-5 w-5" />
                    </span>
                    <span class="text-sm font-bold leading-tight text-slate-900">{{ $general->site_name }}</span>
                @endif
            </a>

            {{-- Desktop nav --}}
            <div class="hidden items-center gap-0.5 lg:flex">
                @foreach ($menuLinks as $item)
                    @if ($item->children->isNotEmpty())
                        {{-- dropdown --}}
                        <div class="relative" x-data="{ o: false }" @mouseenter="o = true" @mouseleave="o = false">
                            <button @class(['flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition', 'text-brand-700 bg-brand-50' => $isParentActive($item), 'text-slate-600 hover:bg-slate-100' => ! $isParentActive($item)])>
                                {{ $item->label }} <x-heroicon-o-chevron-down class="h-4 w-4" />
                            </button>
                            <div x-show="o" x-cloak x-transition class="absolute left-0 top-full w-56 pt-2">
                                <div class="overflow-hidden rounded-xl bg-white py-1 shadow-lg ring-1 ring-slate-100">
                                    @foreach ($item->children as $child)
                                        <a href="{{ $child->url ?: '#' }}" @if ($child->open_in_new_tab) target="_blank" rel="noopener" @endif @class(['block px-4 py-2.5 text-sm hover:bg-brand-50 hover:text-brand-700', 'text-brand-700 bg-brand-50' => $isActive($child->url), 'text-slate-600' => ! $isActive($child->url)])>{{ $child->label }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $item->url ?: '#' }}" @if ($item->open_in_new_tab) target="_blank" rel="noopener" @endif @class(['rounded-lg px-3 py-2 text-sm font-medium transition', 'text-brand-700 bg-brand-50' => $isActive($item->url), 'text-slate-600 hover:bg-slate-100' => ! $isActive($item->url)])>{{ $item->label }}</a>
                    @endif
                @endforeach

                @foreach ($menuButtons as $btn)
                    <a href="{{ $btn->url ?: '#' }}" @if ($btn->open_in_new_tab) target="_blank" rel="noopener" @endif class="ml-2 inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                        {{ $btn->label }} <x-heroicon-o-arrow-right class="h-4 w-4" />
                    </a>
                @endforeach
            </div>

            {{-- Mobile toggle --}}
            <button @click="open = !open" class="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden">
                <x-heroicon-o-bars-3 x-show="!open" class="h-6 w-6" />
                <x-heroicon-o-x-mark x-show="open" x-cloak class="h-6 w-6" />
            </button>
        </nav>

        {{-- Mobile menu --}}
        <div x-show="open" x-cloak x-transition class="max-h-[80vh] overflow-y-auto border-t border-slate-100 bg-white lg:hidden">
            <div class="container-page space-y-1 py-3">
                @foreach ($menuLinks as $item)
                    @if ($item->children->isNotEmpty())
                        <p class="px-4 pt-3 text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $item->label }}</p>
                        @foreach ($item->children as $child)
                            <a href="{{ $child->url ?: '#' }}" @if ($child->open_in_new_tab) target="_blank" rel="noopener" @endif class="block rounded-lg px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-100">{{ $child->label }}</a>
                        @endforeach
                    @else
                        <a href="{{ $item->url ?: '#' }}" @if ($item->open_in_new_tab) target="_blank" rel="noopener" @endif class="block rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100">{{ $item->label }}</a>
                    @endif
                @endforeach
                @foreach ($menuButtons as $btn)
                    <a href="{{ $btn->url ?: '#' }}" @if ($btn->open_in_new_tab) target="_blank" rel="noopener" @endif class="mt-2 block rounded-lg bg-brand-600 px-4 py-2.5 text-center text-sm font-semibold text-white">{{ $btn->label }}</a>
                @endforeach
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    {{-- Floating WhatsApp button --}}
    @if ($general->whatsapp)
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $general->whatsapp) }}" target="_blank" rel="noopener"
            class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg shadow-emerald-500/40 transition hover:scale-110 hover:bg-emerald-600"
            title="Chat WhatsApp" aria-label="Chat WhatsApp">
            <x-heroicon-s-chat-bubble-oval-left-ellipsis class="h-7 w-7" />
        </a>
    @endif

    @php
        $footerCols = collect();
        if ($footer->show_description) $footerCols->push('description');
        if ($footer->show_links_column) $footerCols->push('links');
        if ($footer->show_contact_column) $footerCols->push('contact');
        if ($footer->show_social_column) $footerCols->push('social');
        foreach ($footer->extra_columns as $ec) $footerCols->push('extra_'.$loop->index ?? uniqid());
        $totalCols = $footerCols->count() + count($footer->extra_columns);
        // grid kolom berdasarkan jumlah kolom aktif
        $visibleCount = ($footer->show_description ? 1 : 0)
            + ($footer->show_links_column ? 1 : 0)
            + ($footer->show_contact_column ? 1 : 0)
            + ($footer->show_social_column ? 1 : 0)
            + count($footer->extra_columns);
        $gridClass = match(true) {
            $visibleCount >= 4 => 'md:grid-cols-2 lg:grid-cols-4',
            $visibleCount === 3 => 'md:grid-cols-3',
            $visibleCount === 2 => 'md:grid-cols-2',
            default => 'md:grid-cols-1',
        };

        $socialIcons = [
            'Facebook'  => 'heroicon-s-user-group',
            'Instagram' => 'heroicon-s-camera',
            'YouTube'   => 'heroicon-s-play-circle',
            'X'         => 'heroicon-s-chat-bubble-left-right',
        ];
    @endphp

    <footer class="mt-20 bg-slate-900 text-slate-300">
        <div class="container-page grid gap-10 py-14 {{ $gridClass }}">

            {{-- Kolom deskripsi --}}
            @if ($footer->show_description)
                <div>
                    <div class="flex items-center gap-2.5">
                        @if ($general->logo)
                            <img src="{{ Storage::disk('public')->url($general->logo) }}" alt="{{ $general->site_name }}" class="h-10 w-auto brightness-0 invert">
                        @else
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white">
                                <x-heroicon-s-academic-cap class="h-5 w-5" />
                            </span>
                            <span class="font-bold text-white">{{ $general->site_name }}</span>
                        @endif
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-400">{{ $general->footer_description }}</p>
                </div>
            @endif

            {{-- Kolom tautan --}}
            @if ($footer->show_links_column)
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-white">{{ $footer->links_column_title }}</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        @if ($footer->use_custom_links)
                            @foreach ($footer->custom_links as $link)
                                <li><a href="{{ $link['url'] }}" class="text-slate-400 transition hover:text-white">{{ $link['label'] }}</a></li>
                            @endforeach
                        @else
                            @foreach ($footerLinks as $link)
                                <li><a href="{{ $link->url ?: '#' }}" @if ($link->open_in_new_tab) target="_blank" rel="noopener" @endif class="text-slate-400 transition hover:text-white">{{ $link->label }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            @endif

            {{-- Kolom kontak --}}
            @if ($footer->show_contact_column)
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-white">{{ $footer->contact_column_title }}</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-slate-400">
                        @if (filled($general->address))
                            <li class="flex items-start gap-2"><x-heroicon-o-map-pin class="mt-0.5 h-4 w-4 shrink-0" /> {{ $general->address }}</li>
                        @endif
                        @if (filled($general->phone))
                            <li class="flex items-center gap-2"><x-heroicon-o-phone class="h-4 w-4 shrink-0" /> {{ $general->phone }}</li>
                        @endif
                        @if (filled($general->email))
                            <li class="flex items-center gap-2"><x-heroicon-o-envelope class="h-4 w-4 shrink-0" /> {{ $general->email }}</li>
                        @endif
                    </ul>
                </div>
            @endif

            {{-- Kolom sosial media + login --}}
            @if ($footer->show_social_column)
                <div>
                    @if (! empty($socials))
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-white">{{ $footer->social_column_title }}</h3>
                        <div class="mt-4 flex flex-wrap gap-3">
                            @foreach ($socials as $name => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-800 text-slate-300 transition hover:bg-brand-600 hover:text-white"
                                    title="{{ $name }}">
                                    {{ substr($name, 0, 1) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <a href="/admin" class="mt-6 inline-flex items-center gap-1.5 text-xs text-slate-500 transition hover:text-slate-300">
                        <x-heroicon-o-lock-closed class="h-3.5 w-3.5" /> Login Dashboard
                    </a>
                </div>
            @endif

            {{-- Kolom tambahan --}}
            @foreach ($footer->extra_columns as $col)
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-white">{{ $col['title'] }}</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        @foreach ($col['links'] ?? [] as $link)
                            <li><a href="{{ $link['url'] }}" class="text-slate-400 transition hover:text-white">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

        </div>
        <div class="border-t border-slate-800">
            <div class="container-page py-5 text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} {{ $general->copyright_text }}
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
