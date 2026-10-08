<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminFaqModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'FAQ Admin',
            'email' => 'faq-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_faq_list_is_searchable_paginated_and_links_to_edit(): void
    {
        $faq = Faq::create([
            'question' => 'How can I join the organisation?',
            'answer' => 'Submit the membership form.',
            'category' => 'Membership',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.faqs.index'))
            ->assertOk()
            ->assertSee('css/pages/admin-faqs.css')
            ->assertSee('js/pages/admin-faqs.js')
            ->assertSee('Search this FAQ page')
            ->assertSee('How can I join the organisation?')
            ->assertSee(route('admin.faqs.edit', $faq->id));
    }

    public function test_invalid_create_input_is_preserved_and_associated_with_fields(): void
    {
        $this->actingAs($this->admin)
            ->from(route('admin.faqs.index'))
            ->post(route('admin.faqs.store'), [
                'question' => 'Incomplete FAQ',
                'category' => 'Membership',
                'answer' => '',
            ])
            ->assertRedirect(route('admin.faqs.index'))
            ->assertSessionHasErrors('answer');

        $this->get(route('admin.faqs.index'))
            ->assertOk()
            ->assertSee('Incomplete FAQ')
            ->assertSee('aria-describedby="faqAnswerError"', false)
            ->assertSee('id="faqAnswerError"', false);
    }

    public function test_edit_form_preserves_input_after_validation_failure(): void
    {
        $faq = Faq::create([
            'question' => 'Existing FAQ',
            'answer' => 'Existing answer',
            'category' => 'Membership',
        ]);

        $this->actingAs($this->admin)
            ->from(route('admin.faqs.edit', $faq->id))
            ->put(route('admin.faqs.update', $faq->id), [
                'question' => 'Revised FAQ',
                'category' => 'Membership',
                'answer' => '',
            ])
            ->assertRedirect(route('admin.faqs.edit', $faq->id))
            ->assertSessionHasErrors('answer');

        $this->get(route('admin.faqs.edit', $faq->id))
            ->assertOk()
            ->assertSee('Revised FAQ')
            ->assertSee('aria-describedby="faqAnswerError"', false);
    }
}
