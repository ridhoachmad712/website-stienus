<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $today }}</p>
                <h2 class="mt-1 text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                    {{ $greeting }}, {{ $name }} 👋
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kelola konten dan informasi kampus dari satu tempat.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <x-filament::button tag="a" href="{{ \App\Filament\Resources\PostResource::getUrl('create') }}" icon="heroicon-o-plus" size="sm">
                    Tulis Berita
                </x-filament::button>
                <x-filament::button tag="a" href="{{ \App\Filament\Resources\ApplicantResource::getUrl() }}" icon="heroicon-o-clipboard-document-list" color="gray" size="sm">
                    Pendaftar
                </x-filament::button>
                <x-filament::button tag="a" href="{{ \App\Filament\Resources\ContactMessageResource::getUrl() }}" icon="heroicon-o-inbox-arrow-down" color="gray" size="sm">
                    Pesan Masuk
                </x-filament::button>
                <x-filament::button tag="a" href="{{ url('/') }}" target="_blank" icon="heroicon-o-arrow-top-right-on-square" color="gray" size="sm" outlined>
                    Lihat Situs
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
