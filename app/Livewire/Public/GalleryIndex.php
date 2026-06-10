<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Gallery;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Galeri')]
class GalleryIndex extends Component
{
    use WithPagination;

    #[Url(as: 'kategori')]
    public ?string $category = null;

    public function updating(string $name): void
    {
        if ($name === 'category') {
            $this->resetPage();
        }
    }

    /**
     * @return Collection<int, string>
     */
    public function getCategoriesProperty(): Collection
    {
        return Gallery::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
    }

    /**
     * @return LengthAwarePaginator<int, Gallery>
     */
    public function getPhotosProperty(): LengthAwarePaginator
    {
        return Gallery::query()
            ->when($this->category, fn ($query) => $query->where('category', $this->category))
            ->latest()
            ->paginate(12);
    }

    public function render(): View
    {
        return view('livewire.public.gallery-index');
    }
}
