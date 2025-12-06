<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;

class CustomMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $content, string $userName = 'User')
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->userName = $userName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $appName = Setting::getValue('app_name', config('app.name', 'StepProClass'));
        
        return new Envelope(
            subject: $this->subject,
            from: config('mail.from.address'),
            replyTo: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.custom-message',
            with: [
                'subject' => $this->subject,
                'content' => $this->content,
                'userName' => $this->userName,
            ],
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
