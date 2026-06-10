<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Agenda;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class AgendaShow extends Component
{
    public Agenda $agenda;

    public function mount(Agenda $agenda): void
    {
        $this->agenda = $agenda;
    }

    public function render(): View
    {
        return view('livewire.public.agenda-show')
            ->title($this->agenda->title);
    }
}
