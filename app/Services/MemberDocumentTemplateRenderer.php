<?php

namespace App\Services;

use App\Models\Member;
use App\Models\MemberDocumentTemplate;
use Illuminate\Validation\ValidationException;

class MemberDocumentTemplateRenderer
{
    public const STYLES = ['classic', 'blue', 'forest'];

    private const PLACEHOLDERS = [
        'member_name',
        'member_id',
        'institution',
        'course',
        'certificate_details',
        'issued_date',
        'issuer',
        'certificate_number',
        'verification_url',
    ];

    public function validate(MemberDocumentTemplate|string $template, ?string $statement = null): void
    {
        $contents = $template instanceof MemberDocumentTemplate
            ? [$template->title, $template->statement]
            : [$template, (string) $statement];

        foreach ($contents as $content) {
            if (preg_match('/[<>]/', $content)) {
                throw ValidationException::withMessages([
                    'statement' => 'Template content must be plain text; HTML is not allowed.',
                ]);
            }

            preg_match_all('/{{\s*([a-z_]+)\s*}}/', $content, $matches);
            foreach ($matches[1] as $placeholder) {
                if (! in_array($placeholder, self::PLACEHOLDERS, true)) {
                    throw ValidationException::withMessages([
                        'statement' => "The {{$placeholder}} placeholder is not supported.",
                    ]);
                }
            }

            if (preg_match('/{{(?!\s*[a-z_]+\s*}})[^}]*}}/', $content)) {
                throw ValidationException::withMessages([
                    'statement' => 'Template placeholders must use the documented {{placeholder_name}} format.',
                ]);
            }
        }
    }

    public function snapshot(
        MemberDocumentTemplate $template,
        Member $member,
        string $details,
        string $certificateNumber,
        string $issuer,
        string $verificationUrl,
        $issuedAt
    ): array {
        $this->validate($template);

        $values = [
            'member_name' => $member->full_name,
            'member_id' => $member->id,
            'institution' => $member->institution ?? '',
            'course' => trim(implode(' · ', array_filter([$member->course, $member->year_of_study]))),
            'certificate_details' => $details,
            'issued_date' => $issuedAt->format('d M Y'),
            'issuer' => $issuer,
            'certificate_number' => $certificateNumber,
            'verification_url' => $verificationUrl,
        ];

        return [
            'title' => $this->replace($template->title, $values),
            'statement' => $this->replace($template->statement, $values),
            'details' => $details,
            'style' => in_array($template->style, self::STYLES, true) ? $template->style : 'classic',
            'verification_url' => $verificationUrl,
        ];
    }

    public function preview(
        MemberDocumentTemplate $template,
        Member $member,
        string $details,
        string $verificationUrl,
        $issuedAt
    ): array {
        return $this->snapshot($template, $member, $details, 'PREVIEW-CERTIFICATE-NUMBER', 'Authorised Officer', $verificationUrl, $issuedAt);
    }

    private function replace(string $content, array $values): string
    {
        return preg_replace_callback('/{{\s*([a-z_]+)\s*}}/', function ($match) use ($values) {
            return $values[$match[1]] ?? '';
        }, $content);
    }
}
