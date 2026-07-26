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
}
