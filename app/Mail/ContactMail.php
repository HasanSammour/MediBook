<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Get admin email from config or use default
        $adminEmail = config('mail.from_address', 'hasansammour01@gmail.com');
        $adminName = config('mail.from_name', 'MediBook Admin');
        
        return new Envelope(
            to: [new Address($adminEmail, $adminName)],
            from: new Address($this->data['email'], $this->data['name']),
            subject: '📧 New Contact Message: ' . $this->data['subject'],
            replyTo: [new Address($this->data['email'], $this->data['name'])],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'data' => $this->data,
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
