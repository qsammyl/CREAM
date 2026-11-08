<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnquiryReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Enquiry $enquiry) {}

    public function envelope(): Envelope
    {
        $subject = $this->enquiry->subject ?: $this->enquiry->type->subject();

        // Let staff reply straight to the enquirer where we have their email.
        $replyTo = [];
        if (filled($this->enquiry->email)) {
            $replyTo[] = new Address($this->enquiry->email, $this->enquiry->name);
        }

        return new Envelope(
            subject: "[{$this->enquiry->reference}] {$subject} — CREAM",
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.enquiry-received',
            with: ['url' => route('staff.enquiries.show', $this->enquiry)],
        );
    }
}
