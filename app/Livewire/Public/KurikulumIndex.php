<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\MataKuliah;
use App\Models\Program;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Kurikulum')]
class KurikulumIndex extends Component
{
    #[Url]
    public ?int $program = null;

    #[Url]
    public int $semester = 1;

    public function mount(): void
    {
        if (! $this->program) {
            $first = Program::query()->orderBy('name')->value('id');
            $this->program = $first;
        }
    }

    public function selectProgram(int $id): void
    {
        $this->program = $id;
        $this->semester = 1;
    }

    public function selectSemester(int $semester): void
    {
        $this->semester = $semester;
    }

    /**
     * @return Collection<int, Program>
     */
    public function getProgramsProperty(): Collection
    {
        return Program::query()->orderBy('name')->get(['id', 'name', 'degree']);
    }

    /**
     * @return Collection<int, MataKuliah>
     */
    public function getMataKuliahProperty(): Collection
    {
        if (! $this->program) {
            return collect();
        }

        return MataKuliah::query()
            ->where('program_id', $this->program)
            ->where('semester', $this->semester)
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('nama')
            ->get();
    }

    public function getSelectedProgramProperty(): ?Program
    {
        if (! $this->program) {
            return null;
        }

        return $this->programs->firstWhere('id', $this->program);
    }

    /**
     * @return array<int, int>
     */
    public function getSemestersProperty(): array
    {
        if (! $this->program) {
            return [];
        }

        return MataKuliah::query()
            ->where('program_id', $this->program)
            ->where('is_active', true)
            ->distinct()
            ->orderBy('semester')
            ->pluck('semester')
            ->all();
    }

    public function getTotalSksProperty(): int
    {
        return $this->mataKuliah->sum('sks');
    }

    public function render(): View
    {
        return view('livewire.public.kurikulum-index');
    }
}
