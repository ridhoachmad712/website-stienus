<?php

namespace Tests\Feature;

use App\Livewire\Public\ContactPage;
use App\Livewire\Public\PmbPage;
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
}
