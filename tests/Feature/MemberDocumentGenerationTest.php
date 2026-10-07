<?php

namespace Tests\Feature;

use App\Mail\MemberDocumentAvailableMail;
use App\Models\Member;
use App\Models\MemberDocument;
use App\Models\MemberDocumentBatch;
use App\Models\MemberDocumentTemplate;
use App\Models\User;
use App\Services\MemberDocumentIssuer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class MemberDocumentGenerationTest extends TestCase
{
    use RefreshDatabase;

    private Member $member;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        $this->member = $this->createMember('KSO-CHD-2026-5101', 'versioned-member@example.org');
        $this->admin = User::create([
            'name' => 'Certificate Admin',
            'email' => 'certificate-admin@example.org',
            'password' => Hash::make('secure test password'),
            'is_admin' => true,
        ]);
    }

    public function test_template_preview_rejects_html_and_undocumented_placeholders(): void
    {
        $this->actingAs($this->admin)
            ->from(route('admin.memberDocumentTemplates.index'))
            ->post(route('admin.memberDocumentTemplates.preview'), [
                'document_type' => 'character',
                'title' => 'Character Certificate',
                'statement' => '<script>alert(1)</script>',
                'style' => 'classic',
            ])
            ->assertRedirect(route('admin.memberDocumentTemplates.index'))
            ->assertSessionHasErrors('statement');

        $this->actingAs($this->admin)
            ->from(route('admin.memberDocumentTemplates.index'))
            ->post(route('admin.memberDocumentTemplates.preview'), [
                'document_type' => 'character',
                'title' => 'Character Certificate',
                'statement' => 'Unknown value {{secret_field}}',
                'style' => 'classic',
            ])
            ->assertRedirect(route('admin.memberDocumentTemplates.index'))
            ->assertSessionHasErrors('statement');
    }

    public function test_template_changes_create_versions_without_rewriting_issued_content(): void
    {
        $template = $this->activeTemplate();
        $document = $this->member->documents()->create([
            'document_type' => 'character',
            'purpose' => 'University application',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.issue', $document->id), [
                'template_id' => $template->id,
                'document_details' => 'Good conduct as recorded by the organisation',
            ])
            ->assertSessionHas('success');

        $issued = $document->fresh();
        $contentBefore = $issued->content_snapshot;
        $this->assertSame(1, $issued->template_version);

        $payload = [
            'document_type' => 'character',
            'title' => 'Character Certificate - {{member_name}}',
            'statement' => 'Verified from KSO records for {{institution}}.',
            'style' => 'forest',
        ];
        $this->actingAs($this->admin)
            ->post(route('admin.memberDocumentTemplates.preview'), $payload)
            ->assertOk()
            ->assertSee('Sample Member')
            ->assertSee('Panjab University');
        $this->actingAs($this->admin)
            ->post(route('admin.memberDocumentTemplates.store'), $payload)
            ->assertRedirect(route('admin.memberDocumentTemplates.index'));

        $newTemplate = MemberDocumentTemplate::where('document_type', 'character')->where('is_active', true)->firstOrFail();
        $this->assertSame(2, $newTemplate->version);
        $this->assertFalse($template->fresh()->is_active);
        $this->assertSame($contentBefore, $issued->fresh()->content_snapshot);
        $this->assertSame($template->id, $issued->fresh()->template_id);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'MEMBER_DOCUMENT_TEMPLATE_VERSION_CREATED',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_template_placeholders_are_rendered_as_escaped_text_and_member_is_notified(): void
    {
        $template = $this->activeTemplate();
        $templatePayload = [
            'document_type' => 'character',
            'title' => 'Character award for {{member_name}}',
            'statement' => 'Certificate detail: {{certificate_details}}',
            'style' => 'blue',
        ];
        $this->actingAs($this->admin)->post(route('admin.memberDocumentTemplates.preview'), $templatePayload)->assertOk();
        $this->actingAs($this->admin)->post(route('admin.memberDocumentTemplates.store'), $templatePayload)->assertRedirect();
        $template = MemberDocumentTemplate::where('document_type', 'character')->where('is_active', true)->firstOrFail();

        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.previewDirect'), [
                'member_id' => $this->member->id,
                'template_id' => $template->id,
                'document_details' => '<script>alert("xss")</script>',
            ])
            ->assertOk()
            ->assertSee('Character award for Certificate Member')
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert("xss")</script>', false);

        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.generateDirect'), [
                'member_id' => $this->member->id,
                'template_id' => $template->id,
                'document_details' => '<script>alert("xss")</script>',
            ])
            ->assertRedirect();

        $document = MemberDocument::firstOrFail();
        $this->assertStringContainsString('<script>', $document->content_snapshot['statement']);
        $this->assertSame('sent', $document->fresh()->notification_status);
        Mail::assertSent(MemberDocumentAvailableMail::class, fn ($mail) => $mail->certificateNumber === $document->certificate_number);

        $this->withSession(['member_id' => $this->member->id])
            ->get(route('membership.documents.show', $document->id))
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>', false)
            ->assertSee('verification');
    }

    public function test_individual_generation_rejects_inactive_members(): void
    {
        $this->member->update(['is_active' => false]);
        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.previewDirect'), [
                'member_id' => $this->member->id,
                'template_id' => $this->activeTemplate()->id,
                'document_details' => 'Approved participation details',
            ])
            ->assertStatus(422);
        $this->assertSame(0, MemberDocument::count());
    }

    public function test_bulk_preview_shows_exclusions_and_idempotent_issue_keeps_one_document_per_member(): void
    {
        $inactive = $this->createMember('KSO-CHD-2026-5102', 'inactive-member@example.org');
        $inactive->update(['is_active' => false]);
        $template = $this->activeTemplate();
        $ids = [$this->member->id, $inactive->id];

        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.previewBulk'), [
                'selection_mode' => 'selected',
                'member_ids' => implode(',', $ids),
                'template_id' => $template->id,
                'document_details' => 'Participation in student welfare programme',
            ])
            ->assertOk()
            ->assertSee('active, approved members')
            ->assertSee('selected members are currently ineligible');

        $idempotencyKey = (string) Str::uuid();
        $payload = [
            'member_ids' => implode(',', $ids),
            'template_id' => $template->id,
            'document_details' => 'Participation in student welfare programme',
            'idempotency_key' => $idempotencyKey,
        ];
        $response = $this->actingAs($this->admin)->post(route('admin.memberDocuments.generateBulk'), $payload);
        $batch = MemberDocumentBatch::where('idempotency_key', $idempotencyKey)->firstOrFail();
        $response->assertRedirect(route('admin.memberDocuments.batches.show', $batch->id));
        $this->assertSame('partial', $batch->status);
        $this->assertSame(1, $batch->issued_count);
        $this->assertSame(1, $batch->skipped_count);
        $this->assertSame(1, MemberDocument::count());
        $this->assertSame(2, $batch->items()->count());

        $this->actingAs($this->admin)->post(route('admin.memberDocuments.generateBulk'), $payload)
            ->assertRedirect(route('admin.memberDocuments.batches.show', $batch->id));
        $this->assertSame(1, MemberDocument::count());
        $this->assertSame(1, $batch->fresh()->issued_count);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'MEMBER_DOCUMENT_BATCH_CREATED',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_failed_batch_recipient_can_be_retried_without_duplicate_issue(): void
    {
        $mockIssuer = Mockery::mock(MemberDocumentIssuer::class);
        $mockIssuer->shouldReceive('issue')->once()->andThrow(new RuntimeException('simulated issuance failure'));
        app()->instance(MemberDocumentIssuer::class, $mockIssuer);

        $key = (string) Str::uuid();
        $this->actingAs($this->admin)
            ->post(route('admin.memberDocuments.generateBulk'), [
                'member_ids' => $this->member->id,
                'template_id' => $this->activeTemplate()->id,
                'document_details' => 'Participation in student welfare programme',
                'idempotency_key' => $key,
            ]);

        $batch = MemberDocumentBatch::where('idempotency_key', $key)->firstOrFail();
        $this->assertSame('failed', $batch->status);
        $this->assertSame(1, $batch->failed_count);
        $this->assertSame(0, MemberDocument::count());

        app()->forgetInstance(MemberDocumentIssuer::class);
        $this->actingAs($this->admin)->post(route('admin.memberDocuments.batches.retry', $batch->id))
            ->assertRedirect(route('admin.memberDocuments.batches.show', $batch->id));
        $this->assertSame('completed', $batch->fresh()->status);
        $this->assertSame(1, $batch->fresh()->issued_count);
        $this->assertSame(1, MemberDocument::where('batch_id', $batch->id)->count());
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'MEMBER_DOCUMENT_BATCH_RETRY',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_search_by_certificate_member_and_batch_and_print_batch_privately(): void
    {
        $key = (string) Str::uuid();
        $this->actingAs($this->admin)->post(route('admin.memberDocuments.generateBulk'), [
            'member_ids' => $this->member->id,
            'template_id' => $this->activeTemplate()->id,
            'document_details' => 'Participation in student welfare programme',
            'idempotency_key' => $key,
        ]);
        $batch = MemberDocumentBatch::where('idempotency_key', $key)->firstOrFail();
        $document = $batch->documents()->firstOrFail();

        $this->actingAs($this->admin)->get(route('admin.memberDocuments.index', ['certificate_number' => $document->certificate_number]))
            ->assertOk()->assertSee($document->certificate_number);
        $this->actingAs($this->admin)->get(route('admin.memberDocuments.index', ['member_id' => $this->member->id]))
            ->assertOk()->assertSee($document->certificate_number);
        $this->actingAs($this->admin)->get(route('admin.memberDocuments.index', ['batch_id' => $batch->id]))
            ->assertOk()->assertSee($document->certificate_number);
        $this->actingAs($this->admin)->get(route('admin.memberDocuments.batches.print', $batch->id))
            ->assertOk()->assertSee('break-after: page')->assertSee($document->certificate_number);
        auth()->logout();
        $this->get(route('admin.memberDocuments.batches.print', $batch->id))->assertRedirect();
    }

    public function test_public_verification_masks_member_id_and_returns_a_real_not_found_page(): void
    {
        $template = $this->activeTemplate();
        $document = $this->member->documents()->create(['document_type' => 'character', 'purpose' => 'Verification']);
        $this->actingAs($this->admin)->post(route('admin.memberDocuments.issue', $document->id), [
            'template_id' => $template->id,
            'document_details' => 'Character and conduct record reviewed',
        ]);

        $this->get(route('documents.verify', $document->fresh()->certificate_number))
            ->assertOk()
            ->assertSee('••••5101')
            ->assertDontSee($this->member->id);
        $this->get(route('documents.verify', 'KSO-CHD-2026-NOT-FOUND'))
            ->assertNotFound()
            ->assertSee('Certificate not found');
    }

    private function activeTemplate(): MemberDocumentTemplate
    {
        return MemberDocumentTemplate::where('document_type', 'character')->where('is_active', true)->firstOrFail();
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
            'is_active' => true,
        ]);
    }
}
