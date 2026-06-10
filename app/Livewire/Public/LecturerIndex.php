<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Lecturer;
use App\Models\Program;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Direktori Dosen')]
class LecturerIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'prodi')]
    public ?string $program = null;

    public function updating(string $name): void
    {
        if (in_array($name, ['search', 'program'], true)) {
            $this->resetPage();
        }
    }

    /**
     * @return Collection<int, Program>
     */
    public function getProgramsProperty(): Collection
    {
        return Program::query()->orderBy('name')->get();
    }

    /**
     * @return LengthAwarePaginator<int, Lecturer>
     */
    public function getLecturersProperty(): LengthAwarePaginator
    {
        return Lecturer::query()
            ->with('program')
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('nidn', 'like', "%{$this->search}%"))
            ->when($this->program, fn ($query) => $query->whereHas('program', fn ($q) => $q->where('slug', $this->program)))
            ->orderBy('name')
            ->paginate(12);
    }

    public function render(): View
    {
        return view('livewire.public.lecturer-index');
    }
}
