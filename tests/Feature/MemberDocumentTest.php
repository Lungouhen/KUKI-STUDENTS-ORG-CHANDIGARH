<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MemberDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MemberDocumentTest extends TestCase
{
    use RefreshDatabase;

    private Member $member;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        $this->member = $this->createMember('KSO-CHD-2026-4001', 'document-member@example.org');
        $this->admin = User::create([
            'name' => 'Document Admin',
            'email' => 'document-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_active_member_can_request_a_supported_document_and_duplicate_pending_requests_are_blocked(): void
    {
        $this->withSession(['member_id' => $this->member->id])
            ->post(route('membership.documents.request'), [
                'document_type' => 'character',
                'purpose' => 'University accommodation application',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('member_documents', [
            'member_id' => $this->member->id,
            'document_type' => 'character',
            'status' => 'pending',
        ]);

        $this->withSession(['member_id' => $this->member->id])
            ->post(route('membership.documents.request'), [
                'document_type' => 'character',
                'purpose' => 'Another character certificate',
            ])
            ->assertSessionHas('error');

        $this->assertSame(1, MemberDocument::count());
    }

    public function test_guests_and_unapproved_or_inactive_members_cannot_request_documents(): void
    {
        $this->post(route('membership.documents.request'), [
            'document_type' => 'character',
            'purpose' => 'University application',
        ])->assertRedirect(route('membership.portal'));

        $this->member->update(['status' => 'Pending']);
        $this->withSession(['member_id' => $this->member->id])
            ->post(route('membership.documents.request'), [
                'document_type' => 'character',
                'purpose' => 'University application',
            ])
            ->assertSessionHas('error');

        $this->member->update(['status' => 'Approved', 'is_active' => false]);
        $this->withSession(['member_id' => $this->member->id])
            ->post(route('membership.documents.request'), [
                'document_type' => 'character',
                'purpose' => 'University application',
            ])
            ->assertSessionHas('error');

        $this->assertSame(0, MemberDocument::count());
    }

    public function test_admin_issues_audited_certificate_and_member_can_print_only_their_own(): void
    {
        $document = $this->pendingDocument([
            'purpose' => 'University accommodation application',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.issue', $document->id), [
                'template_id' => $this->templateId(),
                'document_details' => '<script>alert("not executable")</script> service activity',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $document->refresh();
        $this->assertSame('issued', $document->status);
        $this->assertNotEmpty($document->certificate_number);
        $this->assertSame($this->member->full_name, $document->member_snapshot['full_name']);
        $this->assertSame($this->admin->id, $document->issued_by);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'MEMBER_DOCUMENT_ISSUED',
            'user_id' => $this->admin->id,
        ]);

        $this->withSession(['member_id' => $this->member->id])
            ->get(route('membership.documents.show', $document->id))
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('University accommodation application');

        $otherMember = $this->createMember('KSO-CHD-2026-4002', 'other-document-member@example.org');
        $this->withSession(['member_id' => $otherMember->id])
            ->get(route('membership.documents.show', $document->id))
            ->assertNotFound();
    }

    public function test_public_verification_shows_minimal_status_and_reflects_revocation(): void
    {
        $document = $this->pendingDocument();
        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.issue', $document->id), [
                'template_id' => $this->templateId(),
                'document_details' => 'Member participation in student welfare activities',
            ])
            ->assertSessionHas('success');

        $document->refresh();
        $this->get(route('documents.verify', $document->certificate_number))
            ->assertOk()
            ->assertSee('Valid certificate')
            ->assertSee($this->member->full_name)
            ->assertDontSee($this->member->email)
            ->assertDontSee($document->purpose);

        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.revoke', $document->id), [
                'resolution_note' => 'Certificate details require correction',
            ])
            ->assertSessionHas('success');

        $this->get(route('documents.verify', $document->certificate_number))
            ->assertOk()
            ->assertSee('Certificate revoked')
            ->assertDontSee('Member participation in student welfare activities');

        $this->withSession(['member_id' => $this->member->id])
            ->get(route('membership.documents.show', $document->id))
            ->assertNotFound();
    }

    public function test_admin_rejection_is_audited_and_rejected_requests_cannot_be_issued(): void
    {
        $document = $this->pendingDocument();

        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.reject', $document->id), [
                'resolution_note' => 'Please provide the required supporting details',
            ])
            ->assertSessionHas('success');

        $document->refresh();
        $this->assertSame('rejected', $document->status);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'MEMBER_DOCUMENT_REJECTED',
            'user_id' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.issue', $document->id), [
                'template_id' => $this->templateId(),
                'document_details' => 'Some approved details',
            ])
            ->assertSessionHas('error');

        $this->assertSame('rejected', $document->fresh()->status);
    }

    public function test_member_with_document_history_cannot_be_deleted(): void
    {
        $this->pendingDocument();

        $this->actingAs($this->admin)
            ->delete(route('admin.members.destroy', $this->member->id))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('members', ['id' => $this->member->id]);
        $this->assertSame(1, $this->member->documents()->count());
    }

    private function pendingDocument(array $overrides = []): MemberDocument
    {
        return $this->member->documents()->create(array_merge([
            'document_type' => 'character',
            'purpose' => 'General official use',
        ], $overrides));
    }

    private function templateId(): int
    {
        return \App\Models\MemberDocumentTemplate::where('document_type', 'character')->where('is_active', true)->value('id');
    }

    private function createMember(string $id, string $email): Member
    {
        return Member::create([
            'id' => $id,
            'full_name' => 'Certificate Member',
            'gender' => 'Female',
            'dob' => '2003-05-15',
            'phone' => '+91 90000 00000',
            'email' => $email,
            'blood_group' => 'B+',
            'institution' => 'Panjab University',
            'course' => 'BSc',
            'year_of_study' => '2nd Year',
            'permanent_address' => 'Manipur',
            'current_address' => 'Chandigarh',
            'emergency_contact' => 'Parent',
            'emergency_phone' => '+91 90000 11111',
            'status' => 'Approved',
        ]);
    }
}
