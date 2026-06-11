<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Lecturer;
use App\Models\Program;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Direktori Dosen')]
class LecturerIndex extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'prodi')]
    public ?string $program = null;

    /**
     * @return Collection<int, Program>
     */
    public function getProgramsProperty(): Collection
    {
        return Program::query()->orderBy('name')->get();
    }

    /**
     * Semua dosen (tanpa pagination), terurut sesuai pengaturan admin.
     *
     * @return Collection<int, Lecturer>
     */
    public function getLecturersProperty(): Collection
    {
        return Lecturer::query()
            ->with('program')
            ->when($this->search, fn ($query) => $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('nidn', 'like', "%{$this->search}%")))
            ->when($this->program, fn ($query) => $query->whereHas('program', fn ($q) => $q->where('slug', $this->program)))
            ->orderBy('order')
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.public.lecturer-index');
    }
}
