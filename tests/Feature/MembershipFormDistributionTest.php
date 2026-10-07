<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MembershipFormDistributionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Forms Admin',
            'email' => 'forms-admin@example.org',
            'password' => Hash::make('test password'),
            'is_admin' => true,
        ]);
    }

    public function test_membership_form_builder_is_only_available_to_admins(): void
    {
        $this->get(route('admin.membershipForms.index'))
            ->assertRedirect(route('admin.login'));

        $this->actingAs($this->admin)
            ->get(route('admin.membershipForms.index'))
            ->assertOk()
            ->assertSee('Share the online application')
            ->assertSee('Build a printable blank form')
            ->assertSee(route('membership.register'), false)
            ->assertSee('Download offline form');
    }

    public function test_print_view_includes_only_selected_form_modules(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.membershipForms.print', [
                'modules' => ['personal', 'photo'],
            ]))
            ->assertOk()
            ->assertSee('Personal details')
            ->assertSee('Student photograph')
            ->assertDontSee('College and academic details')
            ->assertDontSee('Applicant declaration and office use');
    }

    public function test_download_returns_a_standalone_printable_html_attachment(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.membershipForms.download', [
                'modules' => ['membership'],
            ]));

        $response->assertOk()
            ->assertHeader('content-type', 'text/html; charset=UTF-8')
            ->assertHeader('x-content-type-options', 'nosniff')
            ->assertHeader('content-disposition', 'attachment; filename="kso-membership-form.html"')
            ->assertSee('Membership category')
            ->assertDontSee('Permanent address / home');
    }

    public function test_unknown_form_modules_are_rejected(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.membershipForms.print', [
                'modules' => ['personal', 'unknown'],
            ]))
            ->assertSessionHasErrors('modules.1');
    }

    public function test_regular_users_cannot_access_membership_form_tools(): void
    {
        $memberUser = User::create([
            'name' => 'Member User',
            'email' => 'member-form@example.org',
            'password' => Hash::make('test password'),
            'is_admin' => false,
        ]);

        $this->actingAs($memberUser)
            ->get(route('admin.membershipForms.index'))
            ->assertRedirect(route('admin.dashboard'));
    }
}
