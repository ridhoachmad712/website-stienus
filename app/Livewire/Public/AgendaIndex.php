<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Agenda;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Agenda')]
class AgendaIndex extends Component
{
    use WithPagination;

    /**
     * @return Collection<int, Agenda>
     */
    public function getUpcomingProperty(): Collection
    {
        return Agenda::query()
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date')
            ->get();
    }

    /**
     * @return LengthAwarePaginator<int, Agenda>
     */
    public function getPastProperty(): LengthAwarePaginator
    {
        return Agenda::query()
            ->whereDate('event_date', '<', now())
            ->orderByDesc('event_date')
            ->paginate(6);
    }

    public function render(): View
    {
        return view('livewire.public.agenda-index');
    }
}
