<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Settings\ProfileSettings;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ProfilePage extends Component
{
    /** One of: about, history, leader, structure. */
    public string $section = 'about';

    public function mount(string $section = 'about'): void
    {
        $this->section = $section;
    }

    public function getProfileProperty(): ProfileSettings
    {
        return app(ProfileSettings::class);
    }

    /**
     * @return array<string, array{label: string, route: string}>
     */
    public function getMenuProperty(): array
    {
        return [
            'about' => ['label' => 'Tentang Kami', 'route' => 'profile'],
            'history' => ['label' => 'Sejarah', 'route' => 'profile.history'],
            'leader' => ['label' => 'Sambutan Pimpinan', 'route' => 'profile.leader'],
            'structure' => ['label' => 'Struktur Organisasi', 'route' => 'profile.structure'],
        ];
    }

    public function getTitleProperty(): string
    {
        return $this->menu[$this->section]['label'] ?? 'Profil';
    }

    public function render(): View
    {
        return view('livewire.public.profile-page')->title($this->title);
    }
}
