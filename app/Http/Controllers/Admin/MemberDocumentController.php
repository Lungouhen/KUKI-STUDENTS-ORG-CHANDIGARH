<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MemberDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MemberDocumentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        abort_if($status && ! in_array($status, MemberDocument::STATUSES, true), 404);

        $documents = MemberDocument::with('member')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.member-documents.index', [
            'documents' => $documents,
            'types' => config('member_documents.types'),
            'statuses' => MemberDocument::STATUSES,
            'status' => $status,
        ]);
    }

    public function issue(Request $request, int $id)
    {
        $validated = $request->validate([
            'document_details' => 'required|string|min:5|max:1500',
        ]);

        $issued = DB::transaction(function () use ($id, $validated) {
            $document = MemberDocument::whereKey($id)->lockForUpdate()->firstOrFail();

            if ($document->status !== 'pending') {
                return false;
            }

            $member = $document->member()->lockForUpdate()->firstOrFail();
            if ($member->status !== 'Approved' || ! $member->is_active) {
                return false;
            }

            $issuedAt = now();
            $document->forceFill([
                'document_details' => trim($validated['document_details']),
                'member_snapshot' => [
                    'id' => $member->id,
                    'full_name' => $member->full_name,
                    'institution' => $member->institution,
                    'course' => $member->course,
                    'department' => $member->department,
                    'year_of_study' => $member->year_of_study,
                    'membership_type' => $member->membership_type,
                    'valid_until' => $member->valid_until?->format('Y-m-d'),
                ],
                'certificate_number' => 'KSO-CHD-'.$issuedAt->format('Y').'-'.Str::upper((string) Str::ulid()),
                'status' => 'issued',
                'issued_by' => auth()->id(),
                'issued_by_name' => auth()->user()->name,
                'issued_at' => $issuedAt,
                'resolution_note' => null,
            ])->save();

            AuditLog::log('MEMBER_DOCUMENT_ISSUED', [
                'document_id' => $document->id,
                'certificate_number' => $document->certificate_number,
                'member_id' => $member->id,
                'document_type' => $document->document_type,
            ]);

            return true;
        });

        if (! $issued) {
            return back()->with('error', 'This request is no longer eligible for issue. Check the member status and request state.');
        }

        return back()->with('success', 'Certificate issued and made available in the member portal.');
    }

    public function reject(Request $request, int $id)
    {
        $validated = $request->validate([
            'resolution_note' => 'required|string|min:5|max:1000',
        ]);

        $rejected = DB::transaction(function () use ($id, $validated) {
            $document = MemberDocument::whereKey($id)->lockForUpdate()->firstOrFail();
            if ($document->status !== 'pending') {
                return false;
            }

            $document->forceFill([
                'status' => 'rejected',
                'resolution_note' => trim($validated['resolution_note']),
            ])->save();

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
        $validated = $request->validate([
            'resolution_note' => 'required|string|min:5|max:1000',
        ]);

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
}
