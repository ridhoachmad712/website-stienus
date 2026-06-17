<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class PostShow extends Component
{
    public Post $post;

    public function mount(string $slug): void
    {
        $this->post = Post::query()
            ->with('category')
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment the view counter without touching the updated_at timestamp.
        $this->post->incrementQuietly('views_count');
    }

    /**
     * Related posts from the same category.
     *
     * @return Collection<int, Post>
     */
    public function getRelatedProperty(): Collection
    {
        return Post::query()
            ->where('status', 'published')
            ->where('category_id', $this->post->category_id)
            ->whereKeyNot($this->post->getKey())
            ->latest()
            ->limit(3)
            ->get();
    }

    public function render(): View
    {
        $image = $this->post->featured_image
            ? Storage::disk('public')->url($this->post->featured_image)
            : null;

        return view('livewire.public.post-show')
            ->title($this->post->title)
            ->layout('components.layouts.app', [
                'metaDescription' => $this->post->summary,
                'ogImageUrl' => $image,
                'ogType' => 'article',
                'jsonLd' => array_filter([
                    '@context' => 'https://schema.org',
                    '@type' => 'NewsArticle',
                    'headline' => $this->post->title,
                    'description' => $this->post->summary,
                    'image' => $image,
                    'datePublished' => $this->post->created_at?->toIso8601String(),
                    'dateModified' => $this->post->updated_at?->toIso8601String(),
                    'articleSection' => $this->post->category?->name,
                    'mainEntityOfPage' => url()->current(),
                ]),
            ]);
    }
}
