<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\Setting;
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
        $this->get('/events')
            ->assertStatus(200)
            ->assertSee('css/pages/events.css')
            ->assertSee('js/pages/events.js');
    }

    public function test_event_detail_page_assets_and_submission_fields_are_accessible(): void
    {
        $event = Event::create([
            'title' => 'Student Cultural Meet',
            'category' => 'Cultural',
            'date' => now()->addWeek()->toDateString(),
            'time' => '10:00 AM',
            'venue' => 'Campus',
            'description' => 'Community event',
            'status' => 'Upcoming',
            'publication_status' => 'published',
        ]);

        $this->get(route('events.show', $event->id))
            ->assertOk()
            ->assertSee('css/pages/event-detail.css')
            ->assertSee('js/pages/event-detail.js')
            ->assertSee('for="event-full-name"', false);
    }

    public function test_event_ticket_page_loads_print_assets_and_has_a_real_pass_code(): void
    {
        $event = Event::create([
            'title' => 'Student Cultural Meet',
            'category' => 'Cultural',
            'date' => now()->addWeek()->toDateString(),
            'time' => '10:00 AM',
            'venue' => 'Campus',
            'description' => 'Community event',
            'status' => 'Upcoming',
            'publication_status' => 'published',
        ]);
        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'full_name' => 'Test Attendee',
            'email' => 'attendee@example.org',
            'phone' => '+91 90000 00000',
            'institution' => 'Panjab University',
            'ticket_code' => 'TICKET-2026-ABC123',
        ]);

        $this->get(route('events.ticketPass', $registration->ticket_code))
            ->assertOk()
            ->assertSee('css/pages/event-pass.css')
            ->assertSee('js/pages/event-pass.js')
            ->assertSee('TICKET-2026-ABC123')
            ->assertSee('id="printEventPass"', false);
    }

    public function test_faq_page_uses_accessible_accordion_markup_and_scoped_styles(): void
    {
        Faq::create([
            'question' => 'Where can I find help?',
            'answer' => 'Contact the student support cell.',
            'category' => 'Support',
            'is_published' => true,
        ]);

        $this->get('/faqs')
            ->assertOk()
            ->assertSee('css/pages/faqs.css')
            ->assertSee('aria-controls="collapse-', false)
            ->assertSee('role="region"', false);
    }

    public function test_event_calendar_data_escapes_untrusted_titles_as_json(): void
    {
        Event::create([
            'title' => '</script><script>alert(1)</script>',
            'category' => 'Cultural',
            'date' => now()->addWeek()->toDateString(),
            'time' => '10:00 AM',
            'venue' => 'Campus',
            'description' => 'Community event',
            'status' => 'Upcoming',
            'publication_status' => 'published',
        ]);

        $this->get('/events')
            ->assertOk()
            ->assertSee('id="calendar-events-data"', false)
            ->assertSee('\\u003C\\/script\\u003E', false)
            ->assertDontSee('</script><script>alert(1)</script>', false);
    }

    public function test_gallery_page_is_accessible(): void
    {
        $response = $this->get('/gallery');
        $response->assertStatus(200)
            ->assertSee('Community photos are on the way')
            ->assertSee('css/pages/gallery.css')
            ->assertSee('js/pages/gallery.js');
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

    public function test_gallery_category_filters_are_keyboard_accessible(): void
    {
        GalleryItem::create([
            'title' => 'Student Orientation',
            'category' => 'Student Life',
            'image_url' => '/storage/uploads/gallery/orientation.jpg',
            'date' => '2025-09-18',
        ]);

        $this->get('/gallery')
            ->assertSee('role="group" aria-label="Filter gallery by category"', false)
            ->assertSee('data-gallery-filter="student-life"', false)
            ->assertSee('aria-pressed="true"', false);
    }

    public function test_donations_page_is_accessible(): void
    {
        $response = $this->get('/donations');
        $response->assertStatus(200);
        $response->assertSee('css/pages/donations.css')
            ->assertSee('js/pages/donations.js')
            ->assertSee('aria-label="Suggested donation amounts"', false);
    }

    public function test_donation_validation_errors_are_rendered_for_accessible_fields(): void
    {
        $this->from('/donations')
            ->post('/donations', [])
            ->assertSessionHasErrors(['amount', 'donor_name', 'payment_ref']);

        $this->get('/donations')
            ->assertSee('id="donor-name-error"', false)
            ->assertSee('aria-describedby="donor-name-error"', false)
            ->assertSee('id="payment-ref-error"', false);
    }

    public function test_contact_page_is_accessible(): void
    {
        Setting::set('mapEmbedUrl', 'https://maps.example.test/kso');

        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertSee('css/pages/contact.css')
            ->assertSee('js/pages/contact.js')
            ->assertSee('for="contact-name"', false)
            ->assertSee('title="Map showing the KSO Chandigarh office location"', false);
    }

    public function test_contact_validation_errors_are_rendered_with_their_fields(): void
    {
        $this->from('/contact')
            ->post('/contact', [])
            ->assertSessionHasErrors(['name', 'phone', 'subject', 'message']);

        $this->get('/contact')
            ->assertSee('id="contact-name-error"', false)
            ->assertSee('aria-describedby="contact-name-error"', false);
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
