<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SiteDownMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public \App\Models\Monitor $monitor)
    {
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Site Down Alert: ' . $this->monitor->url);
    }
    /**
     * Get the message content definition.
     */

    public function content(): Content
    {
        return new Content(markdown: 'emails.site-down');
    }
}

