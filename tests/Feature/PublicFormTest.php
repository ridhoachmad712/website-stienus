<?php

namespace Tests\Feature;

use App\Livewire\Public\ContactPage;
use App\Livewire\Public\FormPage;
use App\Livewire\Public\PmbPage;
use App\Models\CustomForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PublicFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_saves_a_message(): void
    {
        $this->seed();

        Livewire::test(ContactPage::class)
            ->set('name', 'Budi')
            ->set('email', 'budi@example.com')
            ->set('subject', 'Pertanyaan')
            ->set('message', 'Halo, saya ingin bertanya.')
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('contact_messages', ['email' => 'budi@example.com']);
    }

    public function test_honeypot_blocks_spam_contact(): void
    {
        $this->seed();

        Livewire::test(ContactPage::class)
            ->set('website', 'http://spam.test')
            ->set('name', 'Bot')
            ->set('email', 'bot@example.com')
            ->set('subject', 'Spam')
            ->set('message', 'spam spam spam')
            ->call('submit');

        $this->assertDatabaseMissing('contact_messages', ['email' => 'bot@example.com']);
    }

    public function test_pmb_form_saves_an_applicant(): void
    {
        $this->seed();

        Livewire::test(PmbPage::class)
            ->set('name', 'Siti')
            ->set('email', 'siti@example.com')
            ->set('phone', '08123456789')
            ->set('program', 'Akuntansi')
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('applicants', ['email' => 'siti@example.com']);
    }

    public function test_custom_form_saves_submission(): void
    {
        $form = CustomForm::create([
            'title' => 'Tes Form',
            'slug' => 'tes-form',
            'is_active' => true,
            'fields' => [
                ['type' => 'text', 'data' => ['label' => 'Nama', 'required' => true]],
                ['type' => 'email', 'data' => ['label' => 'Email', 'required' => true]],
            ],
        ]);

        Livewire::test(FormPage::class, ['slug' => 'tes-form'])
            ->set('data.field_0', 'Andi')
            ->set('data.field_1', 'andi@example.com')
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('form_submissions', ['custom_form_id' => $form->id]);
        $this->assertSame('Andi', $form->submissions()->first()->data['Nama']);
    }
}
