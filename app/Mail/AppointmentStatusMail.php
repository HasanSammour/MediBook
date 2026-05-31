<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class AppointmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct($appointment, $status)
    {
        $this->appointment = $appointment;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->status) {
            'confirmed' => 'Appointment Confirmed - MediBook',
            'cancelled' => 'Appointment Cancelled - MediBook',
            'completed' => 'Appointment Completed - MediBook',
            default => 'Appointment Update - MediBook'
        };

        return new Envelope(
            to: [new Address($this->appointment->patient->email, $this->appointment->patient->name)],
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-status',
            with: [
                'appointment' => $this->appointment,
                'status' => $this->status,
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
