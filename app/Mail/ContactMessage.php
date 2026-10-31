<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use SerializesModels;

    /** @param array{name: string, email: string, subject: string, message: string} $contact */
    public function __construct(public array $contact) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->contact['email'], $this->contact['name'])],
            subject: __('store.contact_email_subject', ['subject' => $this->contact['subject']]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(markdown: 'emails.contact.new', with: ['contact' => $this->contact]);
    }
}
