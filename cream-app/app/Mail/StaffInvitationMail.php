<?php

namespace App\Mail;

use App\Models\StaffInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public StaffInvitation $invitation,
        public string $plainToken,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'You have been invited to the Credence staff dashboard');
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.staff-invitation',
            with: [
                'url' => route('invitation.accept.show', $this->plainToken),
                'role' => $this->invitation->role->label(),
                'expires' => $this->invitation->expires_at,
            ],
        );
    }
}
