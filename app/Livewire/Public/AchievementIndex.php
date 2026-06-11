<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Achievement;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Prestasi')]
class AchievementIndex extends Component
{
    /**
     * @return Collection<int, Achievement>
     */
    public function getAchievementsProperty(): Collection
    {
        return Achievement::query()
            ->where('is_active', true)
            ->orderByRaw('"date" IS NULL')
            ->orderByDesc('date')
            ->orderBy('order')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.public.achievement-index');
    }
}
