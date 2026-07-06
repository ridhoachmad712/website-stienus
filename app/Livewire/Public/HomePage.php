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
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Beranda')]
class HomePage extends Component
{
    #[Computed]
    public function latestPosts(): Collection
    {
        return Post::query()
            ->with('category')
            ->where('status', 'published')
            ->latest()
            ->limit(7)
            ->get();
    }

    #[Computed]
    public function upcomingAgendas(): Collection
    {
        return Agenda::query()
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date')
            ->limit(4)
            ->get();
    }

    #[Computed]
    public function featuredPrograms(): Collection
    {
        return Program::query()
            ->withCount('lecturers')
            ->orderBy('name')
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function heroStats(): array
    {
        $custom = \App\Models\Stat::query()->where('is_active', true)->orderBy('order')->get();

        if ($custom->isNotEmpty()) {
            return $custom->map(fn (\App\Models\Stat $s): array => [
                'icon' => $s->icon ?: 'chart-bar',
                'value' => $s->value,
                'label' => $s->label,
                'numeric' => is_numeric($s->value),
            ])->all();
        }

        return [
            ['icon' => 'academic-cap', 'value' => (string) Program::count(), 'label' => 'Program Studi', 'numeric' => true],
            ['icon' => 'users', 'value' => (string) Lecturer::count(), 'label' => 'Dosen Ahli', 'numeric' => true],
            ['icon' => 'calendar-days', 'value' => (string) Agenda::count(), 'label' => 'Agenda', 'numeric' => true],
            ['icon' => 'newspaper', 'value' => (string) Post::where('status', 'published')->count(), 'label' => 'Publikasi', 'numeric' => true],
        ];
    }

    #[Computed]
    public function home(): HomeSettings
    {
        return app(HomeSettings::class);
    }

    #[Computed]
    public function heroImage(): ?string
    {
        return app(ThemeSettings::class)->hero_image;
    }

    #[Computed]
    public function heroSideImage(): ?string
    {
        $path = $this->home->hero_side_image;

        return $path ? \Illuminate\Support\Facades\Storage::disk('public')->url($path) : null;
    }

    #[Computed]
    public function slides(): Collection
    {
        return Slider::query()->where('is_active', true)->orderBy('order')->get();
    }

    #[Computed]
    public function leaders(): Collection
    {
        return \App\Models\Leader::query()->where('is_active', true)->orderBy('order')->get();
    }

    #[Computed]
    public function testimonials(): Collection
    {
        return Testimonial::query()->where('is_active', true)->orderBy('order')->get();
    }

    #[Computed]
    public function partners(): Collection
    {
        return Partner::query()->orderBy('order')->get();
    }

    #[Computed]
    public function videoEmbedUrl(): ?string
    {
        $url = $this->home->video_url;

        if (blank($url)) {
            return null;
        }

        preg_match('%(?:youtube\.com/(?:watch\?v=|embed/|v/)|youtu\.be/)([\w-]{11})%', $url, $m);

        return isset($m[1]) ? 'https://www.youtube.com/embed/'.$m[1] : null;
    }

    public function render(): View
    {
        return view('livewire.public.home-page');
    }
}
