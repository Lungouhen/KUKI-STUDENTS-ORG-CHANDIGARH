<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Member;
use App\Models\MemberDocument;
use App\Models\MemberDocumentBatch;
use App\Models\MemberDocumentBatchItem;
use App\Models\MemberDocumentTemplate;
use App\Services\MemberDocumentIssuer;
use App\Services\MemberDocumentTemplateRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Throwable;

class MemberDocumentController extends Controller
{
    private const MAX_BATCH_RECIPIENTS = 500;

    private const BATCH_CHUNK_SIZE = 25;

    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(MemberDocument::STATUSES)],
            'document_type' => ['nullable', Rule::in(array_keys(config('member_documents.types')))],
            'certificate_number' => 'nullable|string|max:80',
            'member_id' => 'nullable|string|max:50',
            'member_name' => 'nullable|string|max:120',
            'batch_id' => 'nullable|integer|exists:member_document_batches,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $documents = MemberDocument::with(['member', 'template', 'issuedBy', 'batch'])
            ->when($validated['status'] ?? null, fn ($query, $value) => $query->where('status', $value))
            ->when($validated['document_type'] ?? null, fn ($query, $value) => $query->where('document_type', $value))
            ->when($validated['certificate_number'] ?? null, fn ($query, $value) => $query->where('certificate_number', $value))
            ->when($validated['member_id'] ?? null, fn ($query, $value) => $query->where('member_id', $value))
            ->when($validated['member_name'] ?? null, fn ($query, $value) => $query->whereHas('member', fn ($members) => $members->where('full_name', 'like', '%'.$value.'%')))
            ->when($validated['batch_id'] ?? null, fn ($query, $value) => $query->where('batch_id', $value))
            ->when($validated['date_from'] ?? null, fn ($query, $value) => $query->whereDate('issued_at', '>=', $value))
            ->when($validated['date_to'] ?? null, fn ($query, $value) => $query->whereDate('issued_at', '<=', $value))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $templates = MemberDocumentTemplate::where('is_active', true)
            ->orderBy('document_type')
            ->get();
        $batches = MemberDocumentBatch::latest()->limit(20)->get();

        return view('admin.member-documents.index', [
            'documents' => $documents,
            'types' => config('member_documents.types'),
            'statuses' => MemberDocument::STATUSES,
            'filters' => $validated,
            'templates' => $templates,
            'batches' => $batches,
        ]);
    }

    public function previewPending(Request $request, int $id, MemberDocumentTemplateRenderer $renderer)
    {
        $validated = $request->validate([
            'template_id' => 'required|integer|exists:member_document_templates,id',
            'document_details' => 'required|string|min:5|max:1500',
        ]);
        $document = MemberDocument::with('member')->findOrFail($id);
        abort_unless($document->status === 'pending', 404);
        $this->assertEligible($document->member);
        $template = MemberDocumentTemplate::findOrFail($validated['template_id']);
        abort_unless($template->document_type === $document->document_type, 422);

        return $this->previewView($renderer, $template, $document->member, $validated['document_details'], 'pending', [
            'document_id' => $document->id,
        ]);
    }

    public function previewDirect(Request $request, MemberDocumentTemplateRenderer $renderer)
    {
        $validated = $request->validate([
            'member_id' => 'required|string|exists:members,id',
            'template_id' => 'required|integer|exists:member_document_templates,id',
            'document_details' => 'required|string|min:5|max:1500',
        ]);
        $member = Member::findOrFail($validated['member_id']);
        $this->assertEligible($member);
        $template = MemberDocumentTemplate::findOrFail($validated['template_id']);

        return $this->previewView($renderer, $template, $member, $validated['document_details'], 'direct');
    }

    public function previewBulk(Request $request, MemberDocumentTemplateRenderer $renderer)
    {
        $validated = $request->validate([
            'selection_mode' => ['required', Rule::in(['selected', 'filter'])],
            'member_ids' => 'nullable|string|max:30000',
            'institution' => 'nullable|string|max:150',
            'course' => 'nullable|string|max:150',
            'template_id' => 'required|integer|exists:member_document_templates,id',
            'document_details' => 'required|string|min:5|max:1500',
        ]);

        if ($validated['selection_mode'] === 'selected') {
            $requestedIds = collect(preg_split('/[\s,;]+/', trim($validated['member_ids'] ?? ''), -1, PREG_SPLIT_NO_EMPTY))
                ->map(fn ($id) => trim($id))
                ->unique()
                ->values()
                ->all();
            if (! $requestedIds) {
                return back()->withInput()->withErrors(['member_ids' => 'Enter at least one member ID.']);
            }
            if (count($requestedIds) > self::MAX_BATCH_RECIPIENTS) {
                return back()->withInput()->withErrors(['member_ids' => 'A batch can include at most 500 members.']);
            }
            $existingCount = Member::whereIn('id', $requestedIds)->count();
            if ($existingCount !== count($requestedIds)) {
                return back()->withInput()->withErrors(['member_ids' => 'One or more member IDs were not found.']);
            }
        } else {
            $query = $this->cohortQuery($validated);
            $requestedIds = (clone $query)->orderBy('id')->limit(self::MAX_BATCH_RECIPIENTS + 1)->pluck('id')->all();
            if (count($requestedIds) > self::MAX_BATCH_RECIPIENTS) {
                return back()->withInput()->withErrors(['institution' => 'This cohort exceeds 500 members. Narrow the institution or course filters and preview again.']);
            }
        }

        if (! $requestedIds) {
            return back()->withInput()->withErrors(['member_ids' => 'No members matched the selected criteria.']);
        }

        $members = Member::whereIn('id', $requestedIds)->orderBy('full_name')->get();
        $eligibleIds = $members->filter(fn (Member $member) => $member->status === 'Approved' && $member->is_active)->pluck('id')->all();
        $excludedCount = count($requestedIds) - count($eligibleIds);
        $template = MemberDocumentTemplate::findOrFail($validated['template_id']);
        $sampleMember = $members->firstWhere('id', $eligibleIds[0] ?? null) ?? $members->first();
        $renderer->validate($template);
        $preview = $renderer->preview(
            $template,
            $sampleMember,
            $validated['document_details'],
            secure_url(route('documents.verify', 'PREVIEW-CERTIFICATE-NUMBER', false)),
            now()
        );

        return view('admin.member-documents.preview', [
            'mode' => 'bulk',
            'template' => $template,
            'member' => $sampleMember,
            'preview' => $preview,
            'types' => config('member_documents.types'),
            'details' => trim($validated['document_details']),
            'recipientCount' => count($requestedIds),
            'eligibleCount' => count($eligibleIds),
            'excludedCount' => $excludedCount,
            'recipients' => $members->take(25),
            'memberIds' => $requestedIds,
            'idempotencyKey' => (string) Str::uuid(),
        ]);
    }

    public function issue(Request $request, int $id, MemberDocumentIssuer $issuer)
    {
        $validated = $request->validate([
            'template_id' => 'required|integer|exists:member_document_templates,id',
            'document_details' => 'required|string|min:5|max:1500',
        ]);

        $document = DB::transaction(function () use ($id, $validated, $issuer) {
            $document = MemberDocument::whereKey($id)->lockForUpdate()->firstOrFail();
            if ($document->status !== 'pending') {
                return null;
            }
            $member = Member::whereKey($document->member_id)->lockForUpdate()->firstOrFail();
            if ($member->status !== 'Approved' || ! $member->is_active) {
                return null;
            }
            $template = MemberDocumentTemplate::findOrFail($validated['template_id']);
            abort_unless($template->document_type === $document->document_type, 422);

            $issuer->issue($document, $member, $template, $validated['document_details']);
            AuditLog::log('MEMBER_DOCUMENT_ISSUED', [
                'document_id' => $document->id,
                'certificate_number' => $document->certificate_number,
                'member_id' => $member->id,
                'document_type' => $document->document_type,
                'template_version' => $template->version,
            ]);

            return $document;
        });

        if (! $document) {
            return back()->with('error', 'This request is no longer eligible for issue. Check the member status and request state.');
        }
        $issuer->notify($document);

        return redirect()->route('admin.memberDocuments.index')->with('success', 'Certificate issued. Member notification: '.$document->fresh()->notification_status.'.');
    }

    public function generateDirect(Request $request, MemberDocumentIssuer $issuer)
    {
        $validated = $request->validate([
            'member_id' => 'required|string|exists:members,id',
            'template_id' => 'required|integer|exists:member_document_templates,id',
            'document_details' => 'required|string|min:5|max:1500',
        ]);

        $document = DB::transaction(function () use ($validated, $issuer) {
            $member = Member::whereKey($validated['member_id'])->lockForUpdate()->firstOrFail();
            if ($member->status !== 'Approved' || ! $member->is_active) {
                return null;
            }
            $template = MemberDocumentTemplate::findOrFail($validated['template_id']);
            $document = new MemberDocument([
                'member_id' => $member->id,
                'document_type' => $template->document_type,
                'purpose' => 'Administrator-generated official document',
                'status' => 'pending',
            ]);
            $member->documents()->save($document);
            $issuer->issue($document, $member, $template, $validated['document_details']);

            AuditLog::log('MEMBER_DOCUMENT_ISSUED', [
                'document_id' => $document->id,
                'certificate_number' => $document->certificate_number,
                'member_id' => $member->id,
                'document_type' => $document->document_type,
                'template_version' => $template->version,
                'source' => 'direct',
            ]);

            return $document;
        });

        if (! $document) {
            return back()->with('error', 'Only active, approved members can receive official documents.');
        }
        $issuer->notify($document);

        return redirect()->route('admin.memberDocuments.index', ['member_id' => $document->member_id])
            ->with('success', 'Certificate issued. Member notification: '.$document->fresh()->notification_status.'.');
    }

    public function generateBulk(Request $request, MemberDocumentIssuer $issuer)
    {
        $validated = $request->validate([
            'member_ids' => 'required|string|max:30000',
            'template_id' => 'required|integer|exists:member_document_templates,id',
            'document_details' => 'required|string|min:5|max:1500',
            'idempotency_key' => 'required|uuid',
        ]);
        $memberIds = array_values(array_unique(array_filter(preg_split('/[\s,;]+/', $validated['member_ids']))));
        if (count($memberIds) > self::MAX_BATCH_RECIPIENTS) {
            return back()->withErrors(['member_ids' => 'A batch can include at most 500 members.']);
        }
        if (Member::whereIn('id', $memberIds)->count() !== count($memberIds)) {
            return back()->withErrors(['member_ids' => 'One or more member IDs were not found.']);
        }

        $batch = MemberDocumentBatch::where('idempotency_key', $validated['idempotency_key'])->first();
        if (! $batch) {
            $template = MemberDocumentTemplate::findOrFail($validated['template_id']);
            try {
                $batch = DB::transaction(function () use ($validated, $memberIds, $template) {
                    return MemberDocumentBatch::create([
                        'idempotency_key' => $validated['idempotency_key'],
                        'document_type' => $template->document_type,
                        'template_id' => $template->id,
                        'template_version' => $template->version,
                        'created_by' => auth()->id(),
                        'status' => 'processing',
                        'shared_details' => trim($validated['document_details']),
                        'criteria' => ['recipient_ids' => $memberIds],
                        'recipient_count' => count($memberIds),
                        'started_at' => now(),
                    ]);
                });
            } catch (QueryException $exception) {
                $batch = MemberDocumentBatch::where('idempotency_key', $validated['idempotency_key'])->first();
                if (! $batch) {
                    throw $exception;
                }
            }

            if ($batch->wasRecentlyCreated) {
                AuditLog::log('MEMBER_DOCUMENT_BATCH_CREATED', [
                    'batch_id' => $batch->id,
                    'template_version' => $batch->template_version,
                    'recipient_count' => $batch->recipient_count,
                ]);
                $this->processBatch($batch, $issuer);
            }
        }

        return redirect()->route('admin.memberDocuments.batches.show', $batch->id);
    }

    public function showBatch(int $id)
    {
        $batch = MemberDocumentBatch::with(['template', 'items.member', 'items.document'])
            ->findOrFail($id);

        return view('admin.member-documents.batch', compact('batch'));
    }

    public function previewIssued(int $id)
    {
        $document = MemberDocument::whereIn('status', ['issued', 'revoked'])->findOrFail($id);

        return response()
            ->view('membership.documents.show', ['document' => $document, 'adminPreview' => true])
            ->header('Cache-Control', 'private, no-store')
            ->header('X-Frame-Options', 'DENY')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    public function retryBatch(int $id, Request $request, MemberDocumentIssuer $issuer)
    {
        $batch = MemberDocumentBatch::findOrFail($id);
        abort_unless(in_array($batch->status, ['partial', 'failed'], true), 404);
        AuditLog::log('MEMBER_DOCUMENT_BATCH_RETRY', ['batch_id' => $batch->id]);
        $this->processBatch($batch, $issuer, true);

        return redirect()->route('admin.memberDocuments.batches.show', $batch->id);
    }

    public function printBatch(int $id)
    {
        $batch = MemberDocumentBatch::with(['documents' => fn ($query) => $query->where('status', 'issued')->orderBy('member_id')])
            ->findOrFail($id);
        abort_if($batch->documents->isEmpty(), 404);

        return response()
            ->view('admin.member-documents.batch-print', compact('batch'))
            ->header('Cache-Control', 'private, no-store')
            ->header('X-Frame-Options', 'DENY');
    }

    public function reject(Request $request, int $id)
    {
        $validated = $request->validate(['resolution_note' => 'required|string|min:5|max:1000']);
        $rejected = DB::transaction(function () use ($id, $validated) {
            $document = MemberDocument::whereKey($id)->lockForUpdate()->firstOrFail();
            if ($document->status !== 'pending') {
                return false;
            }
            $document->forceFill(['status' => 'rejected', 'resolution_note' => trim($validated['resolution_note'])])->save();
            AuditLog::log('MEMBER_DOCUMENT_REJECTED', [
                'document_id' => $document->id,
                'member_id' => $document->member_id,
                'document_type' => $document->document_type,
            ]);

            return true;
        });

        return back()->with(
            $rejected ? 'success' : 'error',
            $rejected ? 'Document request rejected.' : 'Only pending document requests can be rejected.'
        );
    }

    public function revoke(Request $request, int $id)
    {
        $validated = $request->validate(['resolution_note' => 'required|string|min:5|max:1000']);
        $revoked = DB::transaction(function () use ($id, $validated) {
            $document = MemberDocument::whereKey($id)->lockForUpdate()->firstOrFail();
            if ($document->status !== 'issued') {
                return false;
            }
            $document->forceFill([
                'status' => 'revoked',
                'revoked_at' => now(),
                'resolution_note' => trim($validated['resolution_note']),
            ])->save();
            AuditLog::log('MEMBER_DOCUMENT_REVOKED', [
                'document_id' => $document->id,
                'certificate_number' => $document->certificate_number,
                'member_id' => $document->member_id,
                'document_type' => $document->document_type,
            ]);

            return true;
        });

        return back()->with(
            $revoked ? 'success' : 'error',
            $revoked ? 'Certificate revoked; public verification will show its revoked status.' : 'Only issued certificates can be revoked.'
        );
    }

    private function processBatch(MemberDocumentBatch $batch, MemberDocumentIssuer $issuer, bool $retryFailures = false): void
    {
        $template = $batch->template;
        if (! $template) {
            $batch->update(['status' => 'failed', 'failed_count' => $batch->recipient_count, 'finished_at' => now()]);

            return;
        }

        foreach (array_chunk($batch->criteria['recipient_ids'] ?? [], self::BATCH_CHUNK_SIZE) as $memberIds) {
            foreach ($memberIds as $memberId) {
                $existing = MemberDocumentBatchItem::where('batch_id', $batch->id)->where('member_id', $memberId)->first();
                if ($existing && ($existing->status !== 'failed' || ! $retryFailures)) {
                    continue;
                }

                try {
                    DB::transaction(function () use ($batch, $memberId, $template, $issuer, $existing) {
                        $member = Member::whereKey($memberId)->lockForUpdate()->firstOrFail();
                        $latestItem = MemberDocumentBatchItem::where('batch_id', $batch->id)
                            ->where('member_id', $member->id)
                            ->lockForUpdate()
                            ->first();
                        if ($latestItem && in_array($latestItem->status, ['issued', 'skipped'], true)) {
                            return;
                        }

                        if ($member->status !== 'Approved' || ! $member->is_active) {
                            MemberDocumentBatchItem::updateOrCreate(
                                ['batch_id' => $batch->id, 'member_id' => $member->id],
                                ['status' => 'skipped', 'message' => 'Member is not active and approved.', 'member_document_id' => null]
                            );

                            return;
                        }

                        $document = new MemberDocument([
                            'member_id' => $member->id,
                            'document_type' => $template->document_type,
                            'purpose' => 'Bulk official document batch #'.$batch->id,
                            'status' => 'pending',
                        ]);
                        $member->documents()->save($document);
                        $issuer->issue($document, $member, $template, $batch->shared_details, $batch->id);
                        MemberDocumentBatchItem::updateOrCreate(
                            ['batch_id' => $batch->id, 'member_id' => $member->id],
                            ['status' => 'issued', 'message' => null, 'member_document_id' => $document->id]
                        );
                        AuditLog::log('MEMBER_DOCUMENT_ISSUED', [
                            'document_id' => $document->id,
                            'certificate_number' => $document->certificate_number,
                            'member_id' => $member->id,
                            'document_type' => $document->document_type,
                            'template_version' => $template->version,
                            'batch_id' => $batch->id,
                        ]);
                    });
                } catch (Throwable $exception) {
                    report($exception);
                    MemberDocumentBatchItem::updateOrCreate(
                        ['batch_id' => $batch->id, 'member_id' => $memberId],
                        ['status' => 'failed', 'message' => 'Issuance failed; an administrator may retry this batch.', 'member_document_id' => null]
                    );
                }

                $item = MemberDocumentBatchItem::with('document')->where('batch_id', $batch->id)->where('member_id', $memberId)->first();
                if ($item?->status === 'issued' && $item->document?->notification_status === 'pending') {
                    $issuer->notify($item->document);
                }
            }
        }

        $counts = $batch->items()
            ->selectRaw("SUM(CASE WHEN status = 'issued' THEN 1 ELSE 0 END) as issued_count")
            ->selectRaw("SUM(CASE WHEN status = 'skipped' THEN 1 ELSE 0 END) as skipped_count")
            ->selectRaw("SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count")
            ->first();
        $issued = (int) $counts->issued_count;
        $skipped = (int) $counts->skipped_count;
        $failed = (int) $counts->failed_count;
        $status = $failed > 0 && $issued === 0 ? 'failed' : (($failed > 0 || $skipped > 0) ? 'partial' : 'completed');
        $batch->update([
            'status' => $status,
            'issued_count' => $issued,
            'skipped_count' => $skipped,
            'failed_count' => $failed,
            'finished_at' => now(),
        ]);
    }

    private function previewView(
        MemberDocumentTemplateRenderer $renderer,
        MemberDocumentTemplate $template,
        Member $member,
        string $details,
        string $mode,
        array $extra = []
    ) {
        $preview = $renderer->preview(
            $template,
            $member,
            trim($details),
            secure_url(route('documents.verify', 'PREVIEW-CERTIFICATE-NUMBER', false)),
            now()
        );

        return view('admin.member-documents.preview', [
            'mode' => $mode,
            'template' => $template,
            'member' => $member,
            'preview' => $preview,
            'details' => trim($details),
            'types' => config('member_documents.types'),
            ...$extra,
        ]);
    }

    private function assertEligible(Member $member): void
    {
        abort_unless($member->status === 'Approved' && $member->is_active, 422, 'Only active, approved members can receive official documents.');
    }

    private function cohortQuery(array $filters)
    {
        return Member::query()
            ->when($filters['institution'] ?? null, fn ($query, $value) => $query->where('institution', $value))
            ->when($filters['course'] ?? null, fn ($query, $value) => $query->where('course', $value));
    }
}
