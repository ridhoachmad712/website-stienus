<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
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

    /** Honeypot anti-spam (harus tetap kosong). */
    public string $website = '';

    public bool $submitted = false;

    public function submit(): void
    {
        if (filled($this->website)) {
            $this->submitted = true;

            return;
        }

        $key = 'contact-submit:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('form', 'Terlalu banyak percobaan. Silakan coba lagi dalam '.RateLimiter::availableIn($key).' detik.');

            return;
        }
        RateLimiter::hit($key, 60);

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
