<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTestimonialModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Testimonial Admin',
            'email' => 'testimonial-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_testimonial_register_is_searchable_and_renders_route_assets(): void
    {
        $testimonial = $this->testimonial();

        $this->actingAs($this->admin)
            ->get(route('admin.testimonials.index', ['q' => 'Community']))
            ->assertOk()
            ->assertSee('css/pages/admin-testimonials.css')
            ->assertSee('js/pages/admin-testimonials.js')
            ->assertSee($testimonial->author_name)
            ->assertSee(route('admin.testimonials.edit', $testimonial->id));
    }

    public function test_create_validation_error_preserves_testimonial_input(): void
    {
        $this->actingAs($this->admin)
            ->from(route('admin.testimonials.index'))
            ->post(route('admin.testimonials.store'), [
                'author_name' => 'New alumni',
                'author_title' => 'Graduate',
                'college_name' => 'Community University',
                'quote' => '',
                'rating' => 5,
            ])
            ->assertRedirect(route('admin.testimonials.index'))
            ->assertSessionHasErrors('quote');

        $this->get(route('admin.testimonials.index'))
            ->assertOk()
            ->assertSee('New alumni')
            ->assertSee('aria-labelledby="testimonialErrorsHeading"', false);
    }

    public function test_edit_form_renders_accessible_fields_and_existing_values(): void
    {
        $testimonial = $this->testimonial();

        $this->actingAs($this->admin)
            ->get(route('admin.testimonials.edit', $testimonial->id))
            ->assertOk()
            ->assertSee('for="testimonialAuthorName"', false)
            ->assertSee($testimonial->author_name)
            ->assertSee($testimonial->quote);
    }

    private function testimonial(): Testimonial
    {
        return Testimonial::create([
            'author_name' => 'Community Graduate',
            'author_title' => 'Alumni',
            'college_name' => 'Community University',
            'quote' => 'Student services helped me find my way.',
            'rating' => 5,
        ]);
    }
}
