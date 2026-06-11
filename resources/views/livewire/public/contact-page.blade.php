<div>
    @php $g = app(\App\Settings\GeneralSettings::class); @endphp
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Hubungi Kami</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Kontak</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">Punya pertanyaan? Kirimkan pesan atau kunjungi kampus kami.</p>
        </div>
    </section>

    <div class="container-page grid gap-8 py-12 lg:grid-cols-2">
        {{-- Info + map --}}
        <div class="space-y-6">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <x-heroicon-o-map-pin class="h-7 w-7 text-brand-600" />
                    <h3 class="mt-3 font-semibold text-slate-900">Alamat</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $g->address }}</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <x-heroicon-o-phone class="h-7 w-7 text-brand-600" />
                    <h3 class="mt-3 font-semibold text-slate-900">Telepon</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $g->phone }}</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <x-heroicon-o-envelope class="h-7 w-7 text-brand-600" />
                    <h3 class="mt-3 font-semibold text-slate-900">Email</h3>
                    <p class="mt-1 break-all text-sm text-slate-500">{{ $g->email }}</p>
                </div>
                @if ($g->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $g->whatsapp) }}" target="_blank" class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:ring-emerald-300">
                        <x-heroicon-o-chat-bubble-left-right class="h-7 w-7 text-emerald-600" />
                        <h3 class="mt-3 font-semibold text-slate-900">WhatsApp</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $g->whatsapp }}</p>
                    </a>
                @endif
            </div>

            @if ($g->map_embed)
                <div class="overflow-hidden rounded-2xl shadow-sm ring-1 ring-slate-100">
                    <iframe src="{{ $g->map_embed }}" width="100%" height="280" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            @else
                <div class="flex h-48 items-center justify-center rounded-2xl bg-slate-100 text-sm text-slate-400">
                    Peta lokasi belum diatur (isi "Embed Peta" di Pengaturan Umum).
                </div>
            @endif
        </div>

        {{-- Form --}}
        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
            <h2 class="text-2xl font-bold text-slate-900">Kirim Pesan</h2>
            @if ($submitted)
                <div class="mt-6 rounded-2xl bg-emerald-50 p-6 text-center">
                    <x-heroicon-o-check-circle class="mx-auto h-14 w-14 text-emerald-500" />
                    <h3 class="mt-3 text-lg font-bold text-emerald-800">Pesan Terkirim!</h3>
                    <p class="mt-1 text-sm text-emerald-700">Terima kasih telah menghubungi kami.</p>
                    <button wire:click="$set('submitted', false)" class="mt-4 text-sm font-semibold text-brand-600 hover:underline">Kirim pesan lain</button>
                </div>
            @else
                <form wire:submit="submit" class="mt-6 space-y-4">
                    {{-- Honeypot anti-spam (disembunyikan dari pengguna) --}}
                    <div class="hidden" aria-hidden="true">
                        <label>Website</label>
                        <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
                    </div>

                    @error('form')
                        <p class="rounded-xl bg-red-50 p-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nama</label>
                        <input type="text" wire:model="name" class="w-full rounded-xl border-0 bg-slate-50 px-4 py-3 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                        @error('name') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" wire:model="email" class="w-full rounded-xl border-0 bg-slate-50 px-4 py-3 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                        @error('email') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Subjek</label>
                        <input type="text" wire:model="subject" class="w-full rounded-xl border-0 bg-slate-50 px-4 py-3 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                        @error('subject') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Pesan</label>
                        <textarea wire:model="message" rows="5" class="w-full rounded-xl border-0 bg-slate-50 px-4 py-3 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500"></textarea>
                        @error('message') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 disabled:opacity-60" wire:loading.attr="disabled" wire:target="submit">
                        <span wire:loading.remove wire:target="submit">Kirim Pesan</span>
                        <span wire:loading wire:target="submit">Mengirim...</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
