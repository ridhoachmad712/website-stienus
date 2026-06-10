<div>
    @php $a = $this->admission; @endphp
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand-800 to-brand-950 py-20">
        <div class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-brand-500/30 blur-3xl"></div>
        <div class="container-page relative text-center text-white">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium text-brand-100 ring-1 ring-inset ring-white/20">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> PMB Dibuka
            </span>
            <h1 class="mt-6 text-4xl font-extrabold sm:text-5xl">{{ $a->headline }}</h1>
            <p class="mx-auto mt-4 max-w-2xl text-lg text-brand-100">{{ $a->subheadline }}</p>
            @if ($a->brochure)
                <a href="{{ Storage::disk('public')->url($a->brochure) }}" target="_blank" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-brand-700 shadow-lg transition hover:bg-brand-50">
                    <x-heroicon-o-arrow-down-tray class="h-4 w-4" /> Unduh Brosur
                </a>
            @endif
        </div>
    </section>

    <div class="container-page py-14">
        @if ($a->intro)
            <div class="prose prose-slate mx-auto mb-12 max-w-3xl text-center">{!! $a->intro !!}</div>
        @endif

        {{-- Steps --}}
        @if (! empty($a->steps))
            <h2 class="mb-8 text-center text-2xl font-bold text-slate-900">Alur Pendaftaran</h2>
            <div class="mb-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($a->steps as $i => $step)
                    <div class="relative rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-600 text-lg font-bold text-white">{{ $i + 1 }}</span>
                        <h3 class="mt-4 font-bold text-slate-900">{{ $step['title'] }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $step['description'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-5">
            {{-- Info --}}
            <div class="space-y-6 lg:col-span-2">
                @if ($a->schedule)
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                        <h3 class="flex items-center gap-2 font-bold text-slate-900"><x-heroicon-o-calendar-days class="h-5 w-5 text-brand-600" /> Jadwal</h3>
                        <div class="prose prose-sm mt-3 max-w-none text-slate-600">{!! $a->schedule !!}</div>
                    </div>
                @endif
                @if ($a->fee_info)
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                        <h3 class="flex items-center gap-2 font-bold text-slate-900"><x-heroicon-o-banknotes class="h-5 w-5 text-brand-600" /> Biaya</h3>
                        <div class="prose prose-sm mt-3 max-w-none text-slate-600">{!! $a->fee_info !!}</div>
                    </div>
                @endif
            </div>

            {{-- Form --}}
            <div class="lg:col-span-3">
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100" id="form">
                    <h2 class="text-2xl font-bold text-slate-900">Formulir Pendaftaran</h2>

                    @if (! $a->form_enabled)
                        <p class="mt-4 rounded-xl bg-amber-50 p-4 text-sm text-amber-700">Pendaftaran online sedang ditutup. Silakan hubungi panitia PMB.</p>
                    @elseif ($submitted)
                        <div class="mt-6 rounded-2xl bg-emerald-50 p-6 text-center">
                            <x-heroicon-o-check-circle class="mx-auto h-14 w-14 text-emerald-500" />
                            <h3 class="mt-3 text-lg font-bold text-emerald-800">Pendaftaran Terkirim!</h3>
                            <p class="mt-1 text-sm text-emerald-700">Terima kasih. Tim PMB kami akan segera menghubungi Anda.</p>
                            <button wire:click="$set('submitted', false)" class="mt-4 text-sm font-semibold text-brand-600 hover:underline">Daftar lagi</button>
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

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Nama Lengkap</label>
                                    <input type="text" wire:model="name" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                                    @error('name') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                                    <input type="email" wire:model="email" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                                    @error('email') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">No. WhatsApp</label>
                                    <input type="text" wire:model="phone" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                                    @error('phone') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Program Studi</label>
                                    <select wire:model="program" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                                        <option value="">— Pilih —</option>
                                        @foreach ($this->programs as $program)
                                            <option value="{{ $program->name }}">{{ $program->degree }} {{ $program->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('program') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Asal Sekolah</label>
                                <input type="text" wire:model="origin_school" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Alamat</label>
                                <textarea wire:model="address" rows="2" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500"></textarea>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Pesan (opsional)</label>
                                <textarea wire:model="message" rows="3" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500"></textarea>
                            </div>
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 disabled:opacity-60" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="submit">Kirim Pendaftaran</span>
                                <span wire:loading wire:target="submit">Mengirim...</span>
                                <x-heroicon-o-paper-airplane class="h-4 w-4" wire:loading.remove wire:target="submit" />
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
