<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('FAQ')]
class FaqPage extends Component
{
    /**
     * FAQ aktif dikelompokkan per kategori.
     *
     * @return Collection<string, Collection<int, Faq>>
     */
    public function getGroupsProperty(): Collection
    {
        return Faq::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->groupBy(fn (Faq $f): string => $f->category ?: 'Umum');
    }

    public function render(): View
    {
        return view('livewire.public.faq-page');
    }
}
