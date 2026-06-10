<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Program;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class ProgramShow extends Component
{
    public Program $program;

    public function mount(string $slug): void
    {
        $this->program = Program::query()
            ->with('lecturers')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function render(): View
    {
        $description = $this->program->vision_mission
            ? Str::limit(strip_tags($this->program->vision_mission), 160)
            : "Program Studi {$this->program->degree} {$this->program->name} di STIE Nusantara Makassar.";

        return view('livewire.public.program-show')
            ->title($this->program->name)
            ->layout('components.layouts.app', [
                'metaDescription' => $description,
                'ogImageUrl' => $this->program->profile_image
                    ? Storage::disk('public')->url($this->program->profile_image)
                    : null,
            ]);
    }
}
