<?php

namespace App\Mail;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $service;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct($service, $status)
    {
        $this->service = $service;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $type = strtolower(Service::TYPE[$this->service->type]);

        return new Envelope(
            subject: "Trạng thái phiếu $type #" . $this->service->code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.services.new',
            with: ['service' => $this->service, 'status' => $this->status]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
