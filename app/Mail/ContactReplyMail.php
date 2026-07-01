<?php

namespace App\Mail;

use App\Models\ContactSubmissionReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $messageId;

    public function __construct(
        public ContactSubmissionReply $reply,
    ) {
        $this->messageId = '<reply-' . $reply->id . '-' . $reply->contact_submission_id . '@weddingsbychristian.com>';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: Your Inquiry — Weddings By Christian',
            replyTo: config('mail.from.address'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-reply',
        );
    }

    public function withSymfonyMessage($message): void
    {
        $headers = $message->getHeaders();
        $headers->addTextHeader('Message-ID', $this->messageId);
        $headers->addTextHeader('X-WBC-Submission-ID', (string) $this->reply->contact_submission_id);
    }
}
