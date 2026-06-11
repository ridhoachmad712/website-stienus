<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Announcement;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Pengumuman')]
class AnnouncementIndex extends Component
{
    /**
     * @return Collection<int, Announcement>
     */
    public function getAnnouncementsProperty(): Collection
    {
        return Announcement::query()
            ->where('is_active', true)
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.public.announcement-index');
    }
}
