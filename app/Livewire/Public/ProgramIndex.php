<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Program;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Program Studi')]
class ProgramIndex extends Component
{
    /**
     * @return Collection<int, Program>
     */
    public function getProgramsProperty(): Collection
    {
        return Program::query()
            ->withCount('lecturers')
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.public.program-index');
    }
}
