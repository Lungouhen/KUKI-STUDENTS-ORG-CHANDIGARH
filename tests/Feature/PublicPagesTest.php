<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('KUKI STUDENTS\' ORGANISATION CHANDIGARH');
    }

    public function test_about_page_is_accessible(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('About KSO Chandigarh');
    }

    public function test_events_page_is_accessible(): void
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
    }

    public function test_gallery_page_is_accessible(): void
    {
        $response = $this->get('/gallery');
        $response->assertStatus(200);
    }

    public function test_donations_page_is_accessible(): void
    {
        $response = $this->get('/donations');
        $response->assertStatus(200);
    }

    public function test_contact_page_is_accessible(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    public function test_shared_navigation_has_keyboard_accessible_controls(): void
    {
        $this->get('/')
            ->assertSee('Skip to main content')
            ->assertSee('aria-label="Toggle navigation"', false)
            ->assertSee('aria-controls="membershipDropdownMenu"', false)
            ->assertSee('role="status"', false);
    }

    public function test_admin_login_does_not_disclose_default_credentials(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertDontSee('admin123')
            ->assertDontSee('value="admin@ksochandigarh.org"', false)
            ->assertSee('for="admin-email"', false)
            ->assertSee('for="admin-password"', false);
    }
}
