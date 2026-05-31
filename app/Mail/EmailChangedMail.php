<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class EmailChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $oldEmail;
    public $newEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $oldEmail)
    {
        $this->user = $user;
        $this->oldEmail = $oldEmail;
        $this->newEmail = $user->email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [new Address($this->newEmail, $this->user->name)],
            subject: 'Email Address Changed - MediBook',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.email-changed',
            with: [
                'user' => $this->user,
                'oldEmail' => $this->oldEmail,
                'newEmail' => $this->newEmail,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
