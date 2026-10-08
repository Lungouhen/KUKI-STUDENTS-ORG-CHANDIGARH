<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('KUKI STUDENTS\' ORGANISATION CHANDIGARH');
        $response->assertSee('css/home.css')
            ->assertSee('js/home.js')
            ->assertSee('data-counter-target=', false);
        $this->get('/about')->assertDontSee('js/home.js');
    }

    public function test_about_page_is_accessible(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('About KSO Chandigarh');
        $response->assertSee('css/pages/about.css')
            ->assertSee('alt="', false);
        $this->get('/contact')->assertDontSee('css/pages/about.css');
    }

    public function test_events_page_is_accessible(): void
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
    }

    public function test_gallery_page_is_accessible(): void
    {
        $response = $this->get('/gallery');
        $response->assertStatus(200)->assertSee('Community photos are on the way');
    }

    public function test_public_gallery_uses_uploaded_photos_not_seeded_mock_images(): void
    {
        GalleryItem::create([
            'title' => 'Community orientation',
            'category' => 'Student Life',
            'image_url' => '/images/gallery-1.jpg',
            'date' => '2025-09-18',
        ]);
        GalleryItem::create([
            'title' => 'Welcome week',
            'category' => 'Student Life',
            'image_url' => '/storage/uploads/gallery/welcome-week.jpg',
            'date' => '2025-09-19',
        ]);

        $this->get('/gallery')
            ->assertOk()
            ->assertSee('Welcome week')
            ->assertDontSee('Community orientation')
            ->assertSee('/storage/uploads/gallery/welcome-week.jpg');
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
