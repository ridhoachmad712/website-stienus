<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Staff;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Tenaga Kependidikan')]
class StaffIndex extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'unit')]
    public ?string $unit = null;

    /**
     * @return Collection<int, string>
     */
    public function getUnitsProperty(): Collection
    {
        return Staff::query()->whereNotNull('unit')->distinct()->orderBy('unit')->pluck('unit');
    }

    /**
     * @return Collection<int, Staff>
     */
    public function getStaffProperty(): Collection
    {
        return Staff::query()
            ->when($this->search, fn ($query) => $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('position', 'like', "%{$this->search}%")))
            ->when($this->unit, fn ($query) => $query->where('unit', $this->unit))
            ->orderBy('order')
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.public.staff-index');
    }
}
