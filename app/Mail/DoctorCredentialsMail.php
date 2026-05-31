<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class DoctorCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $doctor;
    public $password;
    public $isReset;

    /**
     * Create a new message instance.
     */
    public function __construct($doctor, $password, $isReset = false)
    {
        $this->doctor = $doctor;
        $this->password = $password;
        $this->isReset = $isReset;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isReset ? 'Password Reset - MediBook' : 'Welcome to MediBook - Your Login Credentials';
        
        return new Envelope(
            to: [new Address($this->doctor->email, $this->doctor->name)],
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.doctor-credentials',
            with: [
                'doctor' => $this->doctor,
                'password' => $this->password,
                'isReset' => $this->isReset,
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
