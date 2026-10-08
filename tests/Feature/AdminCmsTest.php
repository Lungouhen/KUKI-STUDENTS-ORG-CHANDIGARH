<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ksochandigarh.org',
            'password' => bcrypt('admin123'),
            'is_admin' => true,
        ]);
    }

    public function test_admin_can_login(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@ksochandigarh.org',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_dashboard_loads_scoped_chart_assets_and_accessible_summaries(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('css/pages/admin-dashboard.css')
            ->assertSee('js/pages/admin-dashboard.js')
            ->assertSee('id="membersChart" role="img"', false)
            ->assertSee('id="members-chart-description"', false)
            ->assertSee('id="donations-chart-description"', false);
    }

    public function test_member_list_has_accessible_filters_and_action_labels(): void
    {
        Member::create([
            'id' => 'KSO-CHD-2026-0001',
            'full_name' => 'Test Student',
            'gender' => 'Male',
            'dob' => '2003-05-15',
            'phone' => '+91 90000 00000',
            'email' => 'member@example.org',
            'blood_group' => 'B+',
            'institution' => 'Panjab University',
            'course' => 'BSc',
            'year_of_study' => '2nd Year',
            'permanent_address' => 'Manipur',
            'current_address' => 'Chandigarh',
            'emergency_contact' => 'Parent',
            'emergency_phone' => '+91 90000 11111',
            'status' => 'Pending',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.members.index', ['status' => 'Pending']))
            ->assertOk()
            ->assertSee('css/pages/admin-members.css')
            ->assertSee('js/pages/admin-members.js')
            ->assertSee('for="admin-member-search"', false)
            ->assertSee('scope="col"', false)
            ->assertSee('aria-label="Approve member Test Student"', false)
            ->assertSee('data-member-delete', false);
    }

    public function test_admin_member_creation_form_has_scoped_assets_and_accessible_fields(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.members.create'))
            ->assertOk()
            ->assertSee('css/pages/admin-member-create.css')
            ->assertSee('js/pages/admin-member-create.js')
            ->assertSee('for="admin-member-full-name"', false)
            ->assertSee('aria-describedby="admin-member-photo-help', false)
            ->assertSee('id="admin-member-form-status"', false);
    }

    public function test_admin_member_edit_form_preserves_member_values_and_shows_current_photo(): void
    {
        $member = Member::create([
            'id' => 'KSO-CHD-2026-0002',
            'full_name' => 'Edit Student',
            'gender' => 'Female',
            'dob' => '2002-04-12',
            'phone' => '+91 90000 00002',
            'email' => 'edit-student@example.org',
            'blood_group' => 'A-',
            'institution' => 'Panjab University',
            'course' => 'BSc',
            'year_of_study' => '2nd Year',
            'permanent_address' => 'Manipur',
            'current_address' => 'Chandigarh',
            'emergency_contact' => 'Parent',
            'emergency_phone' => '+91 90000 11112',
            'photo' => '/storage/uploads/members/edit-student.jpg',
            'status' => 'Pending',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.members.edit', $member->id))
            ->assertOk()
            ->assertSee('css/pages/admin-member-edit.css')
            ->assertSee('js/pages/admin-member-edit.js')
            ->assertSee('for="admin-member-edit-full-name"', false)
            ->assertSee('value="Edit Student"', false)
            ->assertSee('alt="Current photo for Edit Student"', false)
            ->assertSee('admin-member-edit-photo-status', false);
    }

    public function test_admin_login_is_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/admin/login', [
                'email' => 'admin@ksochandigarh.org',
                'password' => 'wrong-password',
            ])->assertRedirect();
        }

        $this->post('/admin/login', [
            'email' => 'admin@ksochandigarh.org',
            'password' => 'wrong-password',
        ])->assertTooManyRequests();
    }

    public function test_unauthenticated_users_cannot_access_admin_pages(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_user_cannot_login_to_admin(): void
    {
        $user = User::create([
            'name' => 'Member Account',
            'email' => 'member@example.org',
            'password' => Hash::make('test member password'),
            'is_admin' => false,
        ]);

        $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'test member password',
        ])->assertRedirect();

        $this->assertGuest();
    }

    public function test_admin_can_approve_member_status(): void
    {
        $member = Member::create([
            'id' => 'KSO-CHD-2026-0099',
            'full_name' => 'Pending Student',
            'gender' => 'Male',
            'phone' => '+91 99999 00000',
            'email' => 'pending@kso.org',
            'blood_group' => 'B+',
            'institution' => 'PEC Chandigarh',
            'course' => 'BTech',
            'year_of_study' => '1st Year',
            'permanent_address' => 'Moreh, Manipur',
            'current_address' => 'PEC Campus, Chandigarh',
            'emergency_contact' => 'Father',
            'emergency_phone' => '+91 99999 11111',
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/members/{$member->id}/status", [
            'status' => 'Approved',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('members', [
            'id' => 'KSO-CHD-2026-0099',
            'status' => 'Approved',
        ]);
    }
}
