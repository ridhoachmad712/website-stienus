<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\CustomForm;
use App\Models\FormSubmission;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class FormPage extends Component
{
    public CustomForm $form;

    /** @var array<string, mixed> */
    public array $data = [];

    /** Honeypot. */
    public string $website = '';

    public bool $submitted = false;

    public function mount(string $slug): void
    {
        $this->form = CustomForm::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        foreach ($this->fields() as $i => $field) {
            $this->data['field_'.$i] = $field['type'] === 'checkbox' ? [] : '';
        }
    }

    /**
     * Normalized field list.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fields(): array
    {
        return collect($this->form->fields ?? [])->map(fn (array $b): array => [
            'type' => $b['type'] ?? 'text',
            'label' => $b['data']['label'] ?? 'Field',
            'required' => (bool) ($b['data']['required'] ?? false),
            'options' => $b['data']['options'] ?? [],
        ])->all();
    }

    public function submit(): void
    {
        if (filled($this->website)) {
            $this->submitted = true;

            return;
        }

        $key = 'form-'.$this->form->id.':'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('form', 'Terlalu banyak percobaan. Coba lagi dalam '.RateLimiter::availableIn($key).' detik.');

            return;
        }

        $rules = [];
        $attributes = [];
        foreach ($this->fields() as $i => $field) {
            $k = 'data.field_'.$i;
            $base = $field['required'] ? ['required'] : ['nullable'];
            $rules[$k] = match ($field['type']) {
                'email' => array_merge($base, ['email', 'max:255']),
                'number' => array_merge($base, ['numeric']),
                'date' => array_merge($base, ['date']),
                'checkbox' => array_merge($base, ['array']),
                'select', 'radio' => array_merge($base, ['string', 'max:255']),
                default => array_merge($base, ['string', 'max:5000']),
            };
            $attributes[$k] = $field['label'];
        }

        $this->validate($rules, attributes: $attributes);
        RateLimiter::hit($key, 60);

        // Simpan dengan kunci label agar mudah dibaca di dashboard.
        $record = [];
        foreach ($this->fields() as $i => $field) {
            $value = $this->data['field_'.$i] ?? null;
            $record[$field['label']] = is_array($value) ? implode(', ', $value) : (string) $value;
        }

        FormSubmission::create([
            'custom_form_id' => $this->form->id,
            'data' => $record,
        ]);

        $this->submitted = true;
    }

    public function render(): View
    {
        return view('livewire.public.form-page')->title($this->form->title);
    }
}
