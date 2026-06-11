<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Lecturer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class LecturerShow extends Component
{
    public Lecturer $lecturer;

    public function mount(Lecturer $lecturer): void
    {
        $this->lecturer = $lecturer->load('program');
    }

    public function render(): View
    {
        $desc = $this->lecturer->bio
            ? Str::limit(strip_tags($this->lecturer->bio), 160)
            : trim($this->lecturer->name.' — '.($this->lecturer->expertise ?: 'Dosen').' di '.($this->lecturer->program?->name ?? 'STIE Nusantara Makassar'));

        return view('livewire.public.lecturer-show')
            ->title($this->lecturer->name)
            ->layout('components.layouts.app', [
                'metaDescription' => $desc,
                'ogImageUrl' => $this->lecturer->photo
                    ? Storage::disk('public')->url($this->lecturer->photo)
                    : null,
                'ogType' => 'profile',
            ]);
    }
}
