<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_form_is_accessible_and_family_fields_share_their_alpine_scope(): void
    {
        $this->get(route('membership.register'))
            ->assertOk()
            ->assertSee('css/pages/membership-register.css')
            ->assertSee('js/pages/membership-register.js')
            ->assertSee('membership-registration-page" x-data=', false)
            ->assertSee('x-show="memberType === \'Family\'"', false)
            ->assertSee('for="membership-full-name"', false)
            ->assertSee('id="membership-photo-status"', false);
    }

    public function test_can_register_new_member(): void
    {
        $response = $this->post('/members/register', [
            'full_name' => 'Test Student Haokip',
            'gender' => 'Male',
            'dob' => '2004-01-01',
            'phone' => '+91 98765 00000',
            'email' => 'teststudent@gmail.com',
            'blood_group' => 'O+',
            'institution' => 'Panjab University, Sector 14',
            'course' => 'BA English',
            'year_of_study' => '1st Year',
            'permanent_address' => 'Churachandpur, Manipur',
            'current_address' => 'Sector 15, Chandigarh',
            'emergency_contact' => 'Father Name',
            'emergency_phone' => '+91 98765 11111',
        ]);

        $member = Member::where('email', 'teststudent@gmail.com')->firstOrFail();

        $response->assertRedirect(route('membership.idCard', $member->id));
        $this->assertDatabaseHas('members', [
            'full_name' => 'Test Student Haokip',
            'email' => 'teststudent@gmail.com',
            'status' => 'Pending',
        ]);
        $this->get(route('membership.idCard', $member->id))
            ->assertOk()
            ->assertSee('css/pages/member-id-card.css')
            ->assertSee('js/pages/member-id-card.js')
            ->assertSee('alt="Member photo for Test Student Haokip"', false)
            ->assertSee('id="printMemberIdCard"', false);
    }

    public function test_can_verify_existing_member_id(): void
    {
        $member = Member::create([
            'id' => 'KSO-CHD-2026-9999',
            'full_name' => 'Verified Student',
            'gender' => 'Female',
            'phone' => '+91 99999 88888',
            'email' => 'verified@kso.org',
            'blood_group' => 'A+',
            'institution' => 'DAV College, Sector 10',
            'course' => 'BSc',
            'year_of_study' => '2nd Year',
            'permanent_address' => 'Kangpokpi, Manipur',
            'current_address' => 'Sector 10, Chandigarh',
            'emergency_contact' => 'Mother Name',
            'emergency_phone' => '+91 99999 77777',
            'status' => 'Approved',
        ]);

        $response = $this->post('/members/verify', [
            'member_id' => 'KSO-CHD-2026-9999',
        ]);

        $response->assertStatus(200)
            ->assertSee('css/pages/member-verification.css')
            ->assertSee('js/pages/member-verification.js')
            ->assertSee('for="verification-member-id"', false)
            ->assertSee('Verified Student')
            ->assertSee('APPROVED');
    }
}
