<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Applicant;
use App\Models\Program;
use App\Settings\AdmissionSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Penerimaan Mahasiswa Baru')]
class PmbPage extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:30')]
    public string $phone = '';

    #[Validate('required|string|max:255')]
    public string $program = '';

    #[Validate('nullable|string|max:255')]
    public string $origin_school = '';

    #[Validate('nullable|string|max:1000')]
    public string $address = '';

    #[Validate('nullable|string|max:2000')]
    public string $message = '';

    public bool $submitted = false;

    public function getAdmissionProperty(): AdmissionSettings
    {
        return app(AdmissionSettings::class);
    }

    /**
     * @return Collection<int, Program>
     */
    public function getProgramsProperty(): Collection
    {
        return Program::query()->orderBy('name')->get();
    }

    public function submit(): void
    {
        $validated = $this->validate();

        Applicant::create($validated);

        $this->reset(['name', 'email', 'phone', 'program', 'origin_school', 'address', 'message']);
        $this->submitted = true;
    }

    public function render(): View
    {
        return view('livewire.public.pmb-page');
    }
}
