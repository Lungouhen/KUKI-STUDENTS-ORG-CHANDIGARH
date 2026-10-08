<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPartnerPageTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Partner Admin',
            'email' => 'partner-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_partner_register_renders_accessible_directory_and_page_assets(): void
    {
        Partner::create([
            'name' => 'Community Learning Network',
            'type' => 'Collaborator',
            'category' => 'NGO',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.partners.index'));

        $response->assertOk();
        $response->assertSee('css/pages/admin-partners.css');
        $response->assertSee('js/pages/admin-partners.js');
        $response->assertSee('Community Learning Network');
        $response->assertSee('aria-label="Edit Community Learning Network"', false);
        $response->assertSee('aria-label="Delete Community Learning Network"', false);
    }

    public function test_partner_validation_reopens_modal_and_preserves_input(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.partners.index'))
            ->post(route('admin.partners.store'), [
                'name' => 'Potential partner',
                'email' => 'not-an-email',
                'type' => 'Donor',
                'category' => 'NGO',
                'status' => 'Active',
            ]);

        $response->assertRedirect(route('admin.partners.index'));

        $page = $this->get(route('admin.partners.index'));
        $page->assertOk();
        $page->assertSee('data-reopen-on-error="true"', false);
        $page->assertSee('Potential partner');
        $page->assertSee('The email field must be a valid email address.', false);
    }
}
