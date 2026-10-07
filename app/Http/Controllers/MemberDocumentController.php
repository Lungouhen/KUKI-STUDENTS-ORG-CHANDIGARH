<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Member;
use App\Models\MemberDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberDocumentController extends Controller
{
    public function storeRequest(Request $request)
    {
        $member = $this->portalMember();
        if (! $member) {
            return redirect()->route('membership.portal');
        }

        if ($member->status !== 'Approved' || ! $member->is_active) {
            return back()->with('error', 'Only active, approved members can request official documents.');
        }

        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:'.implode(',', array_keys(config('member_documents.types')))],
            'purpose' => 'required|string|min:5|max:500',
        ]);

        $document = DB::transaction(function () use ($member, $validated) {
            $lockedMember = Member::whereKey($member->id)->lockForUpdate()->firstOrFail();
            if ($lockedMember->status !== 'Approved' || ! $lockedMember->is_active) {
                return false;
            }

            $pendingExists = $lockedMember->documents()
                ->where('document_type', $validated['document_type'])
                ->where('status', 'pending')
                ->exists();

            if ($pendingExists) {
                return null;
            }

            $document = $lockedMember->documents()->create([
                'document_type' => $validated['document_type'],
                'purpose' => trim($validated['purpose']),
            ]);

            AuditLog::log('MEMBER_DOCUMENT_REQUESTED', [
                'document_id' => $document->id,
                'member_id' => $lockedMember->id,
                'document_type' => $document->document_type,
            ]);

            return $document;
        });

        if ($document === false) {
            return back()->with('error', 'Only active, approved members can request official documents.');
        }

        if (! $document) {
            return back()->with('error', 'You already have a request for this certificate awaiting review.');
        }

        return back()->with('success', 'Your document request was submitted for review.');
    }

    public function show(Request $request, int $id)
    {
        $member = $this->portalMember();
        if (! $member) {
            return redirect()->route('membership.portal');
        }

        $document = MemberDocument::where('member_id', $member->id)
            ->where('status', 'issued')
            ->findOrFail($id);

        return response()
            ->view('membership.documents.show', compact('document'))
            ->header('Cache-Control', 'private, no-store')
            ->header('X-Frame-Options', 'DENY')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    public function verify(string $certificateNumber)
    {
        $document = MemberDocument::with('member')
            ->where('certificate_number', $certificateNumber)
            ->whereIn('status', ['issued', 'revoked'])
            ->firstOrFail();

        return response()
            ->view('membership.documents.verify', compact('document'))
            ->header('Cache-Control', 'no-store')
            ->header('X-Frame-Options', 'DENY')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    private function portalMember(): ?Member
    {
        $memberId = session('member_id');
        if (! $memberId) {
            return null;
        }

        $member = Member::find($memberId);
        if (! $member) {
            session()->forget('member_id');
        }

        return $member;
    }
}
