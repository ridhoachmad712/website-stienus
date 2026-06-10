<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Kontak')]
class ContactPage extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:255')]
    public string $subject = '';

    #[Validate('required|string|max:2000')]
    public string $message = '';

    public bool $submitted = false;

    public function submit(): void
    {
        $validated = $this->validate();

        ContactMessage::create($validated);

        $this->reset(['name', 'email', 'subject', 'message']);
        $this->submitted = true;
    }

    public function render(): View
    {
        return view('livewire.public.contact-page');
    }
}
