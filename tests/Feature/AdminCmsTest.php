<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;

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
            'status' => 'Approved'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('members', [
            'id' => 'KSO-CHD-2026-0099',
            'status' => 'Approved'
        ]);
    }
}
