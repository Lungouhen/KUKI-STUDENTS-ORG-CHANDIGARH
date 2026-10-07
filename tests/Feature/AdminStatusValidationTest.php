<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\ContactMessage;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminStatusValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'status-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_member_status_endpoint_rejects_unsupported_statuses(): void
    {
        $member = $this->createMember();

        $this->actingAs($this->admin)
            ->post(route('admin.members.updateStatus', $member->id), ['status' => 'Suspended'])
            ->assertSessionHasErrors('status');

        $this->assertSame('Pending', $member->fresh()->status);
        $this->assertNull($member->fresh()->approval_date);
        $this->assertSame(0, AuditLog::count());
    }

    public function test_contact_message_status_is_validated_and_audited(): void
    {
        $message = ContactMessage::create([
            'name' => 'Student',
            'email' => 'student@example.org',
            'phone' => '555-0100',
            'subject' => 'Question',
            'message' => 'Please contact me.',
            'status' => 'Unread',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.messages.updateStatus', $message->id), ['status' => 'Archived'])
            ->assertSessionHasErrors('status');

        $this->assertSame('Unread', $message->fresh()->status);
        $this->assertSame(0, AuditLog::count());

        $this->post(route('admin.messages.updateStatus', $message->id), ['status' => 'Resolved'])
            ->assertRedirect();

        $this->assertSame('Resolved', $message->fresh()->status);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->admin->id,
            'action' => 'CONTACT_MESSAGE_STATUS_UPDATED',
            'details' => "Message #{$message->id} status changed from Unread to Resolved.",
        ]);
    }

    public function test_admin_member_creation_rejects_unsupported_statuses(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.members.store'), [
                'full_name' => 'Pending Student',
                'gender' => 'Male',
                'phone' => '555-0100',
                'email' => 'new-student@example.org',
                'blood_group' => 'O+',
                'institution' => 'Test College',
                'course' => 'Test Course',
                'year_of_study' => '1',
                'permanent_address' => 'Address',
                'current_address' => 'Address',
                'emergency_contact' => 'Parent',
                'emergency_phone' => '555-0101',
                'status' => 'Suspended',
            ])
            ->assertSessionHasErrors('status');

        $this->assertSame(0, Member::count());
    }

    private function createMember(): Member
    {
        return Member::create([
            'id' => 'KSO-CHD-2026-0101',
            'full_name' => 'Pending Student',
            'gender' => 'Male',
            'phone' => '555-0100',
            'email' => 'pending-student@example.org',
            'blood_group' => 'O+',
            'institution' => 'Test College',
            'course' => 'Test Course',
            'year_of_study' => '1',
            'permanent_address' => 'Address',
            'current_address' => 'Address',
            'emergency_contact' => 'Parent',
            'emergency_phone' => '555-0101',
            'status' => 'Pending',
        ]);
    }
}
