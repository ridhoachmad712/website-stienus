<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Berita')]
class PostIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public ?string $category = null;

    public function updating(string $name): void
    {
        if (in_array($name, ['search', 'category'], true)) {
            $this->resetPage();
        }
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategoriesProperty(): Collection
    {
        return Category::query()->orderBy('name')->get();
    }

    /**
     * @return LengthAwarePaginator<int, Post>
     */
    public function getPostsProperty(): LengthAwarePaginator
    {
        return Post::query()
            ->with('category')
            ->where('status', 'published')
            ->when($this->search, fn ($query) => $query->where('title', 'like', "%{$this->search}%"))
            ->when($this->category, fn ($query) => $query->whereHas('category', fn ($q) => $q->where('slug', $this->category)))
            ->latest()
            ->paginate(9);
    }

    public function render(): View
    {
        return view('livewire.public.post-index');
    }
}
