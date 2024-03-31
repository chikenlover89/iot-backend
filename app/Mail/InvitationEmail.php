<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    private string $token;
    private string $type;
    private string $account_name;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $token,
        string $type,
        string $account_name,
    ) {
        $this->token        = $token;
        $this->type         = $type;
        $this->account_name = $account_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Imperium IOT Invites You to a Revolutionary IoT Experience',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->type === Invitation::TYPE_INVITE ? 'invite' : 'login',
            with: ['account_name' => $this->account_name, 'token' => $this->token],
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
