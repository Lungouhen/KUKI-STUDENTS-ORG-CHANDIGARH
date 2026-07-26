<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Member;

class MemberRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public Member $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KSO Chandigarh Membership Registration Received - ' . $this->member->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.member_registered',
        );
    }
}
