<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class PageShow extends Component
{
    public Page $page;

    public function mount(string $slug): void
    {
        $this->page = Page::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
    }

    public function render(): View
    {
        $description = $this->page->meta_description
            ?: Str::limit(strip_tags((string) $this->page->content), 160);

        return view('livewire.public.page-show')
            ->title($this->page->title)
            ->layout('components.layouts.app', [
                'metaDescription' => $description,
            ]);
    }
}
