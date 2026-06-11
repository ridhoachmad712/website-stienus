<div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 py-16">
        <div class="container-page text-center text-white">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-200">Bantuan</p>
            <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">Pertanyaan Umum (FAQ)</h1>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">Jawaban atas pertanyaan yang sering diajukan seputar STIE Nusantara Makassar.</p>
        </div>
    </section>

    <div class="container-page max-w-3xl py-12">
        @forelse ($this->groups as $category => $items)
            <section class="mb-10">
                <h2 class="mb-4 text-lg font-bold text-slate-900">{{ $category }}</h2>
                <div class="space-y-3">
                    @foreach ($items as $faq)
                        <div x-data="{ open: false }" class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                            <button @click="open = !open" class="flex w-full items-center justify-between gap-4 p-5 text-left">
                                <span class="font-semibold text-slate-800">{{ $faq->question }}</span>
                                <x-heroicon-o-plus class="h-5 w-5 shrink-0 text-brand-600 transition" ::class="open && 'rotate-45'" />
                            </button>
                            <div x-show="open" x-collapse x-cloak class="border-t border-slate-100 px-5 py-4 text-slate-600">
                                <p class="whitespace-pre-line leading-relaxed">{{ $faq->answer }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="rounded-3xl bg-white py-20 text-center shadow-sm ring-1 ring-slate-100">
                <x-heroicon-o-question-mark-circle class="mx-auto h-14 w-14 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Belum ada FAQ</h3>
            </div>
        @endforelse
    </div>
</div>
