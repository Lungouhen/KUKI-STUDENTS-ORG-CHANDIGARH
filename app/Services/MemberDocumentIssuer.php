<?php

namespace App\Services;

use App\Mail\MemberDocumentAvailableMail;
use App\Models\Member;
use App\Models\MemberDocument;
use App\Models\MemberDocumentTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class MemberDocumentIssuer
{
    public function __construct(private readonly MemberDocumentTemplateRenderer $renderer) {}

    public function issue(
        MemberDocument $document,
        Member $member,
        MemberDocumentTemplate $template,
        string $details,
        ?int $batchId = null
    ): MemberDocument {
        $issuedAt = now();
        $certificateNumber = 'KSO-CHD-'.$issuedAt->format('Y').'-'.Str::upper((string) Str::ulid());
        $verificationUrl = secure_url(route('documents.verify', $certificateNumber, false));
        $issuer = auth()->user()?->name ?? 'KSO Chandigarh Administration';

        $document->forceFill([
            'document_type' => $template->document_type,
            'document_details' => trim($details),
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
            'content_snapshot' => $this->renderer->snapshot(
                $template,
                $member,
                trim($details),
                $certificateNumber,
                $issuer,
                $verificationUrl,
                $issuedAt
            ),
            'certificate_number' => $certificateNumber,
            'template_id' => $template->id,
            'template_version' => $template->version,
            'batch_id' => $batchId,
            'status' => 'issued',
            'issued_by' => auth()->id(),
            'issued_by_name' => $issuer,
            'issued_at' => $issuedAt,
            'revoked_at' => null,
            'resolution_note' => null,
            'notification_status' => $member->email ? 'pending' : 'unavailable',
        ])->save();

        return $document;
    }

    public function notify(MemberDocument $document): bool
    {
        $member = $document->member;
        if (! $member?->email) {
            $document->update(['notification_status' => 'unavailable']);

            return false;
        }

        try {
            Mail::to($member->email)->send(new MemberDocumentAvailableMail(
                $member->full_name,
                $document->certificate_number,
                route('membership.portal')
            ));
            $document->update([
                'notification_status' => 'sent',
                'notification_sent_at' => now(),
            ]);

            return true;
        } catch (Throwable $exception) {
            report($exception);
            $document->update(['notification_status' => 'failed']);

            return false;
        }
    }
}
