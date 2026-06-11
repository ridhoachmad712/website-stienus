<div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page max-w-3xl text-center text-white">
            <h1 class="text-3xl font-extrabold sm:text-4xl">{{ $form->title }}</h1>
            @if ($form->description)<p class="mx-auto mt-4 max-w-xl text-brand-100">{{ $form->description }}</p>@endif
        </div>
    </section>

    <div class="container-page max-w-2xl py-12">
        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
            @if ($submitted)
                <div class="rounded-2xl bg-emerald-50 p-6 text-center">
                    <x-heroicon-o-check-circle class="mx-auto h-14 w-14 text-emerald-500" />
                    <h3 class="mt-3 text-lg font-bold text-emerald-800">Terkirim!</h3>
                    <p class="mt-1 text-sm text-emerald-700">{{ $form->success_message ?: 'Terima kasih, formulir Anda telah dikirim.' }}</p>
                    <button wire:click="$set('submitted', false)" class="mt-4 text-sm font-semibold text-brand-600 hover:underline">Isi lagi</button>
                </div>
            @elseif (count($this->fields()) === 0)
                <p class="text-center text-slate-400">Formulir ini belum memiliki field.</p>
            @else
                <form wire:submit="submit" class="space-y-5">
                    <div class="hidden" aria-hidden="true"><input type="text" wire:model="website" tabindex="-1" autocomplete="off"></div>

                    @error('form')<p class="rounded-xl bg-red-50 p-3 text-sm text-red-600">{{ $message }}</p>@enderror

                    @foreach ($this->fields() as $i => $field)
                        @php $key = 'data.field_'.$i; $id = 'field_'.$i; @endphp
                        <div>
                            <label for="{{ $id }}" class="mb-1 block text-sm font-medium text-slate-700">
                                {{ $field['label'] }}@if ($field['required'])<span class="text-red-500">*</span>@endif
                            </label>

                            @switch($field['type'])
                                @case('textarea')
                                    <textarea id="{{ $id }}" wire:model="{{ $key }}" rows="4" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500"></textarea>
                                    @break
                                @case('select')
                                    <select id="{{ $id }}" wire:model="{{ $key }}" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                                        <option value="">— Pilih —</option>
                                        @foreach ($field['options'] as $opt)<option value="{{ $opt }}">{{ $opt }}</option>@endforeach
                                    </select>
                                    @break
                                @case('radio')
                                    <div class="mt-1 space-y-2">
                                        @foreach ($field['options'] as $opt)
                                            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="radio" wire:model="{{ $key }}" value="{{ $opt }}" class="text-brand-600 focus:ring-brand-500"> {{ $opt }}</label>
                                        @endforeach
                                    </div>
                                    @break
                                @case('checkbox')
                                    <div class="mt-1 space-y-2">
                                        @foreach ($field['options'] as $opt)
                                            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" wire:model="{{ $key }}" value="{{ $opt }}" class="rounded text-brand-600 focus:ring-brand-500"> {{ $opt }}</label>
                                        @endforeach
                                    </div>
                                    @break
                                @case('email')
                                    <input type="email" id="{{ $id }}" wire:model="{{ $key }}" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                                    @break
                                @case('number')
                                    <input type="number" id="{{ $id }}" wire:model="{{ $key }}" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                                    @break
                                @case('phone')
                                    <input type="tel" id="{{ $id }}" wire:model="{{ $key }}" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                                    @break
                                @case('date')
                                    <input type="date" id="{{ $id }}" wire:model="{{ $key }}" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                                    @break
                                @default
                                    <input type="text" id="{{ $id }}" wire:model="{{ $key }}" class="w-full rounded-xl border-0 bg-slate-50 text-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-brand-500">
                            @endswitch

                            @error($key)<span class="mt-1 text-xs text-red-600">{{ $message }}</span>@enderror
                        </div>
                    @endforeach

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 disabled:opacity-60" wire:loading.attr="disabled" wire:target="submit">
                        <span wire:loading.remove wire:target="submit">Kirim</span>
                        <span wire:loading wire:target="submit">Mengirim...</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
