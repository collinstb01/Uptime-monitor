<?php

namespace App\Mail;

use App\Models\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SiteUpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Monitor $monitor)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Site Recovered: ' . $this->monitor->url);
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.site-up');
    }
}