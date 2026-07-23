<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserContactCopy extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;
    public string $sense;
    public string $aiResponse;
    public function __construct(array $data, string $sense, string $aiResponse)
    {
        $this->data = $data;
        $this->sense = $sense;
        $this->aiResponse = $aiResponse;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your comment!',
        );
    }

    
    public function content(): Content
    {
        return new Content(
            text: 'user_copy'
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
