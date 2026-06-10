<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Download;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Pusat Unduhan')]
class DownloadIndex extends Component
{
    /**
     * Downloads grouped by category.
     *
     * @return Collection<string, Collection<int, Download>>
     */
    public function getGroupsProperty(): Collection
    {
        return Download::query()
            ->latest()
            ->get()
            ->groupBy(fn (Download $d): string => $d->category ?: 'Lainnya');
    }

    public function render(): View
    {
        return view('livewire.public.download-index');
    }
}
