<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Agenda;
use App\Models\Lecturer;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Program;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Settings\HomeSettings;
use App\Settings\ThemeSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Beranda')]
class HomePage extends Component
{
    /**
     * @return Collection<int, Post>
     */
    public function getLatestPostsProperty(): Collection
    {
        return Post::query()
            ->with('category')
            ->where('status', 'published')
            ->latest()
            ->limit(7)
            ->get();
    }

    /**
     * @return Collection<int, Agenda>
     */
    public function getUpcomingAgendasProperty(): Collection
    {
        return Agenda::query()
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date')
            ->limit(4)
            ->get();
    }

    /**
     * @return Collection<int, Program>
     */
    public function getFeaturedProgramsProperty(): Collection
    {
        return Program::query()
            ->withCount('lecturers')
            ->orderBy('name')
            ->limit(6)
            ->get();
    }

    /**
     * @return array<string, int>
     */
    public function getStatsProperty(): array
    {
        return [
            'programs' => Program::count(),
            'lecturers' => Lecturer::count(),
            'posts' => Post::where('status', 'published')->count(),
            'agendas' => Agenda::count(),
        ];
    }

    public function getHomeProperty(): HomeSettings
    {
        return app(HomeSettings::class);
    }

    public function getHeroImageProperty(): ?string
    {
        return app(ThemeSettings::class)->hero_image;
    }

    /**
     * @return Collection<int, Slider>
     */
    public function getSlidesProperty(): Collection
    {
        return Slider::query()->where('is_active', true)->orderBy('order')->get();
    }

    /**
     * @return Collection<int, Testimonial>
     */
    public function getTestimonialsProperty(): Collection
    {
        return Testimonial::query()->where('is_active', true)->orderBy('order')->get();
    }

    /**
     * @return Collection<int, Partner>
     */
    public function getPartnersProperty(): Collection
    {
        return Partner::query()->orderBy('order')->get();
    }

    public function getVideoEmbedUrlProperty(): ?string
    {
        $url = $this->home->video_url;

        if (blank($url)) {
            return null;
        }

        // Extract the YouTube video id from common URL formats.
        preg_match('%(?:youtube\.com/(?:watch\?v=|embed/|v/)|youtu\.be/)([\w-]{11})%', $url, $m);

        return isset($m[1]) ? 'https://www.youtube.com/embed/'.$m[1] : null;
    }

    public function render(): View
    {
        return view('livewire.public.home-page');
    }
}
