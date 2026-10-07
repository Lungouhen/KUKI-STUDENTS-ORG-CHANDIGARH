<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberVerificationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_response_contains_only_public_id_card_fields(): void
    {
        $member = $this->createMember();

        $this->getJson('/api/verify/' . $member->id)
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'member' => [
                    'id' => $member->id,
                    'full_name' => 'Test Student',
                    'status' => 'Approved',
                    'institution' => 'Test College',
                    'course' => 'Test Course',
                    'year_of_study' => '2',
                    'membership_type' => 'Regular Student Member',
                    'valid_until' => '2027-06-30',
                    'photo' => 'members/test.jpg',
                ],
            ])
            ->assertJsonMissingPath('member.email')
            ->assertJsonMissingPath('member.phone')
            ->assertJsonMissingPath('member.permanent_address')
            ->assertJsonMissingPath('member.emergency_phone');
    }

    public function test_verification_endpoint_throttles_repeated_requests(): void
    {
        $member = $this->createMember();

        for ($request = 0; $request < 10; $request++) {
            $this->getJson('/api/verify/' . $member->id)->assertOk();
        }

        $this->getJson('/api/verify/' . $member->id)->assertStatus(429);
    }

    private function createMember(): Member
    {
        return Member::create([
            'id' => 'KSO-CHD-2026-0010',
            'full_name' => 'Test Student',
            'phone' => '+91 98765 43210',
            'email' => 'private@example.org',
            'institution' => 'Test College',
            'course' => 'Test Course',
            'year_of_study' => '2',
            'permanent_address' => 'Private address',
            'current_address' => 'Private current address',
            'emergency_contact' => 'Private Contact',
            'emergency_phone' => '+91 91234 56789',
            'photo' => 'members/test.jpg',
            'status' => 'Approved',
            'membership_type' => 'Regular Student Member',
            'valid_until' => '2027-06-30',
        ]);
    }
}
