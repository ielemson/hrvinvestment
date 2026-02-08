<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Message from ' . config('app.name'),
            replyTo: [
                new Address(
                    $this->data['email'],
                    $this->data['first_name'] . ' ' . $this->data['last_name']
                ),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'first_name' => $this->data['first_name'],
                'last_name'  => $this->data['last_name'],
                'email'      => $this->data['email'],
                'phone'      => $this->data['phone'] ?? null,
                'body'    => $this->data['message'],
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
