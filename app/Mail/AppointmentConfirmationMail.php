<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class AppointmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $patient;
    public $doctor;


    /**
     * Create a new message instance.
     */
    public function __construct($appointment, $patient, $doctor)
    {
        $this->appointment = $appointment;
        $this->patient = $patient;
        $this->doctor = $doctor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [new Address($this->patient->email, $this->patient->name)],
            subject: 'Appointment Confirmation - MediBook',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-confirmation',
            with: [
                'appointment' => $this->appointment,
                'patient' => $this->patient,
                'doctor' => $this->doctor,
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
