<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberDocumentAvailableMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $memberName,
        public string $certificateNumber,
        public string $portalUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your KSO Chandigarh certificate is ready');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.member_document_available');
    }
}
