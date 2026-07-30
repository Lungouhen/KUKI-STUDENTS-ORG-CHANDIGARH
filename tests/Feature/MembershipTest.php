<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Member;

class MembershipTest extends TestCase
{
    use RefreshDatabase;

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

        $response->assertRedirect();
        $this->assertDatabaseHas('members', [
            'full_name' => 'Test Student Haokip',
            'email' => 'teststudent@gmail.com',
            'status' => 'Pending'
        ]);
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
            'member_id' => 'KSO-CHD-2026-9999'
        ]);

        $response->assertStatus(200);
        $response->assertSee('Verified Student');
        $response->assertSee('APPROVED');
    }
}
